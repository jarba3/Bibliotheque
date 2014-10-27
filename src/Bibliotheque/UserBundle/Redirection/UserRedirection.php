<?php

namespace Bibliotheque\UserBundle\Redirection;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserRedirection implements AuthenticationSuccessHandlerInterface
{
    private $router;

    public function __construct(RouterInterface $router){
        $this->router = $router;
    }
    
    public function onAuthenticationSuccess(Request $request, TokenInterface $token){
        $roles = $token->getRoles();
        
        $rolesTab = array_map(function($role){
                                        return $role->getRole();
                                    }, $roles);
        
        
        if (in_array("ROLE_BIBLIOTHECAIRE", $rolesTab)){

            $redirection = new RedirectResponse($this->router->generate('bibliotheque_bibliothecaire'));
        }

        if (in_array("ROLE_ADMIN", $rolesTab)){

            $redirection = new RedirectResponse($this->router->generate('admin'));
        }

        if ((in_array("ROLE_ETUDIANT",  $rolesTab)) || (in_array("ROLE_PROFESSEUR",  $rolesTab))){

            $redirection = new RedirectResponse($this->router->generate('bibliotheque_profil'));
        }

        return $redirection;
    }
}