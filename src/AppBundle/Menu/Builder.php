<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/6/16
 * Time: 8:16 AM
 */
namespace AppBundle\Menu;


use Knp\Menu\FactoryInterface;
use Knp\Menu\Renderer\ListRenderer;
use Knp\Menu\Matcher\Matcher;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options){



        $menu = $factory->createItem('root');

        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-left');

        $menu->addChild('home',array(
                'route' => 'homepage',
                'label' => 'Inicio'

            )
        );
        $menu->addChild('constructoras-e-inmobiliarias',array(
                'route' => 'lisdato_seccion',
                'label' => 'constructoras e inmobiliarias',
                'routeParameters' => array('slug_seccion' => 'constructoras-e-inmobiliarias')

            )
        );
        $menu->addChild('compra-venta-y-alquiler-inmuebles',array(
                'route' => 'lisdato_seccion',
                'label' => 'compra venta y alquiler de inmuebles',
                'routeParameters' => array('slug_seccion' => 'compra-venta-y-alquiler-inmuebles')

            )
        );
        $menu->addChild('especialistas-servicios-personales',array(
                'route' => 'lisdato_seccion',
                'label' => 'especialistas servicios personales',
                'routeParameters' => array('slug_seccion' => 'especialistas-servicios-personales')

            )
        );
        $menu->addChild('proveedores',array(
                'route' => 'lisdato_seccion',
                'label' => 'proveedores',
                'routeParameters' => array('slug_seccion' => 'proveedores')

            )
        );

        return $menu;
    }
}