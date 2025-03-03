<?php

namespace App\EventListeners\Podcasts;

use App\Services\Podcasts\PodcastRouteVerifier;
use App\Services\Podcasts\PodcastsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Routing\RouterInterface;

class PodcastOwnerListener extends AbstractController
{
    public function __construct(private Security $security, private RouterInterface $routerInterface, private PodcastsService $podcastsService, private PodcastRouteVerifier $routeVerifier) {}

    public function onKernelController(ControllerEvent $controllerEvent)
    {
        $request = $controllerEvent->getRequest();
        $requestRoute = $request->attributes->get('_route');

        if (!$this->routeVerifier->isAuthorizedRoute($requestRoute)) {
            return;
        }

        $podcastIdentifier = $request->attributes->get('identifier');

        if (!$podcastIdentifier) {

            $response = new RedirectResponse($this->routerInterface->generate('app_account_podcasts'));

            //Cette fonction anonyme est utilisée afin d'empêcher l'exécution du controleur initial
            $controllerEvent->setController(fn() => $response);
            return;
        }

        $currentUser = $this->security->getUser();

        $isUserOwningPodcast = $this->podcastsService->isUserOwningPodcast($podcastIdentifier, $currentUser->getPodcasts());

        if (!$isUserOwningPodcast) {
            $this->addFlash('error', 'Impossible de modifier le podcast demandé, vous ne possédez pas les droits.');
            $response = new RedirectResponse($this->routerInterface->generate('app_account_podcasts'));
            $controllerEvent->setController(fn() => $response);
        }
    }
}
