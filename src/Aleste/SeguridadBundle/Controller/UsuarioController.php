<?php

namespace Aleste\SeguridadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Aleste\SeguridadBundle\Entity\Usuario;
use Aleste\SeguridadBundle\Form\UsuarioType;
use Aleste\CommonBundle\Controller\BaseController;
use Aleste\CommonBundle\Controller\BaseControllerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Usuario controller.
 *
 */
class UsuarioController extends BaseController implements BaseControllerInterface
{

     public function getDQL()
     {
          return "SELECT a FROM SeguridadBundle:Usuario a";
     }
     
     public function getDQLFiltro($filtro)
     {
         return $this->getDoctrine()
                     ->getManager()
                     ->createQueryBuilder()
                     ->add('select','a')
                     ->add('from',$this->getClassName(). ' a')
                     ->add('where','a.usuario LIKE :filtro OR a.apellido LIKE :filtro')
                     ->add('orderBy','a.apellido,a.nombre')
                     ->setParameter('filtro', '%' . $filtro . '%')
                 ;
                 
     }
     
     public function getClassName()
     {
         return 'SeguridadBundle:Usuario';
     }
     
     public function getFiltro()
     {
         return 'nombre';
     }
     
     public function getListRole()
     {
         return 'ROLE_ADMIN';
     }
     
     public function getShowRole()
     {
         return 'ROLE_ADMIN';
     }
 
     public function getCreateRole()
     {
         return 'ROLE_ADMIN';
     }
 
     public function getUpdateRole()
     {
         return 'ROLE_ADMIN';
     }
 
     public function getDeleteRole()
     {
         return 'ROLE_ADMIN';
     }
     
     public function getNewEntity()
     {
         return new Usuario();
     }
     
     public function getNewFormType()
     {
         return new UsuarioType($this->container->get('security.context'));
     }
 
     public function getRouteIndex()
     {
         return 'usuario';
     }
     
     public function getRouteNew()
     {
         return 'usuario_new';
     }
     
     public function getRouteShow()
     {
         return 'usuario_show';
     }
     
     public function getRouteEdit()
     {
         return 'usuario_edit';
     }
     
     public function getRouteDelete()
     {
         return 'usuario_delete';
     }
 
     public function getRouteRevision()
     {
         return 'usuario_revisiones';
     }
     
     public function getEnUso()
     {
         return 'Nombre de Usuario';
     }
     
    /**
     * Displays a form to create a new Usuario entity.
     *
     */
     public function newAction()
     {
          if(!$this->validarSeguridad('ROLE_ADMIN'))
          {
             throw new AccessDeniedException();
          }
        
          $entity = new Usuario();
          $form   = $this->createForm(new UsuarioType($this->container->get('security.context')), $entity);
 
          $perfiles = $this->getDoctrine()->getManager()->getRepository('SeguridadBundle:Rol')->findAll();
          
          return $this->render('SeguridadBundle:Usuario:new.html.twig', array(
                               'entity'   => $entity,
                               'form'     => $form->createView(),
                               'perfiles' => $perfiles,
                              ));
     }
    
     public function createAction(Request $request)
     {
          if(!$this->validarSeguridad('ROLE_ADMIN'))
          {
            throw new AccessDeniedException();
          }
     
          $em = $this->getDoctrine()->getManager();
          
          $entity  = new Usuario();
          
          $form    = $this->createForm(new UsuarioType($this->container->get('security.context')), $entity);
          
          $form->handleRequest($request);
        
          if ($form->isValid())
          {      
               $factory = $this->container->get('security.encoder_factory');
               $encoder = $factory->getEncoder($entity);
               $pwd = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
               $entity->setPassword($pwd);
               $entity->setHerramienta($em->getReference('TrackerBundle:Herramienta',1));
               $em->persist($entity);

               try {
                   $em->flush();
               }
               catch(\Doctrine\DBAL\DBALException $e )
               {
                    if( $e->getCode() === 0 )
                    {
                       $this->get('session')->getFlashBag()->add('error','El '.$this->getEnUso() .' está en uso');
                       return $this->redirect($this->generateUrl('usuario_new'));
                    }
                    else throw $e;
               }
   
               $this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('_datos_actualizados_exito'));
               return $this->redirect($this->generateUrl('usuario_show', array('id' => $entity->getId())));
         
          } // fin del if form->isValid()

          $perfiles = $em->getRepository('SeguridadBundle:Rol')->findAll();        
          
          return $this->render('SeguridadBundle:Usuario:new.html.twig', array(
                                        'entity'   => $entity,
                                        'form'     => $form->createView(),
                                        'perfiles' => $perfiles,
                              ));
    }

     /**
      * Displays a form to edit an existing Usuario entity.
      *
      */
     public function editAction($id)
     {
          if(!$this->validarSeguridad('ROLE_ADMIN'))
          {
            throw new AccessDeniedException();
          }
        
          $em = $this->getDoctrine()->getManager();

          $entity = $em->getRepository('SeguridadBundle:Usuario')->find($id);

          if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
          }

          $editForm = $this->createForm(new UsuarioType($this->container->get('security.context')), $entity);
          $deleteForm = $this->createDeleteForm($id);
         
          $perfiles = $em->getRepository('SeguridadBundle:Rol')->findAll();

          return $this->render('SeguridadBundle:Usuario:edit.html.twig', array(
                                                       'entity'      => $entity,
                                                       'edit_form'   => $editForm->createView(),
                                                       'delete_form' => $deleteForm->createView(),
                                                       'perfiles'    => $perfiles,
                              ));
     }

     /**
      * Edits an existing Usuario entity.
      *
      */
     public function updateAction(Request $request, $id)
     {
          if(!$this->validarSeguridad('ROLE_ADMIN'))
          {
             throw new AccessDeniedException();
          }

          $em = $this->getDoctrine()->getManager();
       
          $entity = $em->getRepository('SeguridadBundle:Usuario')->find($id);
          $oldEntity = clone $entity;
        
          if(!$entity)
          {
             throw $this->createNotFoundException('Unable to find Usuario entity.');
          }

          $editForm   = $this->createForm(new UsuarioType($this->container->get('security.context')), $entity);
        
          $editForm->handleRequest($request);
        
          if ($editForm->isValid())
          {
               if($entity->getPassword()!=NULL)
               {
                    $factory = $this->container->get('security.encoder_factory');
                    $encoder = $factory->getEncoder($entity);
                    $pwd = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
                    $entity->setPassword($pwd);
                }else{
                   $entity->setPassword($oldEntity->getPassword());
                }
                
               try {
                   $em->flush();
               }
                catch(\Doctrine\DBAL\DBALException $e )
               {
                    if( $e->getCode() === 0 )
                    {
                       $this->get('session')->setFlash('error','El usuario esta en uso actualmente');
                       return $this->redirect($this->generateUrl('usuario_edit', array('id' => $id)));
                    }
                    else throw $e;
               }
    
               $this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('_datos_actualizados_exito'));
               return $this->redirect($this->generateUrl('usuario_edit', array('id' => $id)));
          }
          
          $this->get('session')->getFlashBag()->add('error',$this->get('translator')->trans('_error_datos_formulario'));
        
          return $this->render('SeguridadBundle:Usuario:edit.html.twig', array(
                              'entity'      => $entity,
                              'edit_form'   => $editForm->createView(),
                              'perfiles'    => $em->getRepository('SeguridadBundle:UsuarioPerfil')->getOrdered(),
                              ));
    }

    
    public function resetPasswordAction(Request $request)
    {
       
          if($request->getMethod() == 'POST')
          {
              $em = $this->getDoctrine()->getManager();
              $flash = $this->get('session')->getFlashBag();
              $usuario = $this->container->get('security.context')->getToken()->getUser();
    
              $factory = $this->container->get('security.encoder_factory');
              $encoder = $factory->getEncoder($usuario);
    
              $actual = $encoder->encodePassword($request->get('actual'), $usuario->getSalt());
    
              if($usuario->getPassword() != $actual)
              {
                  $flash->add('error','Error en los datos ingresados');
                  return $this->redirect($this->generateUrl('usuario_edit_password'));
              }
              
              if($request->get('nueva') != $request->get('confirmar'))
              {
                  $flash->add('error','La contraseña nueva no coincide con su verificación');
                  return $this->redirect($this->generateUrl('usuario_edit_password'));
              }
              
              $re = '/
                     # Match password with 6-15 chars with letters and digits
                        ^                # Anchor to start of string.
                        (?=.*?[A-Za-z])  # Assert there is at least one letter, AND
                        (?=.*?[0-9])     # Assert there is at least one digit, AND
                        (?=.{6,15}\z)    # Assert the length is from 6 to 15 chars.
                        /x';
              if(!preg_match($re, $request->get('nueva')))
              {
                  $flash->add('error','La contraseña debe poseer entre 6 y 15 caracteres, al menos una mayúsucla, al menos un número y sólo admite caracteres alfanuméricos');
                  return $this->redirect($this->generateUrl('usuario_edit_password'));
              }
              
              $nueva = $encoder->encodePassword($request->get('nueva'), $usuario->getSalt());
              $usuario->setPassword($nueva);
              $em->flush();
              $flash->add('success','Cambio de contraseña realizado con éxito');
              return $this->render('CommonBundle::layout.html.twig');
          }
     
          return $this->render('SeguridadBundle:Usuario:password.reset.html.twig', array());
    }
    
    public function viewAction($id)
    {
        if(!$this->validarSeguridad('ROLE_USER'))
        {
            throw new AccessDeniedException();
        }
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->getClassName())->find($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('Unable to find '. $this->getClassName() . ' entity.');
        }

        return $this->render($this->getClassName() . ':view.html.twig', array(
                                                                              'entity'      => $entity,
                                                                             )
                            );
    }
}