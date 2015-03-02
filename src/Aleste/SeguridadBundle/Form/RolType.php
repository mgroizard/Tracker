<?php

namespace Aleste\SeguridadBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class RolType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text', array( 'label' => '_nombre',
                                          'attr' => array('widget_col' => 4)
                                         )
                  )
        ;
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aleste\SeguridadBundle\Entity\Rol',
        ));
    }
    
    public function getName()
    {
        return 'aleste_seguridadbundle_roltype';
    }
    
    private $sc;
    
    public function __construct($securityContext)
    {
      $this->sc = $securityContext;    
    }
}
