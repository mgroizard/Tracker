<?php

namespace Aleste\UsuarioBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsuarioFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                    
            
            ->add('username', 'filter_text', array('label' => 'Usuario', 'attr' => array('class' => 'form-control')))            
            ->add('email', 'filter_text', array('label' => 'Email', 'attr' => array('class' => 'form-control')))            
            ->add('enabled', 'filter_boolean', array('label' => 'Habilitado', 'attr'=>array('class' => 'form-control')))                                    
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'Aleste\UsuarioBundle\Entity\Usuario',
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
        return 'usuario_filter';
    }
}
