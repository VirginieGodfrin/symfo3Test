<?php

namespace Test\BlogBundle\Antispam;

 class BlogAntispam{

 	public function isSpam($text){

		return strlen($text) < 50 ;

	}
 }
