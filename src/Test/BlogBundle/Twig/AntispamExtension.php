<?php

namespace Test\BlogBundle\Twig;

use Test\BlogBundle\Antispam\BlogAntispam;

class AntispamExtension extends \Twig_Extension
{
	/**
	 * @var blogAntispam
	 */
	private $blogAntispam;

	public function __construct(BlogAntispam $blogAntispam){
		$this->blogAntispam = $blogAntispam;
	}

	public function checkIfArgumentIsSpam($text){
		return $this->blogAntispam->isSpam($text);
	}

	public function getFunctions(){
		return array(
			new \Twig_SimpleFunction('checkIfSpam', 
				array($this, 'checkIfArgumentIsSpam')),
			);
	}

	public function getName(){
		return 'blogAntispam';
	}
}