<?php
/**
 * Created by PhpStorm.
 * User: gijs
 * Date: 02/01/2020
 * Time: 14:43
 */

namespace App\Tests\Mailer;


use App\Entity\User;
use App\Mailer\Mailer;
use PHPUnit\Framework\TestCase;

class MailerTest extends TestCase
{

    public function testConfirmationEmail()
    {
        $user = new User();
        $user->setEmail('test@example.com');

        $swiftMailer = $this->createMock(\Swift_Mailer::class);
        $twig = $this->createMock(\Twig\Environment::class);

        $swiftMailer->expects($this->once())
            ->method('send')
            ->with($this->callback(function ($subject) {
                $messageStr = (string)$subject;

                return strpos($messageStr, 'From: from@example.com') !== false
                    && strpos($messageStr, 'To: test@example.com') !== false
                    && strpos($messageStr, 'Content-Type: text/html') !== false
                    && strpos($messageStr, 'Subject: Welcome!') !== false
                    && strpos($messageStr, 'This is a body message') !== false;
            }));

        $twig->expects($this->once())
            ->method('render')
            ->with('email/registration.html.twig', [
                    'user' => $user
                ])
            ->willReturn('This is a body message')
        ;

        $mailer = new Mailer($swiftMailer, $twig, 'from@example.com');
        $mailer->sendConfirmationEmail($user);
    }

}