<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/12/16
 * Time: 12:27 PM
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

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'Symfony\Component\Form\Extension\Core\Type\TextType', array(
                'attr' => array(
                    'placeholder' => 'What\'s your name?',
                    'pattern'     => '.{2,}' //minlength
                ),
                'constraints' => array(
                    new NotBlank(array('message' => 'Recuerda ingresar tu nombre.')),

                ),

            ))
            ->add('email', 'email', array(
                'attr' => array(
                    'placeholder' => 'So I can get back to you.',

                ),
                'constraints' => array(
                    new NotBlank(array('message' => 'Recuerda ingresar tu email.')),
                    new Email(array('message' => 'Dirección de correo electrónico no válida.'))

                ),
            ))
            ->add('subject', 'text', array(
                'attr' => array(
                    'placeholder' => 'The subject of your message.',
                    'pattern'     => '.{3,}' //minlength
                ),
                'constraints' => array(
                    new NotBlank(array('message' => 'Recuerda ingresar el asunto de tu mensaje.')),


                ),
            ))
            ->add('tel', 'text', array(
                'attr' => array(
                    'placeholder' => 'Ingrese un telefono.',
                    'pattern'     => '.{3,}' //minlength
                ),
                'constraints' => array(
                    new NotBlank(array('message' => 'Recuerda ingresar tu número de teléfono.')),


                ),
            ))
            ->add('message', 'textarea', array(
                'attr' => array(
                    'cols' => 90,
                    'rows' => 10,
                    'placeholder' => 'And your message to me...'
                ),
                'constraints' => array(
                    new NotBlank(array('message' => 'Recuerda ingresar tu mensaje.')),


                ),
            ))
            ->add('send', 'Symfony\Component\Form\Extension\Core\Type\SubmitType'
            );

    }
    public function getName()
    {
        return 'contact';
    }
}