<?php
/**
 * Created by PhpStorm.
 * User: gijs
 * Date: 23/12/2019
 * Time: 19:54
 */

namespace App\Controller;


use App\Entity\User;
use App\Event\RegisterEvent;
use App\Form\UserType;
use App\Security\TokenGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{

    /**
     * @Route("/register", name="user_register")
     */
    public function register(
        UserPasswordEncoderInterface $encoder,
        Request $request,
        EventDispatcherInterface $dispatcher,
        TokenGenerator $tokenGenerator)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword(
                $user,
                $user->getPlainPassword()
            );
            $user->setPassword($password);
            $user->setConfirmationToken($tokenGenerator->getRandomSecureToken(50));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $event = new RegisterEvent($user);
            $dispatcher->dispatch(RegisterEvent::NAME, $event);

            return $this->redirectToRoute('micro_post_index');
        }

        return $this->render('register/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

}