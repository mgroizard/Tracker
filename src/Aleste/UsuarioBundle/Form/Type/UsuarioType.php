<?php

namespace Aleste\UsuarioBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Doctrine\ORM\EntityRepository;

class UsuarioType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        

        $builder
                    
            ->add('apellido', null, array('label' => 'Apellido', 'mapped' => false, 'required' => true, 'constraints' => array(new NotBlank()), 'attr' => array('class' => '')))
            ->add('nombre', null, array('label' => 'Nombre', 'mapped' => false, 'required' => true, 'constraints' => array(new NotBlank()), 'attr' => array('class' => '')))            
            ->add('tipoDocumento', 'entity', array('class' => 'GestionBundle:TipoDocumentoIdentidad', 'label' => 'Tipo', 'mapped' => false, 'required' => true, 'constraints' => array(new NotBlank()), 'attr' => array('class' => 'form-control')))            
            ->add('nroDoc', null, array('label' => 'Documento.', 'mapped' => false, 'required' => true, 'constraints' => array(new NotBlank()), 'attr' => array('class' => '')))            
            ->add('sexo', 'entity', array('class' => 'GestionBundle:Sexo', 'label' => 'Sexo.', 'mapped' => false, 'required' => true, 'constraints' => array(new NotBlank()), 'attr' => array('class' => 'form-control')))            
            ->add('username', null, array('label' => 'Usuario', 'constraints' => array(new NotBlank()), 'attr' => array('class' => '')))
            ->add('email', null, array('label' => 'Email', 'constraints' => array(new NotBlank(), new Email()), 'attr' => array('class' => '')))            
            ->add('enabled', null, array('label' => 'Habilitado', 'required' => false, 'attr' => array('class' => '')))                   
            ->add('recibeNotificaciones', null, array('label' => 'Notificar', 'required' => false, 'attr' => array('class' => '')))                   
            ->add('roles', 'roles_choice', array('label' => 'Roles', 'required' => false, 'multiple' => true, 'attr' => array('class' => 'form-control')))
            ->add('grupos', null, array('label' => 'Grupo', 'required' => true, 'attr' => array('class' => 'form-control')))
            ->add('coordinaciones', 'entity', array('label' => 'Coordinaciones', 
                                                    'class'=>'GestionBundle:Entidad', 
                                                    'query_builder' => function(EntityRepository $er) {
                                                     return $er->getQueryTipoEntidadCoordinacion();
                                            },
                                                    'mapped' => false, 'multiple' => true, 'expanded' => false, 'required' => true, 'attr' => array('class' => 'form-control')))
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

            $data = $event->getData();
            $form = $event->getForm();

            if (!$data || $data->getId() == '') {
               $form->add('password', 'password', array('label' => 'Contraseña inicial', 'required' => true, 'attr' => array('class' => '')));
            }

        });


    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aleste\UsuarioBundle\Entity\Usuario',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'usuario';
    }
}
