<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 11/10/2016
 * Time: 2:54 PM
 */

namespace AppBundle\Doctrine;


use AppBundle\Document\ClientUserRegister;
use AppBundle\Document\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\PreFlushEventArgs;
use Doctrine\ODM\MongoDB\Event\PostFlushEventArgs;
use Doctrine\ODM\MongoDB\Event\PreUpdateEventArgs;
use Doctrine\ODM\MongoDB\Events;

class UserRegisterListner implements EventSubscriber
{

    /**
     * @var User
     */
    private $user = [];

    public function getSubscribedEvents()
    {
        return [Events::onFlush, Events::postFlush];
    }


    public function preFlush(PreFlushEventArgs $eventArgs) {
        $document = $eventArgs->getDocument();
        if(!$document instanceof User) {
            return;
        }

        $this->user = $eventArgs->getDocument();
    }

    public function postFlush(PostFlushEventArgs $eventArgs) {
        echo "Hello";

        if(empty($this->user)) {
            return;
        }

        echo "{$this->user->getUsername()}";
        $dm = $eventArgs->getDocumentManager();

        $clients = $dm->getRepository("AppBundle:Client")->findAll();
        foreach ($clients as $client) {
            $register = new ClientUserRegister();
            $register->setActive(false);
            $register->setClient($client);
            $register->setUser($this->user);
            //$dm->persist($register);
            //$dm->flush();
        }

        $dm->clear();
    }
}