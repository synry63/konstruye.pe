<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/16/16
 * Time: 2:30 PM
 */
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ComentarioProductoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo')
            ->add('comentario')
            ->add('nota','Symfony\Component\Form\Extension\Core\Type\HiddenType', array(
                'data' => '0'))
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType')
        ;
    }
}