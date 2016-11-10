<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/18/16
 * Time: 9:35 AM
 */
namespace AppBundle\Form\Type;

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
use AppBundle\Form\EventListener\AddCityFieldSubscriber;
use AppBundle\Form\EventListener\AddCountryFieldSubscriber;
use AppBundle\Form\EventListener\AddProvinceFieldSubscriber;

class ProductoType extends AbstractType
{
    private $edit_mode;
    public function __construct($edit_mode = false) {
        $this->edit_mode = $edit_mode;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($this->edit_mode){
            $builder->add('imgFile', 'file',array(
                'constraints' => array(
                    new Image(array(
                        'maxSize'       => '1M',
                        /*'maxWidth'=>250,
                        'maxHeight'=>250,
                        'minWidth'=>250,
                        'minHeight'=>250,*/
                        'allowLandscape'=>true,
                        'allowSquare'=>true,
                        'allowPortrait'=>true,
                        //'mimeTypes'=>array('image/jpeg')
                    ))

                ),
            ));
        }
        else{
            $builder->add('imgFile', 'file',array(
                'constraints' => array(
                    new NotBlank(),
                    new Image(array(
                        'maxSize'       => '1M',
                        /*'maxWidth'=>250,
                        'maxHeight'=>250,
                        'minWidth'=>250,
                        'minHeight'=>250,*/
                        'allowLandscape'=>true,
                        'allowSquare'=>true,
                        'allowPortrait'=>true,
                        //'mimeTypes'=>array('image/jpeg')
                    ))

                ),
            ));
        }

        $builder->add('description','Symfony\Component\Form\Extension\Core\Type\TextareaType',array(
            'constraints' => array(
                new NotBlank(),

            ),
        ));
        $builder->add('nombre','Symfony\Component\Form\Extension\Core\Type\TextType',array(
            'constraints' => array(
                new NotBlank(),

            )
        ));
        $builder->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType');
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Producto',
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