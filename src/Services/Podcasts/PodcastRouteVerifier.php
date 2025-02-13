<?php

namespace App\Services\Podcasts;



class PodcastRouteVerifier
{
    private const AUTHORIZED_ROUTES = [
        'app_account_podcasts_delete',
        'app_account_podcasts_update'
    ];

    public function isAuthorizedRoute($route)
    {
        return in_array($route, self::AUTHORIZED_ROUTES, true);
    }
}
