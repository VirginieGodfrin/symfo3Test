<?php

namespace Test\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//génération d'un URL absolue
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

//redirection 
use Symfony\Component\HttpFoundation\RedirectResponse; 

//service
use Symfony\Component\DependencyInjection\Loader;

use Test\BlogBundle\Entity\Advert;
use Test\BlogBundle\Entity\AdvertSkill;
use Test\BlogBundle\Entity\Application;
use Test\BlogBundle\Entity\Image;

//formulaire
use Test\BlogBundle\Form\AdvertType;



class AdvertController extends Controller {

    public function indexAction($page){

        if($page < 1 ){
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        $nbPerPage = 3;
        // à défaut de $this->container->getParameter('nb_per_page')

        $em = $this->getDoctrine()->getManager();

        $listAdverts = $em
          ->getRepository('TestBlogBundle:Advert')
          ->myFindAllOrder($page, $nbPerPage);

        $nbPages = ceil(count($listAdverts)/$nbPerPage);
        var_dump(count($listAdverts));

        if($page > $nbPages){
          throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }  

        return $this->render('TestBlogBundle:Advert:index.html.twig', array(
          'listAdverts' => $listAdverts,
          'nbPages' => $nbPages,
          'page' => $page )
        );
    }

    public function menuAction($limit){
        $em = $this->getDoctrine()->getManager();

        //$listAdverts = $em->getRepository('TestBlogBundle:Advert')->findAll();

        $listAdverts = $em->getRepository('TestBlogBundle:Advert')
          ->findBy(
              array(),//pas de critères
              array('date' => 'desc'),//trié par date decroissante
              $limit, // On sélectionne limit annonces
              0 // À partir du premier
          );

        return $this->render('TestBlogBundle:Advert:menu.html.twig', 
            array('listAdverts' => $listAdverts)
        );

    }

    public function viewAction($id){

      $em = $this->getDoctrine()->getManager();

      $advert = $em->getRepository('TestBlogBundle:Advert')->find($id);

      if($advert === null){
        throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
      }

      $listApplications = $em 
        ->getRepository('TestBlogBundle:Application')
        ->findBy(array('advert' => $advert));

      $listAdvertSkills = $em
        ->getRepository('TestBlogBundle:AdvertSkill')
        ->findBy(array('advert' => $advert ));  

      return $this->render('TestBlogBundle:Advert:view.html.twig', array(
        'advert' => $advert,
        'listApplications' => $listApplications,
        'listAdvertSkills' => $listAdvertSkills
      ));
    }

    public function addAction(Request $request){

      $advert = new Advert();
      $advert->setDate(new \Datetime());

      $form = $this->get('form.factory')->create(AdvertType::class, $advert);

      if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      
        $advert->getImage()->upload();

        $em = $this->getDoctrine()->getManager();
        $em->persist($advert);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

        return $this->redirectToRoute('blog_view', array('id' => $advert->getId()));
    }

    return $this->render('TestBlogBundle:Advert:add.html.twig', array(
      'form' => $form->createView(),
    ));
  }


    public function editAction($id, Request $request){

      $em = $this->getDoctrine()->getManager();

      $advert = $em->getRepository('TestBlogBundle:Advert')->find($id);
      //$advert->setDate(new \Datetime());

      if (null === $advert) {
        throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
      }

      $form = $this->get('form.factory')->create(AdvertType::class, $advert);


      if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($advert);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

        return $this->redirectToRoute('blog_view', array('id' => $advert->getId()));
    }

      return $this->render('TestBlogBundle:Advert:edit.html.twig', array(
      'form' => $form->createView(),
      'advert' => $advert,
    ));

    }

    public function deleteAction($id){

      $em = $this->getDoctrine()->getManager();

    // On récupère l'annonce $id
      $advert = $em->getRepository('TestBlogBundle:Advert')->find($id);

      if (null === $advert) {
        throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
      }

    // On boucle sur les catégories de l'annonce pour les supprimer
      foreach ($advert->getCategories() as $category) {
        $advert->removeCategory($category);
      }

      $em->flush();

      return $this->render('TestBlogBundle:Advert:delete.html.twig');
    }

//autres méthodes ....  

    public function testAction(){
        return $this->render('TestBlogBundle:Advert:test.html.twig');
    }

    public function redirectAction(){
        $url = $this->get('router')->generate('blog_test');
        return new redirectResponse($url);
    }

    public function viewSlugAction ($slug, $year, $_format){
    	return new Response(
    			"affiche : ".$slug." crée en : ".$year. " au format :".$_format.". bla bla bla"

    		);
    }

//fonction pour modifier Image
    public function editImageAction($advertId)
    {
      $em = $this->getDoctrine()->getManager();

      // On récupère l'annonce
      $advert = $em->getRepository('TestBlogBundle:Advert')->find($advertId);

      // On modifie l'URL de l'image par exemple
      $advert->getImage()->setUrl('test.png');

      // On n'a pas besoin de persister l'annonce ni l'image.
      // Rappelez-vous, ces entités sont automatiquement persistées car
      // on les a récupérées depuis Doctrine lui-même
      
      // On déclenche la modification
      $em->flush();

      return new Response('OK');
    }

//fonction pour lister les application en focntion d'un article 
  public function listAction(){

      $listAdverts = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('OCPlatformBundle:Advert')
        ->getAdvertWithApplications()
    ;

    foreach ($listAdverts as $advert) {
      // Ne déclenche pas de requête : les candidatures sont déjà chargées !
      // Vous pourriez faire une boucle dessus pour les afficher toutes
      $advert->getApplications();
    }
  }

  

}

//presonaliser une page d'erreure : https://openclassrooms.com/courses/developpez-votre-site-web-avec-le-framework-symfony2/personnaliser-les-pages-d-erreur


