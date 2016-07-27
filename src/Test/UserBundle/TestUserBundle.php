<?php

namespace Test\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class TestUserBundle extends Bundle
{
	public function getParent()
  	{
    	return 'FOSUserBundle';
  	}
}
