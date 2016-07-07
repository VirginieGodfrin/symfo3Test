<?php

namespace Test\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

//génération d'un URL absolue
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AdvertController extends Controller {

	public function indexAction(){

        $content= $this->get('templating')->render('TestBlogBundle:Advert:index.html.twig', 
        	array ('nom'=>'Godfrin')
        	);
        return new Response($content);

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

    public function viewAction($id){
    	return new Response("affichage de l'annonce d'id : " .$id);
    }

    public function viewSlugAction ($slug, $year, $_format){
    	return new Response(
    			"affiche : ".$slug." crée en : ".$year. " au format :".$_format.". bla bla bla"

    		);
    }
}


