<?php

namespace AppBundle\Manager;

use AppBundle\Entity\User;
use AppBundle\Exception\User\PasswordNotMatchesException;
use AppBundle\Exception\User\PasswordNotValidException;
use AppBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    const MIN_PASSWORD_LENGTH = 6;

    /**
     * @var  UserRepository
     */
    private $repository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserRepository $repository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->repository = $repository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Changes some user password
     *
     * @param User $user
     * @param $oldPassword
     * @param $newPassword
     * @return User
     * @throws PasswordNotMatchesException|PasswordNotValidException
     */
    public function changePassword(User $user, $oldPassword, $newPassword)
    {
        if (!$this->isOldPasswordValid($user, $oldPassword)) {
            throw new PasswordNotMatchesException();
        }

        if (!$this->isNewPasswordValid($oldPassword, $newPassword)) {
            throw new PasswordNotValidException();
        }

        $user->setPassword($newPassword);

        $this->repository->save($user);

        return $user;
    }

    /**
     * @param User $user
     * @param $oldPassword
     * @return boolean
     */
    private function isOldPasswordValid(User $user, $oldPassword)
    {
        return $this->passwordEncoder->isPasswordValid($user, $oldPassword);
    }

    /**
     * @param $oldPassword
     * @param $newPassword
     * @return boolean
     */
    private function isNewPasswordValid($oldPassword, $newPassword)
    {
        if (self::MIN_PASSWORD_LENGTH > strlen($newPassword)) {
            return false;
        }

        if ($oldPassword === $newPassword) {
            return false;
        }

        return true;
    }
}