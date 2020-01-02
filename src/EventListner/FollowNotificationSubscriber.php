<?php
/**
 * Created by PhpStorm.
 * User: gijs
 * Date: 31/12/2019
 * Time: 12:24
 */

namespace App\EventListner;


use App\Entity\FollowNotification;
use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\PersistentCollection;

class FollowNotificationSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::onFlush
        ];
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        /** @var PersistentCollection $collectionUpdate */
        foreach ($uow->getScheduledCollectionUpdates() as $collectionUpdate) {

            if (!$collectionUpdate->getOwner() instanceof User) {
                continue;
            }

            if ('following' !== $collectionUpdate->getMapping()['fieldName']) {
                continue;
            }

            $insertDiff = $collectionUpdate->getInsertDiff();

            if (!count($insertDiff)) {
                return;
            }

            /** @var User $userWhoFollows */
            $userWhoFollows = $collectionUpdate->getOwner();

            $notification = new FollowNotification();
            $notification->setUser(reset($insertDiff));
            $notification->setFollower($userWhoFollows);

            $em->persist($notification);

            $uow->computeChangeSet(
                $em->getClassMetadata(FollowNotification::class),
                $notification
            );
        }
    }
}