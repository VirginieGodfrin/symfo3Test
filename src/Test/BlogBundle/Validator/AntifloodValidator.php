<?php

namespace Test\BlogBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class AntifloodValidator extends ConstraintValidator{

	private $RequestStack;
	private $em;

	public function __construct(RequestStack $requestStack, EntityManagerInterface $em){
		$this->requestStack = $requestStack;
		$this->em= $em; 
	}

  	public function validate($value, Constraint $constraint){

  		$request = $this->requestStack->getCurrentRequest(); //recup l'objet request

  		$ip = $request->getClientIp();//recup l'ip

  		// On vérifie si cette IP a déjà posté une candidature il y a moins de 15 secondes
  		$isFlood = $this->em
  			->getRepository('TestBlogBundle:Application')
  			->isFlood($ip, 15); // ecrire la fonction isFlood

  		if($isFlood){
  			// C'est cette ligne qui déclenche l'erreur pour le formulaire, avec en argument le message
  			$this->context->addViolation($constraint->message);
  		}

    // Pour l'instant, on considère comme flood tout message de moins de 3 caractères
    	if (strlen($value) < 3) {
      // C'est cette ligne qui déclenche l'erreur pour le formulaire, avec en argument le message de la contrainte
      		$this->context->addViolation($constraint->message);
    	}
  }
}