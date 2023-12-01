<?php

use HaeckseSarah\AoC23\lib\Bootstrap\Bootstrap;
use HaeckseSarah\AoC23\lib\Collection\Collection;
use HaeckseSarah\AoC23\lib\Stream\StreamFactory;

require_once 'vendor/autoload.php';

$mode = strtolower($argv[1] ?? '');
$day = (int) $argv[2] ?? 0;
$challenge = strtolower($argv[3] ?? '');

$isRun = ('run' === $mode);

if ($day <= 0 || $day > 24) {
    echo 'invalid day';
    exit;
}

if ('bootstrap' === $mode) {
    $bootstrap = new Bootstrap(dirname(__FILE__));
    $bootstrap->createFilestructure($day);
    exit;
}

$day = sprintf("%'.02d", $day);

if (!$isRun) {
    $cmd = "php vendor/bin/codecept run Unit Day{$day}Test";
    if ('' != $challenge) {
        $cmd .= ':testChallenge'.strtoupper($challenge);
    }
    echo shell_exec($cmd);
    exit;
}

$input = Collection::createFromStream((new StreamFactory())->createStreamFromFile('src/AoC23/Day'.$day.'/input.txt'));

$runnerClass = 'HaeckseSarah\AoC23\Day'.$day.'\Runner';
$runner = new $runnerClass($input);
if ('b' !== $challenge) {
    echo "Exec ChallengeA\n";
    echo 'result: '.$runner->challengeA()."\n";
}

if ('a' !== $challenge) {
    echo "Exec ChallengeB\n";
    echo 'result: '.$runner->challengeB()."\n";
}
