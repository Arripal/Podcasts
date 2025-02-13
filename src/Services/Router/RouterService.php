<?php


namespace App\Services\Router;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class RouterService
{

    public function __construct(private RouterInterface $routerInterface) {}

    public function generateURL($routeName, array $parameters = [], int $statusCode = 302)
    {
        return new RedirectResponse($this->routerInterface->generate($routeName, $parameters), $statusCode);
    }
}
