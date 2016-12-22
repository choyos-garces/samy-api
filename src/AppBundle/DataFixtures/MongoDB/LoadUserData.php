<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 11/2/2016
 * Time: 11:29 PM
 */

namespace AppBundle\DataFixtures\MongoDB;


use AppBundle\Document\Client;
use AppBundle\Document\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $client = new Client();
        $client->setActive(true);
        $client->setName("Samy");
        $client->setUrl("/");
        $manager->persist($client);

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@samy.com');
        $admin->setPlainPassword('password739');
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $manager->flush();
    }
}