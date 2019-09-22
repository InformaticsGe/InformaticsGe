<?php

namespace App\Compiler;


final class PHPCompiler extends AbstractCompiler
{
    /**
     * PHPCompiler constructor.
     *
     * @param string $code
     * @param $timeLimit
     */
    public function __construct(string $code, $timeLimit)
    {
        $timeLimit *= 2;

        parent::__construct($code, $timeLimit);

        $this->codeFilename = 'main.php';
        $this->executeCommand = 'timeout ' . $this->timeLimit . 's php ' .
            $this->codeFilename . ' 2> ' . $this->errorFilename;
    }

    /**
     * @inheritDoc
     */
    public function compile(): AbstractCompiler
    {
        // Put code and input data to files.
        file_put_contents($this->directory . '/' . $this->codeFilename, $this->code);
        file_put_contents($this->directory . '/' . $this->inputFilename, $this->inputData);

        $this->compiled = true;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function execute($inputData): AbstractCompiler
    {
        $execute = parent::execute($inputData);

        // Get error.
        $this->errorOutput = file_get_contents(
            $this->directory . '/' . $this->errorFilename
        );

        if ('' !== $this->errorOutput) {
            $this->isError = true;
        }

        return $execute;
    }
}
