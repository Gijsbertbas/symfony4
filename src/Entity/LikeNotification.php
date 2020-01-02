<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LikeNotificationRepository")
 */
class LikeNotification extends Notification
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MicroPost")
     */
    private $microPost;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $likeBy;

    /**
     * @return mixed
     */
    public function getMicroPost()
    {
        return $this->microPost;
    }

    /**
     * @param mixed $microPost
     */
    public function setMicroPost($microPost): void
    {
        $this->microPost = $microPost;
    }

    /**
     * @return mixed
     */
    public function getLikeBy()
    {
        return $this->likeBy;
    }

    /**
     * @param mixed $likeBy
     */
    public function setLikeBy($likeBy): void
    {
        $this->likeBy = $likeBy;
    }
}
