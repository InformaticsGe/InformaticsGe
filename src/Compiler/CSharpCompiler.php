<?php

namespace App\Compiler;


final class CSharpCompiler extends AbstractCompiler
{
    /**
     * CSharpCompiler constructor.
     *
     * @param string $code
     * @param $timeLimit
     */
    public function __construct(string $code, $timeLimit)
    {
        $timeLimit *= 2;

        parent::__construct($code, $timeLimit);

        $this->executable = 'main.exe';
        $this->codeFilename = 'main.cs';
        $this->executeCommand = 'timeout ' . $this->timeLimit . 's mono ' . $this->executable;
        $this->compileCommand = 'mcs  -out:' . $this->executable . ' ' .
            $this->codeFilename . ' 2> ' . $this->errorFilename;
    }
}
