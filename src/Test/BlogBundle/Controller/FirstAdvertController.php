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

        /*$content= $this->get('templating')->render('TestBlogBundle:Advert:index.html.twig', 
        	array ('nom'=>'Godfrin')
        	);
        return new Response($content);*/


// générer une URL
        /*$url = $this->get('router')->generate( //appel de la méthode generate
        	'blog_view', //le nom de la route
        	array('id'=>5) //le ou les parametres
        );*/
        
//générer une URL absolue
        /*$url = $this->get('router')->generate('blog_view', 
        	array('id'=>5), 
        	UrlGeneratorInterface::ABSOLUTE_URL);*/

    
        /*return new Response ("l'url de l'annonce id 5 est : " .$url);*/
    

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

//Request
    /* public function viewAction($id, Request $request){...}
     * request permet de récupérer les parametres utiles à la requête: blog/advert/5?tag=vivi&nom=godfrin
     *  $tag = $request->query->get('tag');
     *  $nom = $request->query->get('nom');
     *  query sert à recupérer les parametres passés en get (clic sur lien, url)
     *  request sert à récupérer les parametres passés en post (envoi d'un formulaire)
     *      ex: if ($request->isMethod('POST')){traitement du formulaire}
     *  les méthodes de l'objet request : http://api.symfony.com/3.0/Symfony/Component/HttpFoundation/Request.html
     */

     //	return new Response("affichage de l'annonce d'id : " .$id. ", tag: " .$tag);
       

    /*
     * return $this->get('templating')->renderResponse(
     * 'TestBlogBundle:Advert:index.html.twig',
     *       array('id' => $id, 'tag' => $tag,'nom' =>$nom )
     *  );
     */
      
    //recupération du repo
        $repository = $this->getDoctrine()->getManager
            ->getRepository('TestBlogBundle:Advert');

    //récup de l'entité en fonction de id $advert intance de l'entité Advert
        $advert = $repository->find($id); 

        if( null === $advert){
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        return $this->render('TestBlogBundle:Advert:view.html.twig', 
            array('id' => $id)
            );
    }

    /*public function addAction(Request $request){
        $session = $request->getSession();
    
        // Bien sûr, cette méthode devra réellement ajouter l'annonce
    
        // Mais faisons comme si c'était le cas
        $session->getFlashBag()->add('info', 'Annonce bien enregistrée');

        // Le « flashBag » est ce qui contient les messages flash dans la session
        // Il peut bien sûr contenir plusieurs messages :
        $session->getFlashBag()->add('info', 'Oui oui, elle est bien enregistrée !');

        // Puis on redirige vers la page de visualisation de cette annonce
        return $this->redirectToRoute('blog_view', array('id' => 5));
    }*/

    public function addAction(Request $request){

    // recup du service antispam exemple
        /*
         * $antispam = $this->container->get('test_blog.antispam');
         * $text= " bla bla pour test";
         * if ($antispam->isSpam($text)) {
         *  throw new \Exception('Votre message a été détecté comme spam !');
         * } 
        */
        $advert = new Advert();
        //...
        $image = new Image();
        //...
        $advert->setImage($image);

        $appli1 = new Application();
        //...

        $appli2 = new Appliaction();
        //...

        $appli1->setAdvert($advert);
        $appli2->setAdvert($advert); 

        $em= $this->getDoctrine()->getManager();

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

        if($request->isMethod('POST')){

            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

            return $this->redirectToRoute('blog_view', array('id'=>5));
        }
        return $this->render('TestBlogBundle:Advert:edit.html.twig');

    }

    public function deleteAction($id){
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

//fonction pour modiier Image
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


