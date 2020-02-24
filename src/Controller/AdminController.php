<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends EasyAdminController
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    protected function persistUserEntity(User $user)
    {
        $user->setPlainPassword($user->getPassword());
        $this->encodePassword($user);

        parent::persistEntity($user);
    }

    protected function updateUserEntity(User $user)
    {
        $encodedPassword = $this->encodePassword($user);
        $user->setPassword($encodedPassword);

        parent::updateEntity($user);
    }

    public function encodePassword(User $user)
    {
        if (!$user instanceof User || !$user->getPlainPassword()) {
            return;
        }
        $encoded = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        // now it's work if plainPassword string was set
        $user->setPassword(
            $encoded
        );
    }

}