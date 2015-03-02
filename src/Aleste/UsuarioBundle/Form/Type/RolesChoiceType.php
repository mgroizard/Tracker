<?php

namespace Aleste\UsuarioBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class RolesChoiceType extends AbstractType
{
    
    private $rolesChoices;

    public function __construct(array $rolesChoices)
    {
        $this->rolesChoices = $rolesChoices;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        $resolver->setDefaults(array(
            'choices' => $this->rolesChoices,
        ));
        
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'roles_choice';
    }    

}