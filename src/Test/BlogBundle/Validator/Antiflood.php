<?php

namespace Test\BlogBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Antiflood extends Constraint
{
<<<<<<< HEAD
	public $message = "Vous avez déjà posté un message il y a moins de 15 secondes, 
  	merci d'attendre un peu.";

  	public function validatedBy(){
  		return 'test_blog_antiflood';  //appel à l'alias du service
  	}


=======
  public $message = "Vous avez déjà posté un message il y a moins de 15 secondes, 
  	merci d'attendre un peu.";
>>>>>>> 33991b963b422a556fc1800b4d86bbe57815f926
}