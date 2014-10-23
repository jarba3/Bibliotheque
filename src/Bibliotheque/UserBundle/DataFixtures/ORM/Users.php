<?php

namespace Bibliotheque\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Bibliotheque\UserBundle\Entity\User;


class LoadUser implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Les noms d'utilisateurs à créer
    $listNames = array('Alexandre', 'Marine', 'Anna');
    $listNames2 = array('Robert', 'Etienne');
    $listNames3 = array('Jacqueline', 'Bernadette');
    $listNames4 = array('Bastien', 'Arnaud');

    foreach ($listNames as $name) {
      
      $user = new User;
      
      $user->setUsername($name);
      $user->setPassword($name);
      $user->setSalt('');
      $user->setRoles(array('ROLE_ETUDIANT'));
      $manager->persist($user);
    }

    foreach ($listNames2 as $name) {
      $user = new User;

      $user->setUsername($name);
      $user->setPassword($name);
      $user->setSalt('');
      $user->setRoles(array('ROLE-PROFESSEUR'));
      $manager->persist($user);
    }
    foreach ($listNames3 as $name) {
      $user = new User;

      $user->setUsername($name);
      $user->setPassword($name);
      $user->setSalt('');
      $user->setRoles(array('ROLE-BIBLIOTHECAIRE'));
      $manager->persist($user);
    }
    foreach ($listNames4 as $name) {
      $user = new User;

      $user->setUsername($name);
      $user->setPassword($name);
      $user->setSalt('');
      $user->setRoles(array('ROLE-ADMIN'));
      $manager->persist($user);
    }

    $manager->flush();
  }
}