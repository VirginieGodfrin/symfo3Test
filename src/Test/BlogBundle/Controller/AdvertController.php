<?php

namespace Test\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdvertController extends Controller {

	public function indexAction()
    {

        $content= $this->get('templating')->render('TestBlogBundle:Advert:index.html.twig', array ('nom'=>'Godfrin'));
        return new Response($content);
    }
}
