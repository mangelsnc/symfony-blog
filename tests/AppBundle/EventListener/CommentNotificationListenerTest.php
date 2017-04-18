<?php

namespace Tests\AppBundle\EventListener;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use AppBundle\EventListener\CommentNotificationListener;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

class CommentNotificationListenerTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function itShouldSendAnEmailWhenACommentIsCreated()
    {
        $mailerMock = $this->getMockBuilder(\Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->setMethods(['send'])
            ->getMock()
        ;
        $mailerMock->expects($this->once())->method('send');

        $urlGeneratorMock = $this->getMockBuilder(UrlGeneratorInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['generate', 'setContext', 'getContext'])
            ->getMock()
        ;

        $translatorMock = $this->getMockBuilder(TranslatorInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['trans', 'transChoice', 'setLocale', 'getLocale'])
            ->getMock()
        ;

        $authorMock = $this->getMockBuilder(Author::class)
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

        $eventMock = $this->getMockBuilder(GenericEvent::class)
            ->disableOriginalConstructor()
            ->setMethods(['getSubject'])
            ->getMock()
        ;
        $eventMock->method('getSubject')->willReturn($commentMock);

        $sut = new CommentNotificationListener(
            $mailerMock,
            $urlGeneratorMock,
            $translatorMock,
            'user@mail.com'
        );

        $sut->onCommentCreated($eventMock);
    }
}
