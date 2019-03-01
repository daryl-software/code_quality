<?php


class Execution
{
    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    private $cmd;

    /**
     * @var string
     */
    public $lastOutput;

    /**
     * @var int
     */
    public $lastExit;

    public function __construct(string $title, string $cmd)
    {
        $this->title = $title;
        $this->cmd = $cmd;
    }

    public function exec(bool $shouldExit = true): string
    {
        $this->lastExit = 0;
        $output = [];

        echo PHP_EOL . $this->title . '...';
        exec($this->cmd, $output, $this->lastExit);

        $this->lastOutput = trim(implode(PHP_EOL, $output));
        if ($this->lastExit > 0 && $shouldExit) {
            $this->printDebug();
            exit($this->lastExit);
        }
        echo 'OK' . PHP_EOL;
        return $this->lastOutput;
    }

    public function printDebug(): void
    {
        echo '(' . $this->lastExit . ') '. $this->cmd. PHP_EOL;
        echo $this->lastOutput . PHP_EOL;
    }
}
