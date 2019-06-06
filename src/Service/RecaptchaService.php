<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use ReCaptcha\ReCaptcha;

class RecaptchaService
{
    /**
     * Check recaptcha response.
     *
     * @param Request $request
     *
     * @return array
     */
    public function checkRecaptcha(Request $request)
    {
        $recaptcha = new ReCaptcha(getenv('GOOGLE_RECAPTCHA_SECRET'));
        $response = $recaptcha->verify(
            $request->request->get('g-recaptcha-response'),
            $request->getClientIp()
        );

        return [
            'success' => $response->isSuccess(),
            'errors' => $response->getErrorCodes()
        ];
    }
}