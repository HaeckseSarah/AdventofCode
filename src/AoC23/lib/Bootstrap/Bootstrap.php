<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC23\lib\Bootstrap;

class Bootstrap
{
    protected $basePath;

    public function __construct($basePath)
    {
        $this->basePath = $basePath;
    }

    public function createFilestructure(int $day)
    {
        $day = sprintf("%'.02d", $day);

        $testFile = $this->basePath.'/tests/Unit/Day'.$day.'Test.php';
        $templateDir = $this->basePath.'/src/AoC23/meta/Bootstrap';
        $codeDir = $this->basePath.'/src/AoC23/Day'.$day;

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
        $this->replace($codeDir.'/Runner.php', '###DAY###', $day);
        copy($templateDir.'/Test.php', $testFile);
        $this->replace($testFile, '###DAY###', $day);
    }

    protected function replace($file, $search, $replace)
    {
        $str = file_get_contents($file);
        $str = str_replace($search, $replace, $str);
        file_put_contents($file, $str);
    }
}
