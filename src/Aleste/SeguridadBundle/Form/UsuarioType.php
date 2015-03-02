<?php

namespace Aleste\SeguridadBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class UsuarioType extends AbstractType
{
    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('usuario','text', array(
                                          'label'=>'_nombre_usuario',
                                          'attr' => array('widget_col' => 4)
                                          )
                  )
            ->add('password', 'repeated', array(
                                                'required' => false,
                                                'type' => 'password',
                                                'invalid_message' => '_contraseña_no_coincide',
                                                'options' => array('label' => '_contraseña',
                                                                   'always_empty' => false,
                                                                   ),
                                                'attr' => array('widget_col' => 4)
                                          )
                  )
  
            ->add('roles','entity',array('multiple'      =>true,
                                         'expanded'      =>true,
                                         'class'         =>'SeguridadBundle:Rol',
                                         'query_builder' => function(EntityRepository $er)
                                                            {
                                                                return $er->createQueryBuilder('e')->orderBy('e.nombre', 'ASC');
                                                            },
                                         'label' => '_perfiles_usuario',
                                        )
                  )
            ->add('persona','entity',array('class'         =>'TrackerBundle:Persona',
                                           'query_builder' => function(EntityRepository $er)
                                                              {
                                                                return $er->createQueryBuilder('e')->orderBy('e.apellido', 'ASC');
                                                              },
                                           'label'    => '_persona',
                                           'attr'     => array('widget_col' => 4)                                         
                                          )
                  )            
        ;
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aleste\SeguridadBundle\Entity\Usuario',
        ));
    }
    
    protected $sc;
    
    public function __construct($contexto)
    {
        $this->sc = $contexto;    
    }
    
    public function getName()
    {
        return 'loteria_seguridadbundle_usuariotype';
    }
    

}
