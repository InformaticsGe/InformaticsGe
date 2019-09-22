<?php

namespace App\Service;


use App\Compiler\AbstractCompiler;

class CompilerService
{

    /**
     * Create new compiler object for given language.
     *
     * @param string $language
     * @param string $code
     * @param int $timeLimit
     *
     * @return AbstractCompiler|array
     */
    public function getCompiler(string $language, string $code, int $timeLimit)
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
                $code,
                $timeLimit,
                $compilerClassesMapping[$language]['additionalData']
            );
        } else {
            $compilerClass = '\\App\\Compiler\\' . $compilerClassesMapping[$language];
            $compilerObj = new $compilerClass($code, $timeLimit);
        }

        return $compilerObj;
    }
}