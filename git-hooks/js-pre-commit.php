<?php

include __DIR__ . '/../src/CodeQuality/Execution.php';
include __DIR__ . '/../src/CodeQuality/Git.php';

use CodeQuality\Execution;
use CodeQuality\Git;

$staggedFiles = Git::getStaggedFiles('js');
if (!count($staggedFiles)) {
    exit(0);
}
$argFiles = implode(' ', $staggedFiles);

$exec = new Execution('Checking '.count($staggedFiles).' JS files', './node_modules/.bin/eslint --format=json ' . $argFiles);
$check = function (Execution $execution) {
    $output = $execution->exec(false);
    $json = json_decode($output, true);
    $total = [
        'errorCount' => 0,
        'warningCount' => 0,
        'fixableErrorCount' => 0,
        'fixableWarningCount' => 0,
    ];

    foreach ($json as $item) {
        $total['errorCount'] += $item['errorCount'];
        $total['warningCount'] += $item['warningCount'];
        $total['fixableErrorCount'] += $item['fixableErrorCount'];
        $total['fixableWarningCount'] += $item['fixableWarningCount'];
    }
    return $total;
};
$total = $check($exec);
if ($total['fixableErrorCount'] > 0 || $total['fixableWarningCount'] > 0) {
    (new Execution('Fixing '.count($staggedFiles).' JS files', './node_modules/.bin/eslint --fix ' . $argFiles))->exec();
} else if ($total['errorCount'] > 0) {
    $exec->cmd = str_replace('--format=json', '', $exec->cmd);
    $exec->exec();
}