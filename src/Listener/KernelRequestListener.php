<?php

namespace App\Listener;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Core\Security;

/**
 * Class KernelRequestListener
 *
 * @package AppBundle\Listener
 */
class KernelRequestListener
{
    const DEBUG_ROUTE_NAMES = [
        '_twig_error_test',
        '_wdt',
        '_profiler_home',
        '_profiler_search',
        '_profiler_search_bar',
        '_profiler_search_results',
        '_profiler',
        '_profiler_router',
        '_profiler_exception',
        '_profiler_exception_css',
        '_profiler_open_file',
        'security_login',
        'security_logout',
        'security_forgotten_password',
    ];

    /** @var Security $security */
    private $security;

    /** @var Router $router */
    private $router;

    /**
     * KernelRequestListener constructor.
     *
     * @param Security $security
     * @param Router   $router
     */
    public function __construct(Security $security, Router $router) {
        
        $this->security = $security;
        $this->router = $router;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $route = $request->get('_route');
        if ($event->isMasterRequest()
            && !in_array($route, self::DEBUG_ROUTE_NAMES)
            && $this->security->isGranted('IS_AUTHENTICATED_REMEMBERED')
           // && $this->security->isGranted(User::ROLE_SUPER_ADMIN)

        ) {
            /** @var User $user */
            $user = $this->security->getUser();
            if (!$user->isEnabled()) {
                $event->setResponse(new RedirectResponse($this->router->generate('security_logout')));
            }
        }

        return;
    }
}