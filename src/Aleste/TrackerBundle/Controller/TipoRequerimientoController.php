<?php

namespace Aleste\TrackerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Aleste\TrackerBundle\Entity\TipoRequerimiento;
use Aleste\TrackerBundle\Form\Type\TipoRequerimientoType;
use Aleste\TrackerBundle\Form\Type\TipoRequerimientoFilterType;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * TipoRequerimiento controller.
 *
 * @Route("/tiporequerimiento")
 */
class TipoRequerimientoController extends Controller
{
    /**
     * Lists all TipoRequerimiento entities.
     *
     * @Route("/", name="tiporequerimiento")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new TipoRequerimientoFilterType());
        if (!is_null($response = $this->saveFilter($form, 'tiporequerimiento', 'tiporequerimiento'))) {
            return $response;
        }
            
        $qb = $em->getRepository('TrackerBundle:TipoRequerimiento')->createQueryBuilder('t');
        if (is_array($order = $this->getOrder('tiporequerimiento'))) {
            $qb->orderBy($order['field'], $order['type']);
        }
        $paginator = $this->filter($form, $qb, 'tiporequerimiento');
                    return array(
            'form'      => $form->createView(),
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a TipoRequerimiento entity.
     *
     * @Route("/{id}/show", name="tiporequerimiento_show")
     * @Template()
     */
    public function showAction(TipoRequerimiento $tiporequerimiento)
    {
        $deleteForm = $this->createDeleteForm($tiporequerimiento->getId());

        return array(
            'tiporequerimiento' => $tiporequerimiento,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new TipoRequerimiento entity.
     *
     * @Route("/new", name="tiporequerimiento_new")
     * @Template()
     */
    public function newAction()
    {
        $tiporequerimiento = new TipoRequerimiento();
        $form   = $this->createForm(new TipoRequerimientoType(), $tiporequerimiento);

        return array(
            'tiporequerimiento' => $tiporequerimiento,
            'form'   => $form->createView(),
        );
    }
    /**
     * Creates a new TipoRequerimiento entity.
     *
     * @Route("/create", name="tiporequerimiento_create")
     * @Method("POST")
     * @Template("TrackerBundle:TipoRequerimiento:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $tiporequerimiento = new TipoRequerimiento();
        $form = $this->createForm(new TipoRequerimientoType(), $tiporequerimiento);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $herramienta = $em->getReference('TrackerBundle:Herramienta', 1);
            $tiporequerimiento->setHerramienta($herramienta);
            $em->persist($tiporequerimiento);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('_datos_creados_exito'));

            return $this->redirect($this->generateUrl('tiporequerimiento_show', array('id' => $tiporequerimiento->getId())));
        }
        $this->get('session')->getFlashBag()->add('danger', $this->get('translator')->trans('_error_datos_formulario'));
        return array(
            'tiporequerimiento' => $tiporequerimiento,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing TipoRequerimiento entity.
     *
     * @Route("/{id}/edit", name="tiporequerimiento_edit")
     * @Template()
     */
    public function editAction(TipoRequerimiento $tiporequerimiento)
    {
        $editForm = $this->createForm(new TipoRequerimientoType(), $tiporequerimiento);
        $deleteForm = $this->createDeleteForm($tiporequerimiento->getId());

        return array(
            'tiporequerimiento' => $tiporequerimiento,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Edits an existing TipoRequerimiento entity.
     *
     * @Route("/{id}/update", name="tiporequerimiento_update")
     * @Method("POST")
     * @Template("TrackerBundle:TipoRequerimiento:edit.html.twig")
     */
    public function updateAction(TipoRequerimiento $tiporequerimiento, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($tiporequerimiento->getId());
        $editForm = $this->createForm(new TipoRequerimientoType(), $tiporequerimiento);
        if ($editForm->handleRequest($request)->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('_datos_actualizados_exito'));
            return $this->redirect($this->generateUrl('tiporequerimiento_edit', array('id' => $tiporequerimiento->getId())));
        }
        $this->get('session')->getFlashBag()->add('danger', $this->get('translator')->trans('_error_datos_formulario'));
        return array(
            'tiporequerimiento' => $tiporequerimiento,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="tiporequerimiento_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('tiporequerimiento', $field, $type);

        return $this->redirect($this->generateUrl('tiporequerimiento'));
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
        return $this->get('knp_paginator')->paginate($qb->getQuery(), $this->getRequest()->query->get('page', 1), 20);
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
     * Deletes a TipoRequerimiento entity.
     *
     * @Route("/{id}/delete", name="tiporequerimiento_delete")
     * @Method("POST")
     */
    public function deleteAction(TipoRequerimiento $tiporequerimiento, Request $request)
    {
        $form = $this->createDeleteForm($tiporequerimiento->getId());
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            try{
                $em->remove($tiporequerimiento);
                $em->flush();
            } catch (\Exception $e) {            
                if($e->getPrevious()->getCode() == 23000){
                    $this->get('session')->getFlashBag()->add('danger', 'flash.delete.error-relations');
                    
                }else{
                    throw new \Exception($e);                
                }                
                return $this->redirect($this->generateUrl('tiporequerimiento'));
            }            
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('_dato_eliminado_exito'));
        }else{
            $this->get('session')->getFlashBag()->add('danger', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('tiporequerimiento'));
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
