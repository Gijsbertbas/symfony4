<?php
/**
 * Created by PhpStorm.
 * User: gijs
 * Date: 15/12/2019
 * Time: 20:13
 */

namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController
{

    /**
     * @var \Twig\Environment
     */
    private $twig;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        return new Response($this->twig->render(
            'security/login.html.twig',
            [
                'last_username' => $authenticationUtils->getLastUsername(),
                'error' => $authenticationUtils->getLastAuthenticationError(),
            ]
        ));

    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {

    }

    /**
     * @Route("/confirm/{token}", name="security_confirm")
     */
    public function confirm(string $token, UserRepository $repository, EntityManagerInterface $entityManager)
    {
        $user = $repository->findOneBy([
                'confirmationToken' => $token,
        ]);

        if (!$user instanceof User) {
            throw new NotFoundHttpException('Confirmation token not known');
        }

        $user->setEnabled(true);
        $user->setConfirmationToken('');
        $entityManager->flush();

        return new Response($this->twig->render('security/confirmation.html.twig', [
            'user' => $user
            ]
        ));

    }

}