<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getEnv', [$this, 'getEnv']),
        ];
    }

    /**
     * Read parameter from environment file.
     *
     * @param string $key Parameter key
     *
     * @return string
     */
    public function getEnv(string $key)
    {
        return getenv($key);
    }
}