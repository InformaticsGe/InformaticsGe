<?php

namespace App\Controller;

use App\Entity\ProblemSubmission;
use App\Entity\ProblemSubmissionResult;
use App\Entity\ProblemTest;
use App\Repository\ProblemRepository;
use App\Repository\UserRepository;
use App\Service\CompilerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

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

        // Create new compiler object.
        $compiler = $compilerService->getCompiler($language, $code);

        // Compile and execute code.
        $compiler
            ->compile()
            ->execute($inputData);

        $return = [
            'success' => true,
            'isError' => $compiler->isError(),
            'error' => $compiler->getError(),
            'output' => $compiler->getExecutionOutput(),
            'time' => $compiler->getExecutionTime(),
            'memory' => $compiler->getExecutionMemory()
        ];

        return new JsonResponse($return);
    }

    /**
     * Submit problem solution.
     *
     * @param $id
     * @param Request $request
     * @param CompilerService $compilerService
     * @param ProblemRepository $problemRepository
     * @param UserRepository $userRepository
     * @param Security $security
     *
     * @return JsonResponse
     */
    public function submitProblemSolution(
        $id,
        Request $request,
        CompilerService $compilerService,
        ProblemRepository $problemRepository,
        UserRepository $userRepository,
        Security $security
    ) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $request = $request->request;
        $_token = $request->get('_token');

        if ($this->isCsrfTokenValid('submit-problem-solution', $_token)) {
            $problem = $problemRepository->find($id);

            if (null == $problem) {
                return new JsonResponse(['success' => false]);
            }

            $problemTests = $problem->getTests();

            $language = $request->get('language');
            $code = $request->get('code');

            // Create new compiler and compile code.
            $compiler = $compilerService->getCompiler($language, $code);
            $compiler->compile();

            $doctrine = $this->getDoctrine();
            $em = $doctrine->getManager();

            // Get current user.
            $username = $security->getUser()->getUsername();
            $user = $userRepository->findOneBy(['username' => $username]);

            $submission = new ProblemSubmission();
            $submission->setProblem($problem)
                ->setUser($user)
                ->setCode($code)
                ->setLanguage($language)
                ->setVerdict('CRTDN'); // Set status to created.

            // Save as created.
            $em->persist($submission);
            $em->flush();

            // Test all cases.
            $acceptedCount = 0;
            $isCE = false;
            $isTLE = false;
            $isMLE = false;
            foreach ($problemTests->getIterator() as $test) {
                /** @var ProblemTest $test */
                $compiler->execute($test->getInput());

                $time = $compiler->getExecutionTime();
                $memory = $compiler->getExecutionMemory();
                $output = $compiler->getExecutionOutput();

                $result = new ProblemSubmissionResult();
                $result->setTime($time)
                    ->setMemory($memory)
                    ->setOutput($output);

                $subVerdict = null;

                if ($compiler->isError()) {
                    $subVerdict = 'CE';
                    $result->setError($compiler->getError());
                    $isCE = true;
                }

                if (
                    null === $subVerdict
                    && $time > $problem->getTimeLimit()
                ) {
                    $subVerdict = 'TLE';
                    $isTLE = true;
                }

                if (
                    null === $subVerdict
                    && $memory > $problem->getMemoryLimit()
                ) {
                    $subVerdict = 'MLE';
                    $isMLE = true;
                }

                if (
                    null === $subVerdict
                    &&  0 === strcmp(trim($output), trim($test->getOutput()))
                ) {
                    $subVerdict = 'AC';
                    $acceptedCount++;
                } else if (null === $subVerdict) {
                    $subVerdict = 'WA';
                }

                $result->setVerdict($subVerdict);

                // Add result to submission.
                $submission->addResult($result);
            }

            $verdict = 'WA';
            if ($acceptedCount == $problemTests->count()) {
                $verdict = 'AC';
            } else if ($isCE) {
                $verdict = 'CE';
            } else if ($isTLE) {
                $verdict = 'TLE';
            } else if ($isMLE) {
                $verdict = 'MLE';
            }

            // Update with results.
            $submission->setVerdict($verdict);
            $em->persist($submission);
            $em->flush();

            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse(['success' => false]);
    }
}
