<?php

namespace Test\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Test\UserBundle\Entity\User;

class LoadUser implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {

    $listNames = array('Marc', 'Denise','Luc' );

    foreach ($listNames as $name) {
      $user = new User();

      $user->setUsername($name);

      $user->setPassword($name);

      $user->setSalt('');

      $user->setRoles(['ROLE_USER']);

      $manager->persist($user);
    }

    $manager->flush();
  }

}