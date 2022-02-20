<?php

namespace App\DataFixtures;

use App\Entity\Page;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist(
            (new User())
                ->setUserIdentifier('user')
                ->setRole(User::ROLES['user'])
        );

        $manager->persist(
            (new User())
                ->setUserIdentifier('admin')
                ->setRole(User::ROLES['admin'])
        );

        $manager->persist(
            (new Page())
            ->setTitle('TEST')
            ->setSlug('test')
        );

        $manager->flush();
    }
}
