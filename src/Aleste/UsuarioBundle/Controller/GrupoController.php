<?php

namespace Aleste\UsuarioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Aleste\UsuarioBundle\Entity\Grupo;
use Aleste\UsuarioBundle\Form\Type\GrupoType;
use Aleste\UsuarioBundle\Form\Type\GrupoFilterType;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;


/**
 * Grupo controller.
 *
 * @Route("/gestion/grupo")
 */
class GrupoController extends Controller
{
    /**
     * Lists all Grupo entities.
     *
     * @Route("/", name="gestion_grupo")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new GrupoFilterType());
        if (!is_null($response = $this->saveFilter($form, 'grupo', 'gestion_grupo'))) {
            return $response;
        }
            $qb = $em->getRepository('UsuarioBundle:Grupo')->createQueryBuilder('g');
        if (is_array($order = $this->getOrder('grupo'))) {
        $qb->orderBy($order['field'], $order['type']);
        }
        $paginator = $this->filter($form, $qb, 'grupo');
                    return array(
            'form'      => $form->createView(),
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a Grupo entity.
     *
     * @Route("/{id}/show", name="gestion_grupo_show")
     * @Template()
     */
    public function showAction(Grupo $grupo)
    {
        $deleteForm = $this->createDeleteForm($grupo->getId());

        return array(
            'grupo' => $grupo,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Grupo entity.
     *
     * @Route("/new", name="gestion_grupo_new")
     * @Template()
     */
    public function newAction()
    {
        //$grupo = new Grupo();
        //$form   = $this->createForm(new GrupoType(), $grupo);
          $groupManager = $this->container->get('fos_user.group_manager');     
        $grupo =  $groupManager->createGroup('');
        $form = $this->createForm(new GrupoType(), $grupo);

        return array(
            'grupo' => $grupo,
            'form'   => $form->createView(),
        );
    }
    /**
     * Creates a new Grupo entity.
     *
     * @Route("/create", name="gestion_grupo_create")
     * @Method("POST")
     * @Template("UsuarioBundle:Grupo:new.html.twig")
     */
    public function createAction(Request $request)
    {
      //  $grupo = new Grupo();
      //  $form = $this->createForm(new GrupoType(), $grupo);
        $groupManager = $this->container->get('fos_user.group_manager');     
        $grupo =  $groupManager->createGroup('');
        $form = $this->createForm(new GrupoType(), $grupo);
        $params = $request->request->all();
        if ($form->handleRequest($request)->isValid()) {
            //$em = $this->getDoctrine()->getManager();
            //$em->persist($grupo);
            //$em->flush();
                  foreach ($params['grupo']['roles'] as $key => $value) {
                $grupo->addRole($value);
            }
            $groupManager->updateGroup($grupo);
            $this->get('session')->getFlashBag()->add('success', 'flash.create.success');

            return $this->redirect($this->generateUrl('gestion_grupo_show', array('id' => $grupo->getId())));
        }
        $this->get('session')->getFlashBag()->add('danger', 'flash.create.error');
        return array(
            'grupo' => $grupo,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Grupo entity.
     *
     * @Route("/{id}/edit", name="gestion_grupo_edit")
     * @Template()
     */
    public function editAction(Grupo $grupo)
    {
        $editForm = $this->createForm(new GrupoType(), $grupo);
        $deleteForm = $this->createDeleteForm($grupo->getId());

        return array(
            'grupo' => $grupo,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Edits an existing Grupo entity.
     *
     * @Route("/{id}/update", name="gestion_grupo_update")
     * @Method("POST")
     * @Template("UsuarioBundle:Grupo:edit.html.twig")
     */
    public function updateAction(Grupo $grupo, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($grupo->getId());
        $editForm = $this->createForm(new GrupoType(), $grupo);
        if ($editForm->handleRequest($request)->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.update.success');
            return $this->redirect($this->generateUrl('gestion_grupo_edit', array('id' => $grupo->getId())));
        }
        $this->get('session')->getFlashBag()->add('danger', 'flash.update.error');
        return array(
            'grupo' => $grupo,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="gestion_grupo_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('grupo', $field, $type);

        return $this->redirect($this->generateUrl('gestion_grupo'));
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
     * Deletes a Grupo entity.
     *
     * @Route("/{id}/delete", name="gestion_grupo_delete")
     * @Method("POST")
     */
    public function deleteAction(Grupo $grupo, Request $request)
    {
        $form = $this->createDeleteForm($grupo->getId());
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            try{
                $em->remove($grupo);
                $em->flush();
            } catch (\Exception $e) {            
                if($e->getPrevious()->getCode() == 23000){
                    $this->get('session')->getFlashBag()->add('danger', 'flash.delete.error-relations');
                    
                }else{
                    throw new \Exception($e);                
                }                
                return $this->redirect($this->generateUrl('gestion_grupo'));
            }            
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        }else{
            $this->get('session')->getFlashBag()->add('danger', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('gestion_grupo'));
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
