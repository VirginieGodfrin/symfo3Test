<?php
namespace Test\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Test\BlogBundle\Entity\Skill;

class LoadSkill implements FixtureInterface{

  public function load(ObjectManager $manager){
  	
    $names = array('Expert', 'Débutant', 'Qualifié', 'Null');

    foreach ($names as $name) {

      $skill = new Skill();
      $skill->setName($name);

      $manager->persist($skill);
    }

    $manager->flush();

  }
}