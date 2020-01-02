<?php
/**
 * Created by PhpStorm.
 * User: gijs
 * Date: 01/01/2020
 * Time: 15:28
 */

namespace App\EventListner;


use App\Entity\UserPreferences;
use App\Event\RegisterEvent;
use App\Mailer\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RegisterSubscriber implements EventSubscriberInterface
{

    /**
     * @var Mailer
     */
    private $mailer;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var string
     */
    private $defaultLocale;

    public function __construct(Mailer $mailer, EntityManagerInterface $em, string $defaultLocale)
    {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->defaultLocale = $defaultLocale;
    }

    public static function getSubscribedEvents()
    {
        return [
            RegisterEvent::NAME => 'onUserRegister',
        ];
    }

    public function onUserRegister(RegisterEvent $event)
    {
        $preferences = new UserPreferences();
        $preferences->setLocale($this->defaultLocale);
        $this->em->persist($preferences);

        $user = $event->getUser();
        $user->setPreferences($preferences);

        $this->em->flush();
        $this->mailer->sendConfirmationEmail($user);
    }
}