<?php

namespace App\Compiler;


final class CPPCompiler extends AbstractCompiler
{
    /**
     * CPPCompiler constructor.
     *
     * @param string $code
     * @param $timeLimit
     */
    public function __construct(string $code, $timeLimit)
    {
        parent::__construct($code, $timeLimit);

        $this->executable = 'main.out';
        $this->codeFilename = 'main.cpp';
        $this->executeCommand = 'timeout ' . $this->timeLimit . 's ./' . $this->executable;
        $this->compileCommand = 'g++ -o ' . $this->executable . ' ' .
            $this->codeFilename . ' 2> ' . $this->errorFilename;
    }
}
