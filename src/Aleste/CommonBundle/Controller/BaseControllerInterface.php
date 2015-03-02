<?php

namespace Aleste\CommonBundle\Controller;

interface BaseControllerInterface
{
    //nombre completo de la clase NombreBundle:NombreClase
    function getClassName();
    
    //retorna una nueva instancia de Entity
    function getNewEntity();
    
    //retorna una nueva instancia de EntityFormType
    function getNewFormType();
    
    //retorna el ROL que está habilitado para Listados / Filtrados 
    function getListRole();
    
    //retonra el ROL que está habilitado para crear nuevas entidades
    function getCreateRole();
    
    //retorna el ROL que está habilitado para eliminar entidades
    function getDeleteRole();
    
    //retorna el ROL que está habilitado para visualizar entidades
    function getShowRole();
    
    //retorna el ROL que está habilitado para editar entidades
    function getUpdateRole();
    
    //retorna el dato que puede llegar a estar en uso si una entidad cuando es creada
    //ya existe, o si la quiere editar a una entidad y se la quiere modificar igualando a
    //otra ya existente
    function getEnUso();
    
    //retorna la consulta para el listado original
    function getDQL();
    
    //retorna la consulta y aplica el filtro correpondiente a la lista original mostrada
    function getDQLFiltro($filtro);
    
    //retorna el nombre del filtro para recuprar o crear la sesión
    function getFiltro();
    
    //retorna el nombre de la ruta al index de la entidad
    function getRouteIndex();
    
    //retorna el nombre de la ruta para editar la entidad
    function getRouteEdit();

    //retorna el nombre de la ruta para eliminar la entidad
    function getRouteDelete();
    
    //retorna el nombre de la ruta para crear una entidad
    function getRouteNew();
    
    //retorna el nombre de la ruta para visualizar una entidad
    function getRouteShow();
    
    //retorna el nombre de la ruta para ver las revisiones de la entidad
    function getRouteRevision();
}