<?php

namespace App\Compiler;


use Symfony\Component\Filesystem\Filesystem;

class AbstractCompiler
{
    protected $filesystem;

    protected $code;
    protected $inputData;
    protected $timeLimit;

    protected $compiled = false;
    protected $directory;
    protected $executable;
    protected $compileCommand;
    protected $executeCommand;
    protected $codeFilename;
    protected $inputFilename = 'input.txt';
    protected $errorFilename = 'error.txt';
    protected $isError = false;
    protected $errorOutput;
    protected $executionOutput;
    protected $executionTime;
    protected $executionMemory;

    /**
     * AbstractCompiler constructor.
     *
     * @param $code
     * @param $inputData
     * @param $timeLimit
     */
    public function __construct($code, $inputData, $timeLimit)
    {
        $this->code = $code;
        $this->inputData = $inputData;
        $this->timeLimit = $timeLimit;

        // Set path to compilation directory.
        $this->directory = '/tmp/' . uniqid();

        $this->filesystem = new Filesystem();

        // Create compilation directory.
        $this->filesystem
            ->mkdir($this->directory);
    }

    /**
     * Compile given code to binary file.
     *
     * @return AbstractCompiler
     */
    public function compile(): self
    {
        // Put code and input data to files.
        file_put_contents($this->directory . '/' . $this->codeFilename, $this->code);
        file_put_contents($this->directory . '/' . $this->inputFilename, $this->inputData);

        // Compile code.
        exec('cd ' . $this->directory . ' && ' . $this->compileCommand);

        // Get error.
        $this->errorOutput = file_get_contents(
            $this->directory . '/' . $this->errorFilename
        );

        if ('' !== $this->errorOutput) {
            $this->isError = true;
        }

        $this->compiled = true;

        return $this;
    }

    /**
     * Execute binary file or run script.
     *
     * @return AbstractCompiler
     */
    public function execute(): self
    {
        if (!$this->compiled) {
            $this->executionOutput = '';
            $this->executionTime = 0.00;
            $this->executionMemory = 0.00;

            return $this;
        }

        $executionCommand = '' == $this->inputData ? $this->executeCommand :
            $this->executeCommand . ' < ' . $this->inputFilename;

        $executionStartTime = microtime(true);

        // Execute command.
        $this->executionOutput = shell_exec('cd ' . $this->directory . ' && ' . $executionCommand) ?: '';

        //Calculate executable time
        $executionEndTime = microtime(true);
        $this->executionTime = sprintf('%0.2f', $executionEndTime - $executionStartTime);

        //Get memory usage in megabytes
        $this->executionMemory = round(memory_get_usage() / 1048576, 2);

        return $this;
    }

    /**
     * Return true if there is compilation error.
     *
     * @return bool
     */
    public function isError()
    {
        return $this->isError;
    }

    /**
     * Get Error message.
     *
     * @return string
     */
    public function getError(): string
    {
        return $this->errorOutput;
    }

    /**
     * Get program output.
     *
     * @return string
     */
    public function getExecutionOutput(): string
    {
        return $this->executionOutput;
    }

    /**
     * Get execution time.
     *
     * @return float
     */
    public function getExecutionTime(): float
    {
        return $this->executionTime;
    }

    /**
     * Get execution memory.
     *
     * @return mixed
     */
    public function getExecutionMemory(): float
    {
        return $this->executionMemory;
    }

    /**
     * AbstractCompiler destructor.
     */
    public function __destruct()
    {
        // Remove compilation directory.
        $this->filesystem
            ->remove($this->directory);
    }
}