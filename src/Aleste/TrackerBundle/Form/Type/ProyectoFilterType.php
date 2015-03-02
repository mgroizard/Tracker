<?php

namespace Aleste\TrackerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProyectoFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('nombre', 'filter_text', array('label' => 'Nombre'))
            //->add('fecha', 'filter_date', array('label' => 'Fecha'))
            //->add('prioridades', 'filter_entity', array('label' => 'Prioridades', 'class' => 'Aleste\TrackerBundle\Entity\Prioridad', 'attr' => array('class' => 'form-control')))
            //->add('grafoTipoRequerimiento', 'filter_entity', array('label' => 'Grafotiporequerimiento', 'class' => 'Aleste\TrackerBundle\Entity\GrafoTipoRequerimiento', 'attr' => array('class' => 'form-control')))
            //->add('herramienta', 'filter_entity', array('label' => 'Herramienta', 'class' => 'Aleste\TrackerBundle\Entity\Herramienta', 'attr' => array('class' => 'form-control')))
            //->add('miembros', 'filter_entity', array('label' => 'Miembros', 'class' => 'Aleste\TrackerBundle\Entity\Periodo', 'attr' => array('class' => 'form-control')))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'Aleste\TrackerBundle\Entity\Proyecto',
            'csrf_protection'   => false,
            'validation_groups' => array('filter'),
            'method'            => 'GET',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'proyecto_filter';
    }
}
