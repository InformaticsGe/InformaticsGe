<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getEnv', [$this, 'getEnv']),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('preview', [$this, 'preview']),
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

    /**
     * Get blog preview text (Text before break line).
     *
     * @param string $content
     *
     * @return string
     */
    public function preview(string $content): string
    {
        $breakPoint = strpos($content, '<div style="page-break-after:always"><span style="display:none">&nbsp;</span></div>');

        if (false !== $breakPoint) {
            $content = substr($content, 0, $breakPoint);
        }

        return $content;
    }
}