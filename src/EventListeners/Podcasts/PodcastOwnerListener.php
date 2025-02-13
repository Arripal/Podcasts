<?php

namespace App\EventListeners\Podcasts;

use App\Services\Podcasts\PodcastRouteVerifier;
use App\Services\Podcasts\PodcastsService;
use App\Services\Router\RouterService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Routing\RouterInterface;

class PodcastOwnerListener
{
    public function __construct(private Security $security, private RouterInterface $routerInterface, private PodcastsService $podcastsService, private PodcastRouteVerifier $routeVerifier, private RouterService $routerService) {}

    public function onKernelController(ControllerEvent $controllerEvent)
    {
        $request = $controllerEvent->getRequest();
        $requestRoute = $request->attributes->get('_route');

        if (!$this->routeVerifier->isAuthorizedRoute($requestRoute)) {
            return;
        }

        $podcastIdentifier = $request->attributes->get('identifier');

        if (!$podcastIdentifier) {

            $response = $this->routerService->generateURL('app_account_podcasts');

            //Cette fonction anonyme est utilisée afin d'empêcher l'exécution du controleur initial
            $controllerEvent->setController(fn() => $response);
            return;
        }

        $currentUser = $this->security->getUser();

        $isUserOwningPodcast = $this->podcastsService->isUserOwningPodcast($podcastIdentifier, $currentUser->getPodcasts());

        if (!$isUserOwningPodcast) {
            $response = $this->routerService->generateURL('app_account_podcasts_details', ['identifier' => $podcastIdentifier]);
            $controllerEvent->setController(fn() => $response);
        }
    }
}
