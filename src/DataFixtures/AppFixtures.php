<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Entity\UserPreferences;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private const USERS = [
        [
            'username' => 'gijs',
            'email' => 'test@mail.com',
            'password' => 'gijs123',
            'fullName' => 'Gijs Straathof',
            'role' => [User::ROLE_ADMIN]
        ],
        [
            'username' => 'john',
            'email' => 'john_doe@doe.com',
            'password' => 'john123',
            'fullName' => 'John Doe',
            'role' => [User::ROLE_USER],
        ],
        [
            'username' => 'rob',
            'email' => 'rob_smith@smith.com',
            'password' => 'rob123',
            'fullName' => 'Rob Smith',
            'role' => [User::ROLE_USER],
        ],
        [
            'username' => 'marry',
            'email' => 'marry_gold@gold.com',
            'password' => 'marry123',
            'fullName' => 'Marry Gold',
            'role' => [User::ROLE_USER],
        ],
    ];

    private const LOCALE = ['en', 'fr'];

    private const POST_TEXT = [
        'Hello, how are you?',
        'It\'s nice sunny weather today',
        'I need to buy some ice cream!',
        'I wanna buy a new car',
        'There\'s a problem with my phone',
        'I need to go to the doctor',
        'What are you up to today?',
        'Did you watch the game yesterday?',
        'How was your day?'
    ];

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadMicroPosts($manager);
    }

    public function loadMicroPosts(ObjectManager $manager)
    {
        for ($i = 0; $i < 30; $i++) {
            $microPost = new MicroPost();
            $microPost->setText(
                self::POST_TEXT[rand(0, count(self::POST_TEXT)-1)]
            );
            $date = new \DateTime();
            $date->modify('-'. rand(0,10) . ' day');
            $microPost->setTime($date);
            $microPost->setUser($this->getReference(
                self::USERS[rand(0, count(self::USERS) -1)]['username']));
            $manager->persist($microPost);
        }
        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager)
    {
        foreach (self::USERS as $fixtureUser) {
            $user = new User();
            $user->setUsername($fixtureUser['username']);
            $user->setEmail($fixtureUser['email']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $fixtureUser['password']));
            $user->setFullName($fixtureUser['fullName']);
            $user->setRoles($fixtureUser['role']);
            $user->setEnabled(true);

            $this->addReference($fixtureUser['username'], $user);

            $preferences = new UserPreferences();
            $preferences->setLocale(self::LOCALE[rand(0,count(self::LOCALE) -1)]);
            $manager->persist($preferences);

            $user->setPreferences($preferences);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
