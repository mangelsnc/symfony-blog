<?php

namespace Tests\AppBundle\Manager;

use AppBundle\Entity\User;
use AppBundle\Manager\UserManager;
use AppBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use PHPUnit\Framework\TestCase;

class UserManagerTest extends TestCase
{
    const CURRENT_PASSWORD = 'password';
    const NEW_PASSWORD = 'password1';

    /** @test */
    public function itShouldChangeUserPassword()
    {
        $repo = $this->getRepositoryMock();
        $repo->expects($this->once())->method('save');

        $encoder = $this->getPasswordEncoderMock();
        $encoder->expects($this->once())->method('isPasswordValid')->willReturn(true);

        $user = new User();
        $user->setPassword('password');

        $sut = new UserManager($repo, $encoder);

        $user = $sut->changePassword($user, self::CURRENT_PASSWORD, self::NEW_PASSWORD);

        $this->assertEquals(self::NEW_PASSWORD, $user->getPassword());
    }

    private function getRepositoryMock()
    {
        $repo = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        return $repo;
    }

    private function getPasswordEncoderMock()
    {
        $encoder = $this->getMockBuilder(UserPasswordEncoderInterface::class)
            ->setMethods(['isPasswordValid', 'encodePassword'])
            ->getMock()
        ;

        return $encoder;
    }
}