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
    const INVALID_PASSWORD = 'pass';
    const NEW_PASSWORD = 'password1';

    /** @test */
    public function itShouldChangeUserPassword()
    {
        $repo = $this->getRepositoryMock();
        $repo->expects($this->once())->method('save');

        $encoder = $this->getPasswordEncoderMock();
        $encoder->expects($this->once())->method('isPasswordValid')->willReturn(true);

        $user = new User();
        $user->setPassword(self::CURRENT_PASSWORD);

        $sut = new UserManager($repo, $encoder);

        $user = $sut->changePassword($user, self::CURRENT_PASSWORD, self::NEW_PASSWORD);

        $this->assertEquals(self::NEW_PASSWORD, $user->getPassword());
    }

    /**
     * @test
     * @expectedException AppBundle\Exception\User\PasswordNotMatchesException
     */
    public function itShouldThrowExceptionIfCurrentPasswordNotMatches()
    {
        $repo = $this->getRepositoryMock();
        $repo->expects($this->never())->method('save');

        $encoder = $this->getPasswordEncoderMock();
        $encoder->method('isPasswordValid')->willReturn(false);

        $user = new User();
        $user->setPassword(self::CURRENT_PASSWORD);

        $sut = new UserManager($repo, $encoder);

        $sut->changePassword($user, self::CURRENT_PASSWORD, self::NEW_PASSWORD);

        $this->assertNotEquals(self::NEW_PASSWORD, $user->getPassword());
    }

    /**
     * @test
     * @expectedException AppBundle\Exception\User\PasswordNotValidException
     */
    public function itShouldThrowExceptionIfNewPasswordIsNotValid()
    {
        $repo = $this->getRepositoryMock();
        $repo->expects($this->never())->method('save');

        $encoder = $this->getPasswordEncoderMock();
        $encoder->method('isPasswordValid')->willReturn(true);

        $user = new User();
        $user->setPassword(self::CURRENT_PASSWORD);

        $sut = new UserManager($repo, $encoder);

        $sut->changePassword($user, self::CURRENT_PASSWORD, self::INVALID_PASSWORD);

        $this->assertNotEquals(self::NEW_PASSWORD, $user->getPassword());
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