<?php

include __DIR__ . '/src/Execution.php';
include __DIR__ . '/src/Git.php';

$gitdir = (new Execution('Getting git dir', 'git rev-parse --show-toplevel'))->exec() . '/';

$e = new Execution('Getting php staged file(s)', 'git diff --cached --name-only --diff-filter=ACMR HEAD | grep \\\\.php');
$output = $e->exec(false);
$staggedFiles = array_filter(explode(PHP_EOL, $output));

if (!count($staggedFiles)) {
    exit(0);
}

foreach ($staggedFiles as $file) {
    (new Execution('Checking PHP file ' . $file . ' ', 'php -l -d display_errors=0 ' . $gitdir . $file))->exec();
}

$argFiles = implode(' ', $staggedFiles);

$e = new Execution('Running Code Sniffer', './vendor/bin/phpcs --standard=ruleset.xml --encoding=utf-8 --colors -n -s -p ' . $argFiles);
$e->exec(false);
if ($e->lastExit > 0 && strpos($e->lastOutput, 'PHPCBF CAN FIX') > 0) {
    $cbf = (new Execution('Fixable error found, fixing it for you', './vendor/bin/phpcbf --standard=ruleset.xml --encoding=utf-8 -p ' . $argFiles))->exec(false);
    (new Execution('Adding changes to git', 'git add ' . $argFiles))->exec();

    $e->title = 'Running Code Sniffer again';
    $e->exec();
}
if ($e->lastExit > 0) {
    $e->printDebug();
    exit($e->lastExit);
}

$e = new Execution('Running PHP Stan...', './vendor/bin/phpstan --no-progress --level=max --no-interaction --memory-limit=-1 --error-format=json analyse ' . $argFiles);
$phpstan = json_decode($e->exec(false), true);
$exit = 0;
foreach ($phpstan['files'] as $file => $data) {
    if ($phpstan['errors'] === 0) {
        continue;
    }

    $diff = (new Execution('Getting changes in ' . $file, 'git diff --cached --diff-filter=ACMR HEAD ' . $file))->exec();
    $lineChanged = Git::diffLineChanged($diff, 0);
    foreach ($data['messages'] as $message) {
        if (!in_array($message['line'], $lineChanged)) {
            continue;
        }

        echo $message['line'] . ': ' . $message['message'] . PHP_EOL;
        $exit = 1;
    }
}
exit($exit);

