<?php
/**
 * Created by PhpStorm.
 * User: gijs
 * Date: 01/01/2020
 * Time: 19:31
 */

namespace App\Mailer;


use App\Entity\User;

class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var \Twig\Environment
     */
    private $twig;
    /**
     * @var string
     */
    private $mailFrom;

    public function __construct(\Swift_Mailer $mailer, \Twig\Environment $twig, string $mailFrom)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->mailFrom = $mailFrom;
    }

    public function sendConfirmationEmail(User $user)
    {
        $html = $this->twig->render('email/registration.html.twig', [
            'user' => $user,
        ]);

        $message = (new \Swift_Message())
            ->setFrom($this->mailFrom)
            ->setTo($user->getEmail())
            ->setBody($html, 'text/html')
            ->setSubject('Welcome!');

        $this->mailer->send($message);
    }

}