<?php

namespace Aleste\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Base Controller.
 */
abstract class BaseController extends Controller
{

    public function revisionesAction($id)
    {
        if(!$this->validarSeguridad('ROLE_ADMIN'))
        {
            throw new AccessDeniedException();
        }
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository($this->getClassName())->find($id);
        
        $repo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry');
        
        $revisiones = $repo->getLogEntries($entity);
        
        return $this->render($this->getClassName().':revisions.html.twig', array(
                                                                                 'entity'     => $entity,
                                                                                 'revisiones' => $revisiones,
                                                                                )
                            );        
    }
    
    public function revertAction($id,$revision)
    {
        if(!$this->validarSeguridad('ROLE_ADMIN'))
        {
            throw new AccessDeniedException();
        }
        
        $em = $this->getDoctrine()->getManager();
        
        $entity = $em->getRepository($this->getClassName())->find($id); 
     
        $repo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry');
        
        $repo->revert($entity,$revision);
        
        $em->persist($entity);
       
        try {
            $em->flush();
        }
        catch(\Doctrine\DBAL\DBALException $e )
        {
            if( $e->getCode()  === 0)
            {
              $this->get('session')->getFlashBag()->add('error','No se pudo retornar el dato a la revisión ' . $opciones['revision']);
              return $this->redirect($this->generateUrl($this->getRouteRevision(),array('id' => $id)));
            }

            else throw $e;
        }
        
        $this->get('session')->getFlashBag()->add('success','Dato vuelto a la revisión ' . $revisión. ' correctamente');
        
        return $this->redirect($this->generateUrl($this->getRouteShow(), array('id' => $id)));
    } 
    
    public function deleteAction(Request $request, $id)
    {
        if(!$this->validarSeguridad($this->getDeleteRole()))
        {
            throw new AccessDeniedException();
        }

        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        
        if($form->isValid())
        {        
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository($this->getClassName())->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ' . $this->getClassName() . ' entity.');
            }

            $this->liberar($entity);
            
            $em->remove($entity);
            
            try {
                $em->flush();
            }
            catch(\Doctrine\DBAL\DBALException $e )
            {
                if( $e->getCode() === 0 )
                {
                  $this->get('session')->getFlashBag()->add('error',$this->get('translator')->trans('_dato_en_uso_actualmente'));
                  return $this->redirect($this->generateUrl($this->getRouteEdit(),array('id' => $id)));
                }

                else throw $e;
            }
        
            $this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('_dato_eliminado_exito'));
            return $this->redirect($this->generateUrl($this->getRouteIndex()));    
        }

        $this->get('session')->getFlashBag()->add('error',$this->get('translator')->trans('_error_datos_formulario'));
        return $this->redirect($this->generateUrl($this->getRouteIndex()));
    }
    
    protected function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                    ->setAction($this->generateUrl($this->getRouteDelete(), array('id' => $id)))
                    ->setMethod('POST')
                    ->getForm()
        ;
    }
    
    public function updateAction(Request $request, $id)
    {
        if(!$this->validarSeguridad($this->getUpdateRole()))
        {
            throw new AccessDeniedException();
        }
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->getClassName())->find($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('Unable to find ' . $this->getClassName() . ' entity.');
        }

        if(!$this->isEditable($entity))
        {
            throw new AccessDeniedException();
        }

        $editForm   = $this->createForm($this->getNewFormType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $editForm->handleRequest($request);

        if ($editForm->isValid())
        {
            
            $this->addFiles($entity);
            
            $this->updateExtras($entity);
            
            $em->persist($entity);

            try {
                $em->flush();
            }
            catch(\Doctrine\DBAL\DBALException $e )
            {
                if( $e->getCode() === 0 )
                {
                    $this->get('session')->getFlashBag()->add('error',$this->get('translator')->trans('_dato_en_uso_actualmente'));
                    return $this->render($this->getClassName() .':edit.html.twig', array(
                                                                                         'entity'      => $entity,
                                                                                         'edit_form'   => $editForm->createView(),
                                                                                         'delete_form' => $deleteForm->createView(),
                                                                                        )
                                        );
                }
                  
                else throw $e;
            }

            $this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('_datos_actualizados_exito'));
            return $this->redirect($this->generateUrl($this->getRouteEdit(), array('id' => $id)));
        }

        $this->get('session')->getFlashBag()->add('error',$this->get('translator')->trans('_error_datos_formulario'));
        
        return $this->render($this->getClassName() .':edit.html.twig', array(
                                                                                'entity'      => $entity,
                                                                                'edit_form'   => $editForm->createView(),
                                                                                'delete_form' => $deleteForm->createView(),
                                                                            )
                            );
    }

    public function deleteListAction(Request $request, $id)
    {
        if(!$this->validarSeguridad($this->getDeleteRole()))
        {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->getClassName())->find($id);

        $deleteForm = $this->createDeleteForm($id);
        
        return $this->render($this->getClassName() .':delete.html.twig', array(
                                                                                'entity'      => $entity,
                                                                                'delete_form' => $deleteForm->createView(),
                                                                                'action'      => $this->generateUrl($this->getRouteDelete(), array('id' => $id)),
                                                                              )
                            );
    }
    
    public function editAction($id)
    {
        if(!$this->validarSeguridad($this->getUpdateRole()))
        {
            throw new AccessDeniedException();
        }
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->getClassName())->find($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('Unable to find '. $this->getClassName() .' entity.');
        }
        
        if(!$this->isEditable($entity))
        {
            throw new AccessDeniedException();
        }
        
        $editForm = $this->createForm($this->getNewFormType(), $entity);
        $deleteForm = $this->createDeleteForm($id);
        
        return $this->render($this->getClassName(). ':edit.html.twig', array(
                                                                             'entity'      => $entity,
                                                                             'edit_form'   => $editForm->createView(),
                                                                             'delete_form' => $deleteForm->createView(),
                                                                            )
                            );
    }
    
    public function createAction(Request $request)
    {
        if(!$this->validarSeguridad($this->getCreateRole()))
        {
            throw new AccessDeniedException();
        }
        
        $entity  = $this->getNewEntity();
        $form    = $this->createForm($this->getNewFormType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            
            $this->addFiles($entity);

            $this->addExtras($entity);
            
            $em->persist($entity);
         
            try {
                $em->flush();
            }
            catch(\Doctrine\DBAL\DBALException $e )
            {
                if( $e->getCode() === 0 )
                {
                    $this->get('session')->getFlashBag()->add('error',$this->get('translator')->trans('_dato_en_uso_actualmente'));
                    return $this->render($this->getClassName() . ':new.html.twig', array(
                                                                                         'entity'   => $entity,
                                                                                         'form'     => $form->createView(),
                                                                                        ));
                }

                else throw $e;
            }
            
            $this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('_datos_creados_exito'));
            return $this->redirect($this->generateUrl($this->getRouteShow(), array('id' => $entity->getId())));
        }

        $this->get('session')->getFlashBag()->add('error',$this->get('translator')->trans('_error_datos_formulario'));
        
        return $this->render($this->getClassName() . ':new.html.twig', array(
                                                                              'entity' => $entity,
                                                                              'form'   => $form->createView(),
                                                                            )
                            );
    }
    
    public function newAction()
    {   
        if(!$this->validarSeguridad($this->getCreateRole()))
        {
            throw new AccessDeniedException();
        }
        
        $entity = $this->getNewEntity();
        $form   = $this->createForm($this->getNewFormType(), $entity);
        
        return $this->render($this->getClassName() . ':new.html.twig', array(
                                                                              'entity' => $entity,
                                                                              'form'   => $form->createView(),
                                                                            )
                            );
    }
    
    public function showAction($id)
    {
        if(!$this->validarSeguridad($this->getShowRole()))
        {
            throw new AccessDeniedException();
        }
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->getClassName())->find($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('Unable to find '. $this->getClassName() . ' entity.');
        }

        if($this->getRequest()->isXmlHttpRequest())
        {
           return $this->render($this->getClassName() .':show.ajax.html.twig',array('entity' => $entity)); 
        }
        
        $deleteForm = $this->createDeleteForm($id);
        
        return $this->render($this->getClassName() . ':show.html.twig', array(
                                                                              'entity'      => $entity,
                                                                              'delete_form' => $deleteForm->createView(),
                                                                             )
                            );
    }
    
    public function indexAction()
    {
        if(!$this->validarSeguridad($this->getListRole()))
        {
            throw new AccessDeniedException();
        }
        
        $request = $this->getRequest();
        
        $request->getSession()->set($this->getFiltro(),''); 
        
        $paginator = $this->get('knp_paginator');
        
        $pagination = $paginator->paginate(
                                            $this->getDoctrine()->getManager()->createQuery($this->getDQL()),
                                            $this->get('request')->query->get('page', 1)  /*page number*/,
                                            $this->container->getParameter('list_rows')   /*limit per page*/
                                          );
           
        if($request->isXmlHttpRequest())
        {    
           $dir  = $this->get('request')->query->get('direction', 'asc');
           
           if($dir == 'asc'){$dir='desc';}else{$dir ='asc';}
           
           return $this->render($this->getClassName() .':index.ajax.html.twig', array(
                                                                                       'dir'      => $dir,
                                                                                       'page'     => $this->get('request')->query->get('page', 1),
                                                                                       'entities' => $pagination,
                                                                                      )
                                );
        }

        return $this->render($this->getClassName() . ':index.html.twig', array('entities' => $pagination));
    }
    
    public function addFiles($entity)
    {
       //debe implementarlo la clase concreta
    }

    public function addExtras($entity)
    {
       //debe implementarlo la clase concreta
    }

    public function updateExtras($entity)
    {
       //debe implementarlo la clase concreta
    }
    
    public function isEditable($entity)
    {
        //debe redefinirlo la clase concreta
        return true;
    }
    
    public function liberar($entity)
    {
        //debe redefinirlo la clase concreta
    }
    
    public function createDeleteForms($entities)
    {
        $deleteForms = array();
        
        foreach ($entities as $entity)
        {
          $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }
        
        return $deleteForms;
    }
    
    public function filterAction(Request $request)
    {
        if(!$this->validarSeguridad($this->getListRole()))
        {
            throw new AccessDeniedException();
        }

        $filtro = trim($request->get($this->getFiltro()));

        if(($filtro == '')&&($request->getSession()->get($this->getFiltro())==''))
        {
          return $this->indexAction();
        }
        
        if($filtro == '')
        {
          $filtro = $request->getSession()->get($this->getFiltro());
        }else{
          $request->getSession()->set($this->getFiltro(),$filtro);  
        }
        
        $paginator = $this->get('knp_paginator');
       
        $pagination = $paginator->paginate(
                                            $this->getDQLFiltro($filtro),
                                            $this->get('request')->query->get('page', 1)  /*page number*/,
                                            $this->container->getParameter('list_rows')   /*limit per page*/
                                          );
        
        if($request->isXmlHttpRequest())
        {
           $dir  = $this->get('request')->query->get('direction', 'asc');
           
           if($dir == 'asc'){$dir='desc';}else{$dir ='asc';}
                      
           return $this->render($this->getClassName() .':index.ajax.html.twig',array(
                                                                                      'dir'      => $dir,
                                                                                      'page'     => $this->get('request')->query->get('page', 1),
                                                                                      'entities' => $pagination,
                                                                                    )
                               );
        }
        
        return $this->render($this->getClassName() .':index.html.twig', array(
                                                                              'entities' => $pagination,
                                                                              'filtro'   => $filtro,
                                                                             )
                            );
    }
    
    public function validarSeguridad($role)
    {           
        $token = $this->container->get('security.context')->getToken();
        
        if(!$token)
        {
          return false;
        }
    
        return $this->container->get('security.context')->isGranted($role);
    }
     
    public function getUser()
    {
        if($this->container->get('security.context')->getToken())
        {
            return $this->container->get('security.context')->getToken()->getUser();
        }
        
        return null;
    }
    
    protected function getErrorMessages(\Symfony\Component\Form\Form $form)
    {
        $errors = array();
    
        if ($form->count() > 0) {
            foreach ($form->all() as $child) {
                /**
                 * @var \Symfony\Component\Form\Form $child
                 */
                if (!$child->isValid()) {
                    $errors[$child->getName()] = $this->getErrorMessages($child);
                }
            }
        } else {
            /**
             * @var \Symfony\Component\Form\FormError $error
             */
            foreach ($form->getErrors() as $key => $error) {
                $errors[] = $error->getMessage();
            }
        }
    
        return $errors;
    }
}
