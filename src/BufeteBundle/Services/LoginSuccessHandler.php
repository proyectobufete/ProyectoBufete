<?php

namespace BufeteBundle\Services;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    protected $router;
    protected $security;

    public function __construct(Router $router, SecurityContext $security)
    {
        $this->router   = $router;
        $this->security = $security;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {

        $urls=  'bufete_homepage';
        if ($this->security->isGranted('ROLE_ADMIN')) {
              $urls = 'casos_indexlaborales';
        }
        elseif ($this->security->isGranted('ROLE_SECRETARIO')) {
            $urls = 'casos_indexciviles';
        }
        $response = new RedirectResponse($this->router->generate($urls));

        return $response;
    }
}
