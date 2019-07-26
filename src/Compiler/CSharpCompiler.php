<?php

namespace App\Compiler;


final class CSharpCompiler extends AbstractCompiler
{
    /**
     * CSharpCompiler constructor.
     *
     * @param string $code
     * @param string $inputData
     * @param $timeLimit
     */
    public function __construct(string $code, string $inputData, $timeLimit)
    {
        $timeLimit *= 2;

        parent::__construct($code, $inputData, $timeLimit);

        $this->executable = 'main.exe';
        $this->codeFilename = 'main.cs';
        $this->executeCommand = 'timeout ' . $this->timeLimit . 's mono ' . $this->executable;
        $this->compileCommand = 'mcs  -out:' . $this->executable . ' ' .
            $this->codeFilename . ' 2> ' . $this->errorFilename;
    }
}
