<?php

namespace App\Compiler;


final class PythonCompiler extends AbstractCompiler
{
    /**
     * PythonCompiler constructor.
     *
     * @param string $code
     * @param string $inputData
     * @param $timeLimit
     * @param $additionalData
     */
    public function __construct(
        string $code,
        string $inputData,
        $timeLimit,
        $additionalData
    ) {
        $timeLimit *= 2;

        parent::__construct($code, $inputData, $timeLimit);

        $pythonVersion = $additionalData['version'];

        $this->codeFilename = 'main.py';
        $this->executeCommand = 'timeout ' . $this->timeLimit . 's python' . $pythonVersion . ' ' .
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
    public function execute(): AbstractCompiler
    {
        $execute = parent::execute();

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
