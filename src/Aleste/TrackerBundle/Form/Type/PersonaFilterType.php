<?php

namespace Aleste\TrackerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonaFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('apellido', 'filter_text', array('label' => 'Apellido'))
            ->add('nombre', 'filter_text', array('label' => 'Nombre'))
            ->add('fechaNacimiento', 'filter_date', array( 'label'  => 'Fecha Nacimiento',
                                                    'widget' => 'single_text',
                                                    'format' => 'dd/MM/yyyy',
                                                    'attr'   => array('class' => ''),
                                                    'required' => false
                                               )
                 )
            ->add('numeroDoc', 'filter_number')
            ->add('tipoDocumento', 'filter_entity', array('label' => 'Tipo Documento', 'class' => 'Aleste\TrackerBundle\Entity\TipoDocumento', 'attr' => array('class' => 'form-control')))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'Aleste\TrackerBundle\Entity\Persona',
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
        return 'persona_filter';
    }
}
