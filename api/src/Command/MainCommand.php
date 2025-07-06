<?php

namespace App\Command;

use App\Exception\RuntimeException;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:main',
    description: 'Welcome command',
    aliases: ['app:welcome'],
    hidden: false,
)]
class MainCommand //extends Command
{
    public function __invoke(
        #[Argument(name: 'The username of the user.')] ?string $username,
        OutputInterface $output,
    ): int {
        try {
            if (null !== $username) {
                $output->writeln("Welcome, {$username}!");
            } else {
                $output->writeln([
                    'Welcome to the application.',
                    '============',
                    '',
                ]);
            }
        } catch (RuntimeException $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}