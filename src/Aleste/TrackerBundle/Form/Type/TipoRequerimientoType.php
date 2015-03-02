<?php

namespace Aleste\TrackerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TipoRequerimientoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('nombre', null, array('label' => 'Nombre', 'attr' => array('class' => '')))
            //->add('grafos', null, array('label' => 'Grafos', 'attr' => array('class' => '')))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aleste\TrackerBundle\Entity\TipoRequerimiento',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'tiporequerimiento';
    }
}
