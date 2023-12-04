<?php

use HaeckseSarah\AoC\lib\Bootstrap\Bootstrap;
use HaeckseSarah\AoC\lib\Collection\Collection;
use HaeckseSarah\AoC\lib\Stream\StreamFactory;

require_once 'vendor/autoload.php';


$mode = strtolower($argv[1] ?? '');

if (count($argv) == 1 || !in_array($mode, ['run', 'test', 'bootstrap'])) {
    echo 'php run.php [run|test|bootstrap] [year] [day] [A|B]';
    exit;
}

$year = ($argv[2] ?? '.');
$day = (int) ($argv[3] ?? 0);

$challenge = strtolower($argv[4] ?? '');

if ($year == '.') {
    $year = date('y');
}
$year = (int)substr($year, -2);

if ($year <= 0) {
    echo "invalid year\n use '.' for current year";
    exit;
}

if ($day <= 0 || $day > 25) {
    echo 'invalid day';
    exit;
}

if ($mode === 'bootstrap') {
    $bootstrap = new Bootstrap(dirname(__FILE__));
    $bootstrap->createFilestructure($year, $day);
    exit;
}

$isRun = ($mode === 'run');

$year = sprintf("%'.02d", $year);
$day = sprintf("%'.02d", $day);

if ($mode !== 'run') {
    $cmd = "php vendor/bin/codecept run Unit AoC{$year}Day{$day}Test";
    if ($challenge != '') {
        $cmd .= ':testChallenge'.strtoupper($challenge);
    }
    echo shell_exec($cmd);
    exit;
}

$runnerClass = 'HaeckseSarah\AoC\AoC'.$year.'\Day'.$day.'\Runner';
$runner = new $runnerClass((new StreamFactory())->createStreamFromFile('src/AoC/AoC'.$year.'/Day'.$day.'/input.txt'));

if ($challenge !== 'b') {
    echo "Exec ChallengeA\n";
    echo 'result: '.$runner->challengeA()."\n";
}

if ($challenge !== 'a') {
    echo "Exec ChallengeB\n";
    echo 'result: '.$runner->challengeB()."\n";
}
