<?php

namespace App\Controller;

use App\Compiler\AbstractCompiler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends AbstractController
{
    /**
     * Compile code on /compiler page.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function compilerCompileCode(Request $request)
    {
        // Get data from request.

        $request = $request->request;

        $_token = $request->get('_token');
        $language = $request->get('language');
        $code = $request->get('code');
        $inputData = $request->get('inputData');

        // Validate Csrf token.
        if (!$this->isCsrfTokenValid('compiler-compile-code', $_token)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'invalid-token'
            ]);
        }

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
            return new JsonResponse([
                'success' => false,
                'message' => 'invalid-compiler'
            ]);
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

        return new JsonResponse([
            'success' => true,
            'isError' => $compilerObj->isError(),
            'error' => $compilerObj->getError(),
            'output' => $compilerObj->getExecutionOutput(),
            'time' => $compilerObj->getExecutionTime(),
            'memory' => $compilerObj->getExecutionMemory()
        ]);
    }
}
