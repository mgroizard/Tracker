<?php

namespace Aleste\TrackerBundle\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class AjaxExceptionListener
{
        /**
     * Handles security related exceptions.
     *
     * @param GetResponseForExceptionEvent $event An GetResponseForExceptionEvent instance
     */
    public function onCoreException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $request = $event->getRequest();

        if ($request->isXmlHttpRequest()) {
            if ($exception instanceof AuthenticationException || $exception instanceof AccessDeniedException) {
                $session = $request->getSession();
                $session->getFlashBag()->add(
                    'warning',
                    'El tiempo de su sesión se ha agotado. Por favor, vuelva a ingresar al sistema.'
                );
                $event->setResponse(new Response('', Response::HTTP_REQUEST_TIMEOUT));
            }
        }
    }
}
