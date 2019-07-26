<?php

namespace App\Compiler;


final class CPPCompiler extends AbstractCompiler
{
    /**
     * CPPCompiler constructor.
     *
     * @param string $code
     * @param string $inputData
     * @param $timeLimit
     */
    public function __construct(string $code, string $inputData, $timeLimit)
    {
        parent::__construct($code, $inputData, $timeLimit);

        $this->executable = 'main.out';
        $this->codeFilename = 'main.cpp';
        $this->executeCommand = 'timeout ' . $this->timeLimit . 's ./' . $this->executable;
        $this->compileCommand = 'g++ -o ' . $this->executable . ' ' .
            $this->codeFilename . ' 2> ' . $this->errorFilename;
    }
}
