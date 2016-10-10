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
use AppBundle\Entity\Provincia;
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
        $builder->add('departamento',EntityType::class, array(
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

        $formModifierMain = function ($form, Departamento $departamento = null) {

            $provincias = null === $departamento ? array() : $departamento->getProvincias();

            $form->add('provincia', EntityType::class, array(
                'class'       => 'AppBundle:Provincia',
                'choice_label' => 'nombre',
                'placeholder' => '',
                'choices'     => $provincias,
            ));
        };
        $formModifierTest = function ($form,$obj = null) {
            if($obj instanceof Departamento){
                $provincias = null === $obj ? array() : $obj->getProvincias();
                $form->add('provincia', EntityType::class, array(
                    'class'       => 'AppBundle:Provincia',
                    'choice_label' => 'nombre',
                    'placeholder' => '',
                    'choices'     => $provincias,
                ));
            }
            elseif($obj instanceof Provincia){
                $distritos = null === $obj ? array() : $obj->getDistritos();

                $form->add('distrito', EntityType::class, array(
                    'class'       => 'AppBundle:Distrito',
                    'choice_label' => 'nombre',
                    'placeholder' => '',
                    'choices'     => $distritos,
                ));
            }

        };
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                //$form = $event->getForm();
                $inmueble = $event->getData();
                $provincia = $inmueble->getProvincia();
 //               var_dump($inmueble->getProvincia()->getNombre());
                //var_dump('here');

                $distritos = null === $provincia ? array() : $provincia->getDistritos();

                $event->getForm()->add('distrito', EntityType::class, array(
                    'class'       => 'AppBundle:Distrito',
                    'choice_label' => 'nombre',
                    'placeholder' => '',
                    'choices'     => array(),
                ));
                //$formModifier($event->getForm(), $inmueble->getProvincia());



                /*$inmueble = $event->getData();

                //var_dump($inmueble);
                //exit;
                $provincia = $inmueble->getProvincia();
                $distritos = null === $provincia ? array() : $provincia->getDistritos();


                $form->add('distrito', EntityType::class, array(
                    'class'       => 'AppBundle:Distrito',
                    'placeholder' => '',
                    'choices'     => $distritos,
                ));*/
            }
        );
        /*$builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifierTest) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $obj = $event->getForm()->getData();
                if($obj->getProvincia() instanceof Provincia){
                    // since we've added the listener to the child, we'll have to pass on
                    // the parent to the callback functions!
                    $formModifierTest($event->getForm(), $obj);
                }
                else if($obj->getDepartamento() instanceof Departamento){
                    $formModifierTest($event->getForm(), $obj);
                }

            }
        );*/
        $builder->get('provincia')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifierTest) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $provincia = $event->getForm()->getData();
                if($provincia instanceof Provincia){
                    // since we've added the listener to the child, we'll have to pass on
                    // the parent to the callback functions!
                    $formModifierTest($event->getForm()->getParent(), $provincia);
                }

            }
        );
        $builder->get('departamento')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifierTest) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $departamento = $event->getForm()->getData();
                if($departamento instanceof Departamento){
                    $formModifierTest($event->getForm()->getParent(), $departamento);
                }
                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!

            }
        );

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