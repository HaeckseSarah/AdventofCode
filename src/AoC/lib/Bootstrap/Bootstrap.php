<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC\lib\Bootstrap;

use RuntimeException;

class Bootstrap
{
    protected $basePath;

    public function __construct($basePath)
    {
        $this->basePath = $basePath;
    }

    public function createFilestructure(int $year, int $day)
    {
        if ($year > 99) {
            throw new RuntimeException('Invalid Year');
        }

        $year = sprintf("%'.02d", $year);
        $day = sprintf("%'.02d", $day);

        $testFile = $this->basePath.'/tests/Unit/AoC'.$year.'Day'.$day.'Test.php';
        $templateDir = $this->basePath.'/template';
        $codeDir = $this->basePath.'/src/AoC/AoC'.$year.'/Day'.$day;

        if (
            file_exists($testFile) || file_exists($codeDir)
        ) {
            echo 'File exists';
            exit;
        }

        mkdir($codeDir);
        copy($templateDir.'/input.txt', $codeDir.'/input.txt');
        copy($templateDir.'/README.md', $codeDir.'/README.md');
        copy($templateDir.'/Runner.php', $codeDir.'/Runner.php');
        $this->replace($codeDir.'/Runner.php', '###YEAR###', $year);
        $this->replace($codeDir.'/Runner.php', '###DAY###', $day);
        copy($templateDir.'/Test.php', $testFile);
        $this->replace($testFile, '###YEAR###', $year);
        $this->replace($testFile, '###DAY###', $day);
    }

    protected function replace($file, $search, $replace)
    {
        $str = file_get_contents($file);
        $str = str_replace($search, $replace, $str);
        file_put_contents($file, $str);
    }
}
