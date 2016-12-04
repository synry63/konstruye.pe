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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\PropertyAccess\PropertyAccess;
use FOS\UserBundle\Util\LegacyFormHelper;

class RegistrationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$type = $this->type;
        $builder->add('isWhat', 'choice', array(
            'choices' => array('Negocio' => 'n', 'Particular' => 'p'),
            'choices_as_values' => true,
            'placeholder' => '',
            'constraints' => array(
                new NotBlank()
            ),
            'multiple'      => false,
            'expanded'      => true
        ));
        $builder->add('plainPassword', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\RepeatedType'), array(
            'type' => LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\PasswordType'),
            'options' => array('translation_domain' => 'FOSUserBundle','always_empty' =>false),
            'first_options' => array('label' => 'form.password'),
            'second_options' => array('label' => 'form.password_confirmation'),
            'invalid_message' => 'fos_user.password.mismatch',
        ));

        $formModifier = function ($form, $isWhat = null) {
            //$positions = null === $sport ? array() : $sport->getAvailablePositions();
            if($isWhat=='p'){
                $form->add('nombres','Symfony\Component\Form\Extension\Core\Type\TextType');
                $form->add('apellidos','Symfony\Component\Form\Extension\Core\Type\TextType');
                $form->add('dni','Symfony\Component\Form\Extension\Core\Type\TextType');

            }
            else if($isWhat == 'n'){
                $form->add('nombreEmpresa','Symfony\Component\Form\Extension\Core\Type\TextType',array(
                    'constraints' => array(
                        new NotBlank()
                    )));
                $form->add('ruc','Symfony\Component\Form\Extension\Core\Type\TextType',array(
                    'constraints' => array(
                        new NotBlank()
                    )));
            }

        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();
                $form = $event->getForm();

                if (null === $data) {
                    return false;
                }
                //var_dump($data->getIsWhat());
                //exit;
                //$accessor    = PropertyAccess::createPropertyAccessor();

                //$test        = $accessor->getValue($data, 'isWhat');

                //var_dump($test);
                // this would be your entity, i.e. SportMeetup
                //$data = $event->getData();
                //$form = $event->getForm()->getData();
                //var_dump($data);
                $formModifier($event->getForm(), $data->getIsWhat());
            }
        );

        $builder->get('isWhat')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $event->stopPropagation();
                //var_dump($event->getData());
                $isWhat = $event->getData();
                $formModifier($event->getForm()->getParent(), $isWhat);
                //$state = $event->getData();
                //$provincia_id = array_key_exists('provincia', $state) ? $data['isNegocio'] : null;

                //if(isset($state[1])){

                //    $event->getForm()->add('nombres');
                    /*$event->getForm()->add('apellidos');
                    $event->getForm()->add('dni');*/
                //}
                /*else if($state=='n'){

                }*/
            },90000);

    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';


    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}