<?php

namespace Test\BigBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class BigController extends Controller{

	public function indexAction(){
		return $this->render('TestBigBundle:Big:index.html.twig');
	}

	public function contactAction(Request $request){
		$session = $request->getSession();
		$session->getFlashBag()->add('info', 
			'La page de contact n’est pas encore disponible, merci de revenir plus tard.');
		return $this->redirectToRoute('big_home');
	}
} 