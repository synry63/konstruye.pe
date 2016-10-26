<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/25/16
 * Time: 8:39 AM
 */
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class GoogleMapType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ubicacion','Symfony\Component\Form\Extension\Core\Type\HiddenType',array(
                'data' => ' ',
                'constraints' => array(
                    new NotBlank(),
                ),
            ))
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType')
        ;
    }
    public function getName()
    {
        return 'googlemap';
    }
}