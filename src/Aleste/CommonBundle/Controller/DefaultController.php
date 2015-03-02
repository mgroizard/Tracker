<?php

namespace Aleste\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    private function getDestination()
    {
        $resultado = $this->getRequest()->headers->get('referer');
        
        if((substr_count($resultado,'create'))||substr_count($resultado,'update'))
        {
            $resultado = $this->generateUrl('homepage');    
        }
        
        return $resultado;
    }
    
    public function idiomaAction($idioma)
    {
        $request = $this->getRequest();
      
        if($idioma == 'en')
        {
            $request->setLocale('en');
            $request->getSession()->set('_locale', 'en');
            return $this->redirect($this->getDestination());
        }

        if($idioma == 'es')
        {
            $request->setLocale('es');
            $request->getSession()->set('_locale', 'es');
            return $this->redirect($this->getDestination());

        }
        
        return $this->redirect($this->getDestination());
    }
    
    public function horaAction()
    {
        return new Response(date('d/m/Y [H:i]'));
    }
}
