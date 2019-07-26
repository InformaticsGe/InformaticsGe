<?php

namespace App\Compiler;


class FreePascalCompiler extends AbstractCompiler
{
    /**
     * FreePascalCompiler constructor.
     *
     * @param string $code
     * @param string $inputData
     * @param $timeLimit
     */
    public function __construct(string $code, string $inputData, $timeLimit)
    {
        parent::__construct($code, $inputData, $timeLimit);

        $this->executable = 'main';
        $this->codeFilename = 'main.pas';
        $this->executeCommand = 'timeout ' . $this->timeLimit . 's ./' . $this->executable;
        $this->compileCommand = 'fpc ' . $this->codeFilename . ' > ' . $this->errorFilename;
    }

    public function compile(): AbstractCompiler
    {
        $compile = parent::compile();

        $fileExists = $this->filesystem
            ->exists($this->directory . '/' . $this->executable);

        if (!$fileExists) {
            $this->compiled = false;
            $this->isError = true;
        } else {
            $this->compiled = true;
            $this->isError = false;
        }

        return $compile;
    }
}
