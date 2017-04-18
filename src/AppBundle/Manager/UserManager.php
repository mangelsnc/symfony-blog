<?php

namespace AppBundle\Manager;

use AppBundle\Entity\User;
use AppBundle\Exception\User\PasswordNotMatchesException;
use AppBundle\Exception\User\PasswordNotValidException;
use AppBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class UserManager
{
    /**
     * @var  UserRepository
     */
    private $repository;

    /**
     * @var UserPasswordEncoder
     */
    private $passwordEncoder;

    public function __construct(UserRepository $repository, UserPasswordEncoder $passwordEncoder)
    {
        $this->repository = $repository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Changes some user password
     *
     * @param User $user
     * @param $password1
     * @param $password2
     * @throws PasswordNotMatchesException|PasswordNotValidException
     */
    public function changePassword(User $user, $password1, $password2)
    {
        if ($this->passwordEncoder->isPasswordValid($user, $password1)) {
            throw new PasswordNotMatchesException();
        }

        if (6 > strlen($password2)) {
            throw new PasswordNotValidException();
        }

        if ($password1 === $password2) {
            throw new PasswordNotValidException();
        }

        $user->setPassword($password2);

        $this->repository->save($user);
    }
}