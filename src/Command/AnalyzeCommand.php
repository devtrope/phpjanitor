<?php
namespace PHPJanitor\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AnalyzeCommand extends Command
{
    protected static $defaultName = 'analyze';
    protected static $defaultDescription = 'Analyze PHP code and provide a complete report about the code quality and issues.';

    public function configure(): void
    {
        $this->addArgument(
            'path',
            InputArgument::REQUIRED,
            'Path to the PHP directory to analyze'
        );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $path = $input->getArgument('path');
        $io->success("Analyzing PHP code in path: $path");
        return Command::SUCCESS;
    }
}
