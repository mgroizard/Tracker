<?php

namespace Aleste\SeguridadBundle\Controller;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Aleste\CommonBundle\Controller\BaseController;
use Aleste\SeguridadBundle\Entity\Rol;
use Aleste\SeguridadBundle\Form\RolType;

/**
 * Rol controller.
 *
 */
class RolController extends BaseController
{

    public function getFilterField()
    {
        return 'nombre';
    }
    
    public function getDQL()
    {
        return "SELECT a FROM SeguridadBundle:Rol a";
    }
    
    public function getDQLFilter($filtro)
    {
        
        return $this->getDoctrine()
                    ->getManager()
                    ->createQueryBuilder()
                    ->add('select','a')
                    ->add('from',$this->getClassName(). ' a')
                    ->add('where','a.nombre LIKE :filtro')
                    ->add('orderBy','a.nombre')
                    ->setParameter('filtro', '%' . $filtro . '%')
                ;
    }
    
    public function getClassName()
    {
        return 'SeguridadBundle:Rol';
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
        return new Rol();
    }
    
    public function getNewFormType()
    {
        return new RolType($this->container->get('security.context'));
    }

    public function getRouteIndex()
    {
        return 'rol';
    }
    
    public function getRouteNew()
    {
        return 'rol_new';
    }
    
    public function getRouteShow()
    {
        return 'rol_show';
    }
    
    public function getRouteEdit()
    {
        return 'rol_edit';
    }

    public function getRouteDelete()
    {
        return 'rol_delete';
    }

    public function getRouteRevision()
    {
        return 'rol_revisiones';
    }
    
    public function getEnUso()
    {
        return 'Nombre';
    }
}
