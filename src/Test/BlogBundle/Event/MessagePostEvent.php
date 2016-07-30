<?php

namespace Test\BlogBundle\Event; 

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;

//une classe qui étend la classe Event du composant EventDispatcher.
Class MessagePostEvent extends Event{

	protected $message;
	protected $user;

	public function __construct($message, UserInterface $user){
		$this->message = $message;
		$this->user = $user;
	}

	public function getMessage(){
		return $this->message;
	}

	public function getUser(){
		return $this->user;
	}

}