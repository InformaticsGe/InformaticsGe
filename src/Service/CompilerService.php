<?php

namespace App\Service;


use App\Compiler\AbstractCompiler;

class CompilerService
{
    /**
     * Compile code for given language.
     *
     * @param string $language
     * @param string $code
     * @param string $inputData
     *
     * @return array
     */
    public function runCompiler(string $language, string $code, string $inputData): array
    {
        // Map compiler language to compiler class.
        $compilerClassesMapping = [
            'cpp' => 'CPPCompiler',
            'c_sharp' => 'CSharpCompiler',
            'python2' => [
                'class' => 'PythonCompiler',
                'additionalData' => [
                    'version' => 2
                ]
            ],
            'python3' => [
                'class' => 'PythonCompiler',
                'additionalData' => [
                    'version' => 3
                ]
            ],
            'java' => 'JavaCompiler',
            'php' => 'PHPCompiler',
            'node_js' => 'NodeJSCompiler',
            'free_pascal' => 'FreePascalCompiler',
        ];

        // Check if given language is valid.
        if (!isset($compilerClassesMapping[$language])) {
            return [
                'success' => false,
                'message' => 'invalid-compiler'
            ];
        }

        // Prepare compiler class name.

        $compilerClass = null;
        /** @var AbstractCompiler $compilerObj */
        $compilerObj = null;

        if (is_array($compilerClassesMapping[$language])) {
            $compilerClass = '\\App\\Compiler\\' . $compilerClassesMapping[$language]['class'];
            $compilerObj = new $compilerClass(
                $code, $inputData, 10,
                $compilerClassesMapping[$language]['additionalData']
            );
        } else {
            $compilerClass = '\\App\\Compiler\\' . $compilerClassesMapping[$language];
            $compilerObj = new $compilerClass($code, $inputData, 10);
        }

        // Compile and execute code.
        $compilerObj
            ->compile()
            ->execute();

        return [
            'success' => true,
            'isError' => $compilerObj->isError(),
            'error' => $compilerObj->getError(),
            'output' => $compilerObj->getExecutionOutput(),
            'time' => $compilerObj->getExecutionTime(),
            'memory' => $compilerObj->getExecutionMemory()
        ];
    }
}