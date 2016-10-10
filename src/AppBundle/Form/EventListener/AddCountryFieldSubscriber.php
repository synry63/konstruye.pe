<?php
/**
 * Created by PhpStorm.
 * User: patrici-star
 * Date: 10/9/2016
 * Time: 2:50 PM
 */
namespace AppBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddCountryFieldSubscriber implements EventSubscriberInterface
{
    private $propertyPathToCity;

    public function __construct($propertyPathToCity)
    {
        $this->propertyPathToCity = $propertyPathToCity;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT   => 'preSubmit'
        );
    }

    private function addCountryForm($form, $country = null)
    {
        $formOptions = array(
            'class'         => 'AppBundle:Departamento',
            'mapped'        => true,
            'choice_label' => 'nombre',
            //'label'         => 'Departamento',
            //'empty_value'   => 'Departamento',
            'placeholder' => '',
            'attr'          => array(
                'class' => 'country_selector',
            ),
            'constraints' => array(
                new NotBlank(),

            )
        );

        if ($country) {
            $formOptions['data'] = $country;
        }

        $form->add('departamento', 'entity', $formOptions);
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $accessor = PropertyAccess::getPropertyAccessor();

        $city    = $accessor->getValue($data, $this->propertyPathToCity);
        $country = ($city) ? $city->getProvincia()->getDepartamento() : null;

        $this->addCountryForm($form, $country);
    }

    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();

        $this->addCountryForm($form);
    }
}