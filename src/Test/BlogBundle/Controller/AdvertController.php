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

class AdvertController extends Controller {

    public function indexAction($page){

        if($page < 1 ){
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }
        return $this->render('TestBlogBundle:Advert:index.html.twig', 
            array('listAdverts'=>array())
        );
    }

    public function menuAction(){
        $listAdverts = array(
            array('id' => 2, 'title' => 'Recherche développeur Symfony'),
            array('id' => 5, 'title' => 'Mission de webmaster'),
            array('id' => 9, 'title' => 'Offre de stage webdesigner')
        );

        return $this->render('TestBlogBundle:Advert:menu.html.twig', 
            array('listAdverts' => $listAdverts)
        );

    }

    public function viewAction($id){

      $em = $this->getDoctrine()->getManager();

      $advert = $em->getRepository('TestBlogBundle:Advert')->find($id)

      if( null === $advert){
        throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
      }

      $listApplications = $em 
        ->getRepository('TestBlogBundle:Application')
        ->findBy(array('advert' => $advert));

      $listAdvertSkills = $em
        ->getRepository('TestBlogBundle:AdvertSkill')
        ->findBy(array('advert' => $advert ));  

      return $this->render('TestBlogBundle:Advert:view.html.twig', array(
        'id' => $id,
        'listApplications' => $listApplications,
        'listAdvertSkills' => $listAdvertSkills
      ));
    }

    public function addAction(Request $request){

      $em= $this->getDoctrine()->getManager();

        $advert = new Advert();
        //...
        $image = new Image();
        //...
        $advert->setImage($image);

        $appli1 = new Application();
        //...

        $appli2 = new Appliaction();
        //...

      //on lie les advert aux applications
        //$appli1->setAdvert($advert);
        //$appli2->setAdvert($advert); 

      //on lie les applications aux annonces
        //$advert->addApplication($appli1);
        //$adevrt->addApplication($appli2);

        $listSkill = $em->getRepository('TestBlogBundle:Skill')->findAll();

          foreach ($listSkill as $skill) {
            
            $advertSkill = new AdvertSkill();
            $advertSkill->setAdvert($advert);
            $advertSkill->setSkill($skill);
            $advertSkill->setLevel('Null');

            $em->persist($advertSkill);
          }

        

        $em->persist($advert);

        $em->persist($appli1);

        $em->persist($appli2);

        $em->flush();

        if($request->isMethod('POST')){
    //objet session
            $request->getSession()->getFlashbag()->add('notice', 'annonce enrégistrée');

            return $this->redirectToRoute('blog_view', 
                array('id'=>$advert->getId())
            );
        }

        return $this->render('TestBlogBundle:Advert:add.html.twig');
    }

    public function editAction($id, Request $request){

      $em = $this->getDoctrine()->getManager();

      $advert = $em->getRepository('TestBlogBundle:Advert')->find($id);

      if (null === $advert) {
        throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
      }

      $listCategories = $em->getRepository('TestBlogBundle:Category')->findAll();

      //boucle sur le tableau listCategories pour les liées à un article
      foreach ($listCategories as $Category) {
        $advert->addCategory($category);
      }

      $em->flush();

        if($request->isMethod('POST')){

           $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

            return $this->redirectToRoute('blog_view', array('id'=>5));
        }
        return $this->render('TestBlogBundle:Advert:edit.html.twig');

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
}

//presonaliser une page d'erreure : https://openclassrooms.com/courses/developpez-votre-site-web-avec-le-framework-symfony2/personnaliser-les-pages-d-erreur


