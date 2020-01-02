<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FollowNotificationRepository")
 */
class FollowNotification extends Notification
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    public $follower;

    /**
     * @return mixed
     */
    public function getFollower()
    {
        return $this->follower;
    }

    /**
     * @param mixed $follower
     */
    public function setFollower($follower): void
    {
        $this->follower = $follower;
    }
}
