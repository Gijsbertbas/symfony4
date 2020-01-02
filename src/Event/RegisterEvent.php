<?php
/**
 * Created by PhpStorm.
 * User: gijs
 * Date: 01/01/2020
 * Time: 15:21
 */

namespace App\Event;


use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class RegisterEvent extends Event
{

    const NAME = 'user.register';

    /**
     * @param User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }

}