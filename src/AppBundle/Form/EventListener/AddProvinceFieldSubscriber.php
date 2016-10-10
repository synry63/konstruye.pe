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
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\EntityRepository;

class AddProvinceFieldSubscriber implements EventSubscriberInterface
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

    private function addProvinceForm($form, $country_id, $province = null)
    {

        $formOptions = array(
            'class'         => 'AppBundle:Provincia',
            'empty_value'   => 'Provincia',
            'label'         => 'Provincia',
            'choice_label' => 'nombre',
            'mapped'        => true,
            'attr'          => array(
                'class' => 'province_selector',
            ),
            'query_builder' => function (EntityRepository $repository) use ($country_id) {
                $qb = $repository->createQueryBuilder('provincia')
                    ->innerJoin('provincia.departamento', 'departamento')
                    ->where('departamento.id = :departamento')
                    ->setParameter('departamento', $country_id)
                ;

                return $qb;
            }
        );

        if ($province) {
            $formOptions['data'] = $province;
        }

        $form->add('provincia','entity', $formOptions);
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $accessor = PropertyAccess::getPropertyAccessor();

        $city        = $accessor->getValue($data, $this->propertyPathToCity);
        $province    = ($city) ? $city->getProvincia() : null;
        $country_id  = ($province) ? $province->getDepartamento()->getId() : null;

        $this->addProvinceForm($form, $country_id, $province);
    }

    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        $country_id = array_key_exists('departamento', $data) ? $data['departamento'] : null;
        $this->addProvinceForm($form, $country_id);
    }
}