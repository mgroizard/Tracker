<?php

namespace Aleste\TrackerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TipoRequerimientoFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('nombre', 'filter_text', array('label' => 'Nombre'))
            //->add('grafos', 'filter_entity', array('label' => 'Grafos', 'class' => 'Aleste\TrackerBundle\Entity\GrafoTipoRequerimiento', 'attr' => array('class' => 'form-control')))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'Aleste\TrackerBundle\Entity\TipoRequerimiento',
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
        return 'tiporequerimiento_filter';
    }
}
