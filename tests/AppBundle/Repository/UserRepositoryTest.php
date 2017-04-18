<?php

namespace Tests\AppBundle\Repository;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testFindByUsername()
    {
        $users = $this->em
            ->getRepository('AppBundle:User')
            ->findByUsername('jane_admin')
        ;

        $this->assertCount(1, $users);
    }

    public function testSave()
    {
        $user = new User();
        $user->setUsername('test');
        $user->setFullName('Test Ículo');
        $user->setEmail('test@mail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('wololo');

        $this->em->getRepository('AppBundle:User')->save($user);

        $users = $this->em
            ->getRepository('AppBundle:User')
            ->findByUsername('test')
        ;

        $this->assertCount(1, $users);

        $this->em->remove($user);
        $this->em->flush();
    }

    /**
    * {@inheritDoc}
    */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}