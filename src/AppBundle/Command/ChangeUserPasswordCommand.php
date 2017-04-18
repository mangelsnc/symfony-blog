<?php

namespace AppBundle\Command;

use AppBundle\Entity\User;
use AppBundle\Manager\UserManager;
use AppBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ChangeUserPasswordCommand extends ContainerAwareCommand
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:change-user-password')
            ->setDescription('Changes user password')
            ->addArgument('username', InputArgument::REQUIRED, 'The username of an existing user')
            ->addArgument('oldPassword', InputArgument::REQUIRED, 'The old password')
            ->addArgument('newPassword', InputArgument::REQUIRED, 'The users new password')
           ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->userManager = $this->getContainer()->get('app.user_manager');
        $this->userRepository = $this->getContainer()->get('app.user_repository');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $oldPassword = $input->getArgument('oldPassword');
        $newPassword = $input->getArgument('newPassword');

        /** @var User $user */
        $user = $this->userRepository->findOneBy(['username' => $username]);

        if (!$user) {
            $output->writeln('<error>User not found!</error>');

            exit;
        }

        try {
            $this->userManager->changePassword($user, $oldPassword, $newPassword);
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');

            exit;
        }


        $output->writeln('');
        $output->writeln(sprintf('[OK] User "%s" was successfully updated his password.', $user->getUsername()));
    }
}
