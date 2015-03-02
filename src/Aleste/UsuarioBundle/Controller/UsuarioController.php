<?php

namespace Aleste\UsuarioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Aleste\UsuarioBundle\Entity\Usuario;
use Aleste\GestionBundle\Entity\Persona;
use Aleste\UsuarioBundle\Form\Type\UsuarioType;
use Aleste\UsuarioBundle\Form\Type\UsuarioFilterType;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Usuario controller.
 *
 * @Route("/gestion/usuario")
 */
class UsuarioController extends Controller
{
    /**
     * Lists all Usuario entities.
     *
     * @Route("/", name="gestion_usuario")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new UsuarioFilterType());
        if (!is_null($response = $this->saveFilter($form, 'usuario', 'gestion_usuario'))) {
            return $response;
        }
        $qb = $em->getRepository('UsuarioBundle:Usuario')->createQueryBuilder('u')->innerJoin('u.persona', 'p');
        if (is_array($order = $this->getOrder('usuario'))) {
        $qb->orderBy($order['field'], $order['type']);
        }
        $paginator = $this->filter($form, $qb, 'usuario');
                    return array(
            'form'      => $form->createView(),
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a Usuario entity.
     *
     * @Route("/{id}/show", name="gestion_usuario_show")
     * @Template()
     */
    public function showAction(Usuario $usuario)
    {
        $deleteForm = $this->createDeleteForm($usuario->getId());
    
        return array(
            'usuario' => $usuario,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Usuario entity.
     *
     * @Route("/new", name="gestion_usuario_new")
     * @Template()
     */
    public function newAction()
    {
        $usuario = new Usuario();
        $form   = $this->createForm(new UsuarioType(), $usuario);

        return array(
            'usuario' => $usuario,
            'form'   => $form->createView(),
        );
    }
    /**
     * Creates a new Usuario entity.
     *
     * @Route("/create", name="gestion_usuario_create")
     * @Method("POST")
     * @Template("UsuarioBundle:Usuario:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');        
        $usuario =  $userManager->createUser();
        $form = $this->createForm(new UsuarioType(), $usuario);
        $params = $request->request->all();
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $factory = $this->get('security.encoder_factory');  

            $persona = new Persona(); 
            $tipoDocumento  = $em->getRepository('GestionBundle:TipoDocumentoIdentidad')->find($params["usuario"]["tipoDocumento"]);
            $sexo           = $em->getRepository('GestionBundle:Sexo')->find($params["usuario"]["sexo"]);
            $coordinaciones = $params["usuario"]["coordinaciones"]; 
                        
            $persona->setApellido($params["usuario"]["apellido"]);
            $persona->setNombre($params["usuario"]["nombre"]);
            $persona->setTipoDocumento($tipoDocumento);
            $persona->setNroDoc($params["usuario"]["nroDoc"]);
            $persona->setSexo($sexo);         

            //Agrego la/s entidades a la persona
            foreach ($coordinaciones as $key => $value) {   
                $entidad = $em->getRepository('GestionBundle:Entidad')->find($value);
                $persona->addEntidad($entidad);
            }              

            $encoder = $factory->getEncoder($usuario);
            $password = $encoder->encodePassword($params['usuario']['password'], $usuario->getSalt());
            $usuario->setPassword($password);
            $usuario->setHasToChangePassword(true);
            $usuario->setPersona($persona);

          //  $usuario->addRole("ROLE_DOCUMENTOS_CONSULTA");
            if(isset($params['usuario']['roles'])){
                foreach ($params['usuario']['roles'] as $key => $value) {
                    $usuario->addRole($value);
                }    
            }
            /*if(isset($params['usuario']['grupos'])){
                foreach ($params['usuario']['grupos'] as $key => $value) {
                    $usuario->addGroup($value);
                }    
            }            */
            

            $userManager->updateUser($usuario);

            $this->get('session')->getFlashBag()->add('success', 'flash.create.success');

            return $this->redirect($this->generateUrl('gestion_usuario_show', array('id' => $usuario->getId())));
        }
        $this->get('session')->getFlashBag()->add('danger', 'flash.create.error');
        return array(
            'usuario' => $usuario,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Usuario entity.
     *
     * @Route("/{id}/edit", name="gestion_usuario_edit")
     * @Template()
     */
    public function editAction(Usuario $usuario)
    {
        $editForm = $this->createForm(new UsuarioType(), $usuario);

        $em = $this->getDoctrine()->getManager();

        $persona = $em->getRepository('GestionBundle:Persona')->find($usuario->getPersona()->getId());

        $editForm->get('apellido')->setData($persona->getApellido());
        $editForm->get('nombre')->setData($persona->getNombre());
        $editForm->get('tipoDocumento')->setData($persona->getTipoDocumento());
        $editForm->get('nroDoc')->setData($persona->getNroDoc());
        $editForm->get('sexo')->setData($persona->getSexo());
        $editForm->get('coordinaciones')->setData($persona->getEntidades());
        


        $deleteForm = $this->createDeleteForm($usuario->getId());

        return array(
            'usuario' => $usuario,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Edits an existing Usuario entity.
     *
     * @Route("/{id}/update", name="gestion_usuario_update")
     * @Method("POST")
     * @Template("UsuarioBundle:Usuario:edit.html.twig")
     */
    public function updateAction(Usuario $usuario, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($usuario->getId());
        $editForm = $this->createForm(new UsuarioType(), $usuario);
        if ($editForm->handleRequest($request)->isValid()) {
            
            //obtengo los valores del formulario que vienen por request
            $params   = $request->request->all();  

            //identifico y obtengo las entidades correspondientes para actualizar los datos de persona
            $persona        = $em->getRepository('GestionBundle:Persona')->find($usuario->getPersona()->getId());
            $tipoDocumento  = $em->getRepository('GestionBundle:TipoDocumentoIdentidad')->find($params["usuario"]["tipoDocumento"]);
            $sexo           = $em->getRepository('GestionBundle:Sexo')->find($params["usuario"]["sexo"]);
            $coordinaciones = $params["usuario"]["coordinaciones"]; 
            
            
            $persona->setApellido($params["usuario"]["apellido"]);
            $persona->setNombre($params["usuario"]["nombre"]);
            $persona->setTipoDocumento($tipoDocumento);
            $persona->setNroDoc($params["usuario"]["nroDoc"]);
            $persona->setSexo($sexo);

            //Primero elimino todas las entidaes que tiene asociadas para despues incorporar las nuevas seleccionadas
            foreach ($persona->getEntidades() as $entidad) {
                $persona->removeEntidad($entidad);
            }

            //Agrego la/s entidades a la persona
            foreach ($coordinaciones as $key => $value) {   
                $entidad = $em->getRepository('GestionBundle:Entidad')->find($value);
                $persona->addEntidad($entidad);
            }
        

            $em->persist($persona);

            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'flash.update.success');
            return $this->redirect($this->generateUrl('gestion_usuario_edit', array('id' => $usuario->getId())));
        }
        $this->get('session')->getFlashBag()->add('danger', 'flash.update.error');
        return array(
            'usuario' => $usuario,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="gestion_usuario_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('usuario', $field, $type);

        return $this->redirect($this->generateUrl('gestion_usuario'));
    }

    /**
     * @param string $name  session name
     * @param string $field field name
     * @param string $type  sort type ("ASC"/"DESC")
     */
    protected function setOrder($name, $field, $type = 'ASC')
    {
        $this->getRequest()->getSession()->set('sort.' . $name, compact('field', 'type'));
    }

    /**
     * @param  string $name
     * @return array
     */
    protected function getOrder($name)
    {
        $session = $this->getRequest()->getSession();

        return $session->has('sort.' . $name) ? $session->get('sort.' . $name) : null;
    }

    /**
     * @param QueryBuilder $qb
     * @param string       $name
     */
    protected function addQueryBuilderSort(QueryBuilder $qb, $name)
    {
        $alias = current($qb->getDQLPart('from'))->getAlias();
        if (is_array($order = $this->getOrder($name))) {
            $qb->orderBy($alias . '.' . $order['field'], $order['type']);
        }
    }
    /**
     * Save filters
     *
     * @param  FormInterface $form
     * @param  string        $name        route/entity name
     * @param  string        $route       route name, if different from entity name
     * @param  array         $params      possible route parameters
     * @return Response
     */
    protected function saveFilter(FormInterface $form, $name, $route = null, array $params = null)
    {
        $request = $this->getRequest();
        $url = is_null($route) ? $this->generateUrl($name) : $this->generateUrl($route, is_null($params) ? array() : $params);
        if ($request->query->has('submit-filter') && $form->handleRequest($request)->isValid()) {
            $request->getSession()->set('filter.' . $name, $request->query->get($form->getName()));

            return $this->redirect($url);
        } elseif ($request->query->has('reset-filter')) {
            $request->getSession()->set('filter.' . $name, null);

            return $this->redirect($url);
        }
    }

    /**
     * Filter form
     *
     * @param  FormInterface  $form
     * @param  QueryBuilder   $qb
     * @return SlidingPagination
     */
    protected function filter(FormInterface $form, QueryBuilder $qb, $name)
    {
        if (!is_null($values = $this->getFilter($name))) {
            if ($form->submit($values)->isValid()) {
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $qb);
            }
        }

        // possible sorting
        $this->addQueryBuilderSort($qb, $name);
        return $this->get('knp_paginator')->paginate($qb->getQuery(), $this->getRequest()->query->get('page', 1), (isset($this->container->parameters['knp_paginator.page_range'])) ? $this->container->parameters['knp_paginator.page_range'] : 20);
    }

    /**
     * Get filters from session
     *
     * @param  string $name
     * @return array
     */
    protected function getFilter($name)
    {
        return $this->getRequest()->getSession()->get('filter.' . $name);
    }

    /**
     * Deletes a Usuario entity.
     *
     * @Route("/{id}/delete", name="gestion_usuario_delete")
     * @Method("POST")
     */
    public function deleteAction(Usuario $usuario, Request $request)
    {
        $form = $this->createDeleteForm($usuario->getId());
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            try{
                $em->remove($usuario);
                $em->flush();
            } catch (\Exception $e) {            
                if($e->getPrevious()->getCode() == 23000){
                    $this->get('session')->getFlashBag()->add('danger', 'flash.delete.error-relations');
                    
                }else{
                    throw new \Exception($e);                
                }                
                return $this->redirect($this->generateUrl('gestion_usuario'));
            }            
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        }else{
            $this->get('session')->getFlashBag()->add('danger', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('gestion_usuario'));
    }

    /**
     * Create Delete form
     *
     * @param integer $id
     * @return FormBuilder
     */
    protected function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

}
