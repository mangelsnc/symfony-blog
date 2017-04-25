<?php

namespace Tests\AppBundle\EventListener;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use AppBundle\Entity\User;
use AppBundle\EventListener\CommentNotificationListener;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Mockery as Mockery;
use PHPUnit\Framework\TestCase;

class CommentNotificationListenerTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    public function itShouldSendAnEmailWhenACommentIsCreated()
    {

        /**
         * PHPUnit
         */
//        $mailerMock = $this->getMockBuilder(\Swift_Mailer::class)
//            ->disableOriginalConstructor()
//            ->setMethods(['send'])
//            ->getMock()
//        ;
//        $mailerMock->expects($this->once())->method('send');

        /**
         * Mockery
         */
        $mailerMock = Mockery::mock(\Swift_Mailer::class);
        $mailerMock->shouldReceive('send')->once();

        /**
         * PHPUnit
         */
//        $urlGeneratorMock = $this->getMockBuilder(UrlGeneratorInterface::class)
//            ->disableOriginalConstructor()
//            ->setMethods(['generate', 'setContext', 'getContext'])
//            ->getMock()
//        ;

        /**
         * Mockery
         */
        $urlGeneratorMock = Mockery::mock(UrlGeneratorInterface::class);
        $urlGeneratorMock->shouldReceive('generate', 'setContext', 'getContext');

        /**
         * PHPUnit
         */
//        $translatorMock = $this->getMockBuilder(TranslatorInterface::class)
//            ->disableOriginalConstructor()
//            ->setMethods(['trans', 'transChoice', 'setLocale', 'getLocale'])
//            ->getMock()
//        ;

        $translatorMock = Mockery::mock(TranslatorInterface::class);
        $translatorMock->shouldReceive('trans', 'transChoice', 'setLocale', 'getLocale');

        $authorMock = $this->getMockBuilder(User::class)
            ->setMethods(['getEmail'])
            ->getMock()
        ;

        $postMock = $this->getMockBuilder(Post::class)
            ->setMethods(['getAuthor'])
            ->getMock()
        ;
        $postMock->method('getAuthor')->willReturn($authorMock);

        $commentMock = $this->getMockBuilder(Comment::class)
            ->setMethods(['getPost'])
            ->getMock()
        ;
        $commentMock->method('getPost')->willReturn($postMock);

//        $eventMock = $this->getMockBuilder(GenericEvent::class)
//            ->disableOriginalConstructor()
//            ->setMethods(['getSubject'])
//            ->getMock()
//        ;
//        $eventMock->method('getSubject')->willReturn($commentMock);
        $eventMock = Mockery::mock(GenericEvent::class);
        $eventMock->shouldReceive('getSubject')->once()->andReturn($commentMock);

        $sut = new CommentNotificationListener(
            $mailerMock,
            $urlGeneratorMock,
            $translatorMock,
            'user@mail.com'
        );

        $sut->onCommentCreated($eventMock);
    }
}
