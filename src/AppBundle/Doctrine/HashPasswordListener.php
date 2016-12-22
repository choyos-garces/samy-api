<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 11/3/2016
 * Time: 7:26 PM
 */

namespace AppBundle\Doctrine;


use AppBundle\Document\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class HashPasswordListener implements EventSubscriber
{
    /**
     * @var UserPasswordEncoder
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoder $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();
        if(!$document instanceof User) {
            return;
        }

        $this->encodePassword($document);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $document = $args->getDocument();
        if(!$document instanceof User) {
            return;
        }

        $this->encodePassword($document);
        $dm = $args->getDocumentManager();
        $meta = $dm->getClassMetadata(get_class($document));
        $dm->getUnitOfWork()->recomputeSingleDocumentChangeSet($meta, $document);
    }

    public function getSubscribedEvents()
    {
        return ['preUpdate', 'prePersist'];
    }

    /**
     * @param User $document
     */
    private function encodePassword(User $document)
    {
        if (!$document->getPlainPassword()) {
            return;
        }

        $encoded = $this->passwordEncoder
            ->encodePassword(
                $document,
                $document->getPlainPassword()
            );
        
        $document->setPassword($encoded);
    }
}