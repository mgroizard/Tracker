<?php

namespace Aleste\UsuarioBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GrupoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('name', null, array('label' => 'Nombre', 'attr' => array('class' => '')))
            //->add('roles', null, array('label' => 'roles', 'attr' => array('class' => '')))
            ->add('roles', 'choice', array('label' => 'Roles', 'required' => true, 'attr' => array('class' => 'form-control'), 'choices' => array( 'ROLE_CONSULTAS' => 'Consula de Documentación', 'ROLE_GESTION' => 'Gestion de documentos'), 'multiple' => true))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aleste\UsuarioBundle\Entity\Grupo',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'grupo';
    }
}
