<?php

namespace App\Compiler;


final class JavaCompiler extends AbstractCompiler
{
    /**
     * JavaCompiler constructor.
     *
     * @param string $code
     * @param $timeLimit
     */
    public function __construct(string $code, $timeLimit)
    {
        parent::__construct($code, $timeLimit);

        $this->executable = 'Main';
        $this->codeFilename = 'Main.java';
        $this->executeCommand = 'timeout ' . $this->timeLimit . 's java ' . $this->executable . ' 2>> ' .
            $this->errorFilename;
        $this->compileCommand = 'javac ' . $this->codeFilename . ' 2>> ' . $this->errorFilename;
    }
}
