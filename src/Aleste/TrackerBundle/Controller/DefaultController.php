<?php

namespace Aleste\TrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Aleste\TrackerBundle\Entity\DocumentoHash;


class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return $this->render('TrackerBundle::layout.html.twig');
    }

    /**
     * @Route("/acuse/{hash}")
     * @Template()
     */
    public function acuseAction($hash)
    {
        $em            = $this->getDoctrine()->getManager();
        $documentoHash = $em->getRepository('TrackerBundle:DocumentoHash')->findOneByHash($hash);

        if ($documentoHash){
            $documento = $documentoHash->getMovimiento()->getDocumento();

            if ($documento->isSinAcuse() && $documentoHash->getFecFinVigencia() == null){
                $documentoManager = $this->get('aleste.documento.manager');
                $response = $documentoManager->acusar($documento->getId(), $documentoHash->getUsuario());
                $response['existeHash'] = true;   
            } else {
                $response = array('success' => false, 'exception' => null, 'existeHash' => true);
            }
            return array('response' => $response, 'usuario' => $documentoHash->getUsuario(), 'documento' => $documento);
        } 
        
        return array('response' => array('success' => false, 'exception' => null, 'existeHash' => false));
    }    

}
