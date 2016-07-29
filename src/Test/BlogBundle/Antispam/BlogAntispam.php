<?php

namespace Test\BlogBundle\Antispam;

 class BlogAntispam{

 	private $mailer;
 	private $locale;
 	private $minLenght;

 	public function __construct(\SWIFT_MAILER $mailer, $minLenght){
 		$this->mailer = $mailer;
 		$this->minLenght = (int) $minLenght;
 	}

 	public function setLocale($locale){
 		$this->locale = $locale;
 	}

 	public function isSpam($text){

		return strlen($text) < 50 ;

	}
 }
