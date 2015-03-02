<?php

namespace Aleste\UsuarioBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UsuarioRepository
 * 
 */
class UsuarioRepository extends EntityRepository
{
    /**
     * Retorna una query con los usuarios que pueden recibir notificaciones por email
     * @return query
     */
    public function getUsuariosParaEmail(){

        $queryBuilder = $this->createQueryBuilder('u')
                             ->where('u.recibeNotificaciones = true');                             

        return $queryBuilder;
                           
    }   

    /**
     * Retorna los usuarios de una determinada entidad
     * @return Collections
     */
    public function getUsuariosParaEmailByEntidad($entidad, $fetchArray = false){
        $queryBuilder = $this->createQueryBuilder('u')
                             ->innerJoin("u.persona", "p")
                             ->innerJoin("p.entidades", "e")
                             ->where("e.id = :entidad")
                             ->andWhere("u.recibeNotificaciones = true")
                             ->setParameter("entidad", $entidad); 

        if ($fetchArray)
            return $queryBuilder->getQuery()->getArrayResult();
        else
            return $queryBuilder->getQuery()->getResult(); 
                           
    } 
    
}


