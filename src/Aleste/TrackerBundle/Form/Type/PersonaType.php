<?php

namespace Aleste\TrackerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('nombre', 'text', array('label' => 'Nombre', 'attr' => array('class' => '')))
            ->add('apellido', 'text', array('label' => 'Apellido', 'attr' => array('class' => '')))
            ->add('tipoDocumento', null, array('label' => 'Tipo Documento', 'attr' => array('class' => '')))
            ->add('numeroDoc', 'number', array('label' => 'N°Documento', 'attr' => array('class' => '')))
            ->add('fechaNacimiento', 'date', array( 'label'  => 'Fecha Nacimiento',
                                                    'widget' => 'single_text',
                                                    'format' => 'dd/MM/yyyy',
                                                    'attr'   => array('class' => '')
                                               )
                 )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aleste\TrackerBundle\Entity\Persona',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'persona';
    }
}
