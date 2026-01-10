<?php
namespace PHPJanitor\Analyzers;

class MagicNumberAnalyzer
{
    private array $magicNumbers = [];

    public function scan(string $directory): array
    {
        $files = $this->getPHPFiles($directory);
        foreach ($files as $file) {
            $cleanedFile = $this->cleanFile(file_get_contents($file));
            foreach ($cleanedFile as $line) {
                if (preg_match_all('!\d+(?:\.\d+)?!', $line['content'], $matches)) {
                    $this->magicNumbers[] = [
                        'number' => $matches[0],
                        'line' => $line['number']
                    ];
                }
            }
        }
        return $this->magicNumbers;
    }

    public function getPHPFiles(string $directory): array
    {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory));
        $phpFiles = [];
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $phpFiles[] = $file->getPathname();
            }
        }
        return $phpFiles;
    }

    public function cleanFile(string $fileContent): array
    {
        $lines = explode(PHP_EOL, $fileContent);
        $clean = [];
        $i = 0;

        foreach ($lines as $line) {
            $trimmedLine = trim($line);
            $clean[] = [
                'content' => $trimmedLine,
                'number' => $i + 1
            ];

            $i++;
        }

        return $clean;
    }
}
