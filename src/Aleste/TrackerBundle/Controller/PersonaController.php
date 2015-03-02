<?php

namespace Aleste\TrackerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Aleste\TrackerBundle\Entity\Persona;
use Aleste\TrackerBundle\Form\Type\PersonaType;
use Aleste\TrackerBundle\Form\Type\PersonaFilterType;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Persona controller.
 *
 * @Route("/persona")
 */
class PersonaController extends Controller
{
    /**
     * Lists all Persona entities.
     *
     * @Route("/", name="persona")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new PersonaFilterType());
        if (!is_null($response = $this->saveFilter($form, 'persona', 'persona'))) {
            return $response;
        }
            $qb = $em->getRepository('TrackerBundle:Persona')->createQueryBuilder('p');
        if (is_array($order = $this->getOrder('persona'))) {
        $qb->orderBy($order['field'], $order['type']);
        }
        $paginator = $this->filter($form, $qb, 'persona');
                    return array(
            'form'      => $form->createView(),
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a Persona entity.
     *
     * @Route("/{id}/show", name="persona_show")
     * @Template()
     */
    public function showAction(Persona $persona)
    {
        $deleteForm = $this->createDeleteForm($persona->getId());

        return array(
            'persona' => $persona,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Persona entity.
     *
     * @Route("/new", name="persona_new")
     * @Template()
     */
    public function newAction()
    {
        $persona = new Persona();
        $form   = $this->createForm(new PersonaType(), $persona);

        return array(
            'persona' => $persona,
            'form'   => $form->createView(),
        );
    }
    /**
     * Creates a new Persona entity.
     *
     * @Route("/create", name="persona_create")
     * @Method("POST")
     * @Template("TrackerBundle:Persona:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $persona = new Persona();
        $form = $this->createForm(new PersonaType(), $persona);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($persona);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'flash.create.success');

            return $this->redirect($this->generateUrl('persona_show', array('id' => $persona->getId())));
        }
        $this->get('session')->getFlashBag()->add('danger', 'flash.create.error');
        return array(
            'persona' => $persona,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Persona entity.
     *
     * @Route("/{id}/edit", name="persona_edit")
     * @Template()
     */
    public function editAction(Persona $persona)
    {
        $editForm = $this->createForm(new PersonaType(), $persona);
        $deleteForm = $this->createDeleteForm($persona->getId());

        return array(
            'persona' => $persona,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Edits an existing Persona entity.
     *
     * @Route("/{id}/update", name="persona_update")
     * @Method("POST")
     * @Template("TrackerBundle:Persona:edit.html.twig")
     */
    public function updateAction(Persona $persona, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($persona->getId());
        $editForm = $this->createForm(new PersonaType(), $persona);
        if ($editForm->handleRequest($request)->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.update.success');
            return $this->redirect($this->generateUrl('persona_edit', array('id' => $persona->getId())));
        }
        $this->get('session')->getFlashBag()->add('danger', 'flash.update.error');
        return array(
            'persona' => $persona,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="persona_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('persona', $field, $type);

        return $this->redirect($this->generateUrl('persona'));
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
     * Deletes a Persona entity.
     *
     * @Route("/{id}/delete", name="persona_delete")
     * @Method("POST")
     */
    public function deleteAction(Persona $persona, Request $request)
    {
        $form = $this->createDeleteForm($persona->getId());
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            try{
                $em->remove($persona);
                $em->flush();
            } catch (\Exception $e) {            
                if($e->getPrevious()->getCode() == 23000){
                    $this->get('session')->getFlashBag()->add('danger', 'flash.delete.error-relations');
                    
                }else{
                    throw new \Exception($e);                
                }                
                return $this->redirect($this->generateUrl('persona'));
            }            
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        }else{
            $this->get('session')->getFlashBag()->add('danger', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('persona'));
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
