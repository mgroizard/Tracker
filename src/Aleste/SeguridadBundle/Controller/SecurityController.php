<?php

namespace Aleste\SeguridadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SecurityController extends Controller
{
     public function loginAction(Request $request)
     {
        $session = $request->getSession();
         
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        //seteo el idioma por defecto de acuerdo al navegador
        $session->set('_locale', $request->getPreferredLanguage(array('es','en')));

        return $this->render('SeguridadBundle:Usuario:login.html.twig', array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
     }
    
     public function accessDeniedAction()
     {
        return $this->render('SeguridadBundle:Errors:error.403.html.twig',array());
     }    
}
