<?php

namespace Aleste\TrackerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Aleste\TrackerBundle\Entity\Proyecto;
use Aleste\TrackerBundle\Command\AgregarProyecto;
use Aleste\TrackerBundle\Entity\ActualizarProyecto;
use Aleste\TrackerBundle\Entity\EliminarProyecto;
use Aleste\TrackerBundle\Form\Type\ProyectoType;
use Aleste\TrackerBundle\Form\Type\ProyectoFilterType;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Proyecto controller.
 *
 * @Route("/proyecto")
 */
class ProyectoController extends Controller
{
    /**
     * Lists all Proyecto entities.
     *
     * @Route("/", name="proyecto")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new ProyectoFilterType());
        if (!is_null($response = $this->saveFilter($form, 'proyecto', 'proyecto'))) {
            return $response;
        }
            $qb = $em->getRepository('TrackerBundle:Proyecto')->createQueryBuilder('p');
        if (is_array($order = $this->getOrder('proyecto'))) {
        $qb->orderBy($order['field'], $order['type']);
        }
        $paginator = $this->filter($form, $qb, 'proyecto');
                    return array(
            'form'      => $form->createView(),
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a Proyecto entity.
     *
     * @Route("/{id}/show", name="proyecto_show")
     * @Template()
     */
    public function showAction(Proyecto $proyecto)
    {
        $deleteForm = $this->createDeleteForm($proyecto->getId());

        return array(
            'proyecto' => $proyecto,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Proyecto entity.
     *
     * @Route("/new", name="proyecto_new")
     * @Template()
     */
    public function newAction()
    {
        $proyecto = new Proyecto();
        $form   = $this->createForm(new ProyectoType(), $proyecto);

        return array(
            'proyecto' => $proyecto,
            'form'   => $form->createView(),
        );
    }
    /**
     * Creates a new Proyecto entity.
     *
     * @Route("/create", name="proyecto_create")
     * @Method("POST")
     * @Template("TrackerBundle:Proyecto:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $proyecto = new Proyecto();
        $form = $this->createForm(new ProyectoType(), $proyecto);

        if ($form->handleRequest($request)->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            
            $actividad = new AgregarProyecto($em,$proyecto);
            $actividad->execute();            
            
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('_datos_creados_exito'));

            return $this->redirect($this->generateUrl('proyecto_show', array('id' => $proyecto->getId())));
        }
        $this->get('session')->getFlashBag()->add('danger', $this->get('translator')->trans('_error_datos_formulario'));
        return array(
            'proyecto' => $proyecto,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Proyecto entity.
     *
     * @Route("/{id}/edit", name="proyecto_edit")
     * @Template()
     */
    public function editAction(Proyecto $proyecto)
    {
        $editForm = $this->createForm(new ProyectoType(), $proyecto);
        $deleteForm = $this->createDeleteForm($proyecto->getId());

        return array(
            'proyecto' => $proyecto,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Edits an existing Proyecto entity.
     *
     * @Route("/{id}/update", name="proyecto_update")
     * @Method("POST")
     * @Template("TrackerBundle:Proyecto:edit.html.twig")
     */
    public function updateAction(Proyecto $proyecto, Request $request)
    {
        $deleteForm = $this->createDeleteForm($proyecto->getId());
        $editForm = $this->createForm(new ProyectoType(), $proyecto);
        if ($editForm->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $actividad = new ActualizarProyecto($em);
            $actividad->execute();
            
            $this->get('session')->getFlashBag()->add('success', 'flash.update.success');
            return $this->redirect($this->generateUrl('proyecto_edit', array('id' => $proyecto->getId())));
        }
        $this->get('session')->getFlashBag()->add('danger', 'flash.update.error');
        return array(
            'proyecto'    => $proyecto,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="proyecto_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('proyecto', $field, $type);

        return $this->redirect($this->generateUrl('proyecto'));
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
     * Deletes a Proyecto entity.
     *
     * @Route("/{id}/delete", name="proyecto_delete")
     * @Method("POST")
     */
    public function deleteAction(Proyecto $proyecto, Request $request)
    {
        $form = $this->createDeleteForm($proyecto->getId());
        if ($form->handleRequest($request)->isValid()) {
            try{
                $actividad = new EliminarProyecto($this->getDoctrine()->getManager());
                $actividad->execute($proyecto);
            } catch (\Exception $e) {            
                if($e->getPrevious()->getCode() == 23000){
                    $this->get('session')->getFlashBag()->add('danger', 'flash.delete.error-relations');
                    
                }else{
                    throw new \Exception($e);                
                }                
                return $this->redirect($this->generateUrl('proyecto'));
            }            
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        }else{
            $this->get('session')->getFlashBag()->add('danger', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('proyecto'));
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
