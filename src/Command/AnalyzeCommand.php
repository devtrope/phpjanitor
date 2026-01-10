<?php
namespace PHPJanitor\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use PHPJanitor\Analyzers\MagicNumberAnalyzer;

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

        $analyzer = new MagicNumberAnalyzer();
        $detected = $analyzer->scan($path);

        if (empty($detected)) {
            $io->success("No magic numbers detected");
            return Command::SUCCESS;
        }
        
        foreach ($detected as $detection) {
            $line = $detection['line'];
            foreach ($detection['number'] as $number) {
                $io->text("Magic number detected: number $number at line $line");
            }
        }

        $numberDetections = sizeof($detected);
        $io->error("Found $numberDetections magic numbers in your code");

        return Command::SUCCESS;
    }
}
