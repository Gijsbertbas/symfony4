<?php
/**
 * Created by PhpStorm.
 * User: gijs
 * Date: 26/12/2019
 * Time: 22:55
 */

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_USER')")
 * @Route("/following")
 */
class FollowingController extends AbstractController
{
    /**
     * @Route("/follow/{id}", name="following_follow")
     */
    public function follow(User $userToFollow, int $id, EntityManagerInterface $em)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if ($currentUser->getId() !== $id) {
            $currentUser->follow($userToFollow);

            $uow = $em->getUnitOfWork();
//            dump($uow);
//            die;

            $this->getDoctrine()->getManager()->flush();

        }
        return $this->redirectToRoute(
            'micro_post_user',
            ['username' => $userToFollow->getUsername()]
        );
    }

    /**
     * @Route("/unfollow/{id}", name="following_unfollow")
     */
    public function unFollow(User $userToUnfollow)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $currentUser->getFollowing()->removeElement($userToUnfollow);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute(
            'micro_post_user',
            ['username' => $userToUnfollow->getUsername()]
        );
    }

}