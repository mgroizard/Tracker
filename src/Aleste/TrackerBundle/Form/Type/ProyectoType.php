<?php

namespace Aleste\TrackerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProyectoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('nombre', null, array('label' => 'Nombre', 'attr' => array('class' => '')))
            //->add('fecha', null, array('label' => 'Fecha', 'attr' => array('class' => '')))
            //->add('prioridades', null, array('label' => 'Prioridades', 'attr' => array('class' => '')))
            //->add('grafoTipoRequerimiento', null, array('label' => 'Grafotiporequerimiento', 'attr' => array('class' => '')))
            //->add('miembros', null, array('label' => 'Miembros', 'attr' => array('class' => '')))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aleste\TrackerBundle\Entity\Proyecto',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'proyecto';
    }
}
