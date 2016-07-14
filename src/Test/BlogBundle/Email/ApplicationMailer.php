<?php

namespace Test\BlogBundle\Email;

use Test\BlogBundle\Entity\Application;

class ApplicationMailler{

	/**
	 * @var \Swift_Mailer
	 */
	private $mailer;

	public function __construct(\Swift_Mailer $mailer){
		$this->mailer = $mailer;
	}

	public function sendNewNotification(Application $application){
		$message = new \Swift_Message(
			'Nouvelle candidature', 
			'Vous avez reçu une nouvelle candidature.'
		);

		$message
			->addTo($application->getAdvert()->getAuthor())
						//// Ici bien sûr il faudrait un attribut "email", j'utilise "author" à la place
			->addFrom('admin@votresite.com');

		$this->mailer->send($message);	
	}
}