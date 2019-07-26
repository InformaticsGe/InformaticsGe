<?php

namespace App\Controller;

use App\Service\CompilerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends AbstractController
{
    /**
     * Compile code on /compiler page.
     *
     * @param Request $request
     * @param CompilerService $compilerService
     *
     * @return JsonResponse
     */
    public function compilerCompileCode(Request $request, CompilerService $compilerService)
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

        $compilationData = $compilerService->runCompiler($language, $code, $inputData);

        return new JsonResponse($compilationData);
    }
}
