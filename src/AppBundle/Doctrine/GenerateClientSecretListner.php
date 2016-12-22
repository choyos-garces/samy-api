<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 11/6/2016
 * Time: 12:10 PM
 */

namespace AppBundle\Doctrine;

use AppBundle\Document\Client;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;

class GenerateClientSecretListner implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return ['prePersist'];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();
        if(!$document instanceof Client) {
            return;
        }

        $secret = $this->generateSecret(32);
        $document->setSecret($secret);
    }

    public function generateSecret($length)
    {
        //$alphabet  = "ABCDEFGHIJKLMNOPQRSTVWXYZ";
        $alphabet = "abcdefghijklmnopqrstvwxyz";
        $alphabet .= "0123456789";

        $alphabetLength = strlen($alphabet);

        $secret = "";
        for($i = 0; $i < $length; $i++)
        {
            $rand = rand(0, $alphabetLength-1);
            $secret .= $alphabet[$rand];
        }

        return $secret;
    }

}