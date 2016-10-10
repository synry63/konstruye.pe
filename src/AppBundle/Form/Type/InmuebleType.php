<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/18/16
 * Time: 9:35 AM
 */
namespace AppBundle\Form\Type;

use AppBundle\Entity\Departamento;
use AppBundle\Entity\Distrito;
use AppBundle\Entity\Inmueble;
use AppBundle\Entity\Provincia;
use AppBundle\Form\EventListener\AddCityFieldSubscriber;
use AppBundle\Form\EventListener\AddCountryFieldSubscriber;
use AppBundle\Form\EventListener\AddProvinceFieldSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class InmuebleType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('description','Symfony\Component\Form\Extension\Core\Type\TextareaType',array(
            'constraints' => array(
                new NotBlank(),

            ),
        ));
        $builder->add('structure', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType',array(
            'choices'  => array(
                'casa' => 'Casa',
                'departamente' => 'Departamente',
                'Terreno' => 'Terreno/Lote',
                'Oficina' => 'oficina',
            ),
        ));

        $builder->add('nombre','Symfony\Component\Form\Extension\Core\Type\TextType',array(
            'constraints' => array(
                new NotBlank(),

            )
        ));
        $builder->add('direccion','Symfony\Component\Form\Extension\Core\Type\TextType',array(
            'constraints' => array(
                new NotBlank(),

            )
        ));

        $propertyPathToCity  = 'distrito';

        $builder
            ->addEventSubscriber(new AddCityFieldSubscriber($propertyPathToCity))
            ->addEventSubscriber(new AddProvinceFieldSubscriber($propertyPathToCity))
            ->addEventSubscriber(new AddCountryFieldSubscriber($propertyPathToCity))
        ;

        /*$builder->add('departamento',EntityType::class, array(
            'class' => 'AppBundle:Departamento',
            'placeholder' => '',
            'choice_label' => 'nombre',
            'constraints' => array(
                new NotBlank(),

            )
        ));
        $builder->add('provincia',EntityType::class, array(
            'class' => 'AppBundle:Provincia',
            'placeholder' => '',
            'choice_label' => 'nombre',
            'constraints' => array(
                new NotBlank(),

            )
        ));
        $builder->add('distrito', EntityType::class, array(
            'class'       => 'AppBundle:Distrito',
            'choice_label' => 'nombre',
            'placeholder' => '',
            'constraints' => array(
                new NotBlank(),

            )
        ));*/

        $builder->add('telefono','Symfony\Component\Form\Extension\Core\Type\TextType',array(
            'constraints' => array(
                new NotBlank(),

            )
        ));
        $builder->add('web');
        $builder->add('facebookLink');
        $builder->add('twitterLink');
        $builder->add('pinteresLink');
        $builder->add('instagramLink');
        $builder->add('googleLink');
        $builder->add('email', 'Symfony\Component\Form\Extension\Core\Type\EmailType',array(
            'constraints' => array(
                new NotBlank(),

            )
        ));
        $builder->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType');
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Inmueble',
        ));
    }
    /*public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }*/

    /*public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Negocio',
        ));
    }*/
}