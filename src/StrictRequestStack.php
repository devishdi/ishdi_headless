<?php

declare(strict_types=1);

namespace Drupal\ishdi_headless;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class StrictRequestStack.
 */
readonly class StrictRequestStack
{

    public function __construct(private RequestStack $requestStack)
    {
    }

    /**
     *
     */
    public function getCurrentRequest(): Request
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            throw new \RuntimeException('Invalid state, no current request in stack');
        }

        return $request;
    }

}
