<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{


    public function __construct( private readonly UserPasswordHasherInterface $hasher )
    {

    }


    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

           $user = new User() ;

           $user->setUsername("admin")
              ->setEmail("admin@gmail.com")
              ->setRoles(["ROLE_ADMIN"])
              ->setApiToken("admin")
              ->setPassword($this->hasher->hashPassword($user,"1234"))
           ;

           $manager->persist($user);


        for ($i=1; $i <= 10; $i++) {
            $user = new User();
            $user->setUsername('user '. $i);
            $user->setEmail('user'. $i.'@gmail.com')
            ->setApiToken('user'. $i)
            ->setPassword($this->hasher->hashPassword($user, '1234'))
            ;
            $manager->persist($user);
            $this->addReference('User'.$i , $user);
        }

        $manager->flush();
    }
}
