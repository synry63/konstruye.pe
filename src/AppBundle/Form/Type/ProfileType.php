<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/13/16
 * Time: 7:39 AM
 */
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombres');
        $builder->add('apellidos');
        $builder->add('dni');
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';


    }

    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}