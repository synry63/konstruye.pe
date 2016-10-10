<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/6/16
 * Time: 8:16 AM
 */
namespace AppBundle\Menu;


use AppBundle\Entity\ConstructoraInmobiliaria;
use AppBundle\Entity\Especialista;
use AppBundle\Entity\Inmueble;
use AppBundle\Entity\Proveedor;
use Knp\Menu\FactoryInterface;
use Knp\Menu\Renderer\ListRenderer;
use Knp\Menu\Matcher\Matcher;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    public function menuPanelNegocio(FactoryInterface $factory, array $options){
        $negocio_id = $options['negocio_id'];
        $em = $this->container->get('doctrine')->getManager();
        $negocio = $em->getRepository('AppBundle:Negocio')->find($negocio_id);
        $menu = $factory->createItem('root');

        $menu->addChild('dashboard', array(
            'route' => 'profile_negocios_panel_dashboard',
            'label' => 'Dashboard',
        ));
        $menu->addChild('gestion-negocio', array(
            'uri' => '#',
            'label' => 'Gestion Negocio',
        ));
        $menu['gestion-negocio']->addChild('cambiar-datos', array('label'=>'Cambiar Datos','uri' => '#'));
        $menu['gestion-negocio']->addChild('cambiar-banner', array('label'=>'Cambiar Banner','uri' => '#'));
        $menu['gestion-negocio']->addChild('cambiar-imagenes', array('label'=>'Cambiar Imagenes','uri' => '#'));
        $menu['gestion-negocio']->addChild('ordenar-imagenes', array('label'=>'Ordenar Imagenes','uri' => '#'));
        $menu['gestion-negocio']->addChild('cambiar-ubicacion', array('label'=>'Ubicacion Google Map','uri' => '#'));
        if($negocio instanceof Proveedor){
            $menu->addChild('gestion-producto', array(
                'uri' => '#',
                'label' => 'Gestion de Productos',
            ));
            $menu['gestion-producto']->addChild('ver-productos', array('label'=>'ver Productos','uri' => '#'));
            $menu['gestion-producto']->addChild('agregar-productos', array('label'=>'Agregar Productos','uri' => '#'));
            $menu['gestion-producto']->addChild('ordenar-productos', array('label'=>'Ornedar Productos','uri' => '#'));
        }
        else if($negocio instanceof Especialista || $negocio instanceof ConstructoraInmobiliaria){
            $menu->addChild('gestion-proyectos', array(
                'uri' => '#',
                'label' => 'Gestion de Proyectos',
            ));
            $menu['gestion-producto']->addChild('ver-proyectos', array('label'=>'ver Proyectos','uri' => '#'));
            $menu['gestion-producto']->addChild('agregar-proyecto', array('label'=>'Agregar Proyecto','uri' => '#'));
            $menu['gestion-producto']->addChild('ordenar-proyecto', array('label'=>'Ornedar Proyecto','uri' => '#'));
        }
        else if($negocio instanceof Inmueble){
            $menu->addChild('gestion-inmueble', array(
                'uri' => '#',
                'label' => 'Gestion de Inmueble',
            ));
            $menu['gestion-producto']->addChild('cambiar-datos-principales', array('label'=>'Cambiar Datos principales','uri' => '#'));
            $menu['gestion-producto']->addChild('cambiar-datos-servicio', array('label'=>'Cambiar Datos Servicios','uri' => '#'));
            $menu['gestion-producto']->addChild('cambiar-datos-generales', array('label'=>'Cambiar Datos Generales','uri' => '#'));
        }

        return $menu;
    }
    public function menuProfile(FactoryInterface $factory, array $options){
        $menu = $factory->createItem('root');
        $menu->addChild('about-me',array(
                'route' => 'fos_user_profile_show',
                'label' => 'Mis datos',
                'extras' => array(
                    'icon' => 'fa fa-user'
                )
            )
        );
        $menu->addChild('change-data',array(
                'route' => 'fos_user_profile_edit',
                'label' => 'Cambiar mis datos',
                'extras' => array(
                    'icon' => 'fa fa-edit'
                )

            )
        );
        $menu->addChild('show-comments',array(
                'route' => 'show_profile_comments',
                'label' => 'Mis Comentarios',
                'extras' => array(
                    'icon' => 'fa fa-edit'
                )

            )
        );
        $menu->addChild('show-negocios',array(
                'route' => 'show_profile_negocios',
                'label' => 'Mis Negocios',
                'extras' => array(
                    'icon' => 'fa fa-edit'
                )

            )
        );

        return $menu;
    }

    public function mainMenu(FactoryInterface $factory, array $options){

        $menu = $factory->createItem('root');

        if($options['nav']==true){
            $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-left');
        }

        $menu->addChild('home',array(
                'route' => 'homepage',
                'label' => 'Inicio'

            )
        );
        $menu->addChild('proveedores',array(
                'route' => 'lisdato_seccion',
                'label' => 'proveedores',
                'routeParameters' => array('slug_seccion' => 'proveedores')

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
        $menu->addChild('constructoras-e-inmobiliarias',array(
                'route' => 'lisdato_seccion',
                'label' => 'constructoras e inmobiliarias',
                'routeParameters' => array('slug_seccion' => 'constructoras-e-inmobiliarias')

            )
        );


        return $menu;
    }
    public function footerMenu(FactoryInterface $factory, array $options){

        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-left');
        $menu->addChild('home',array(
                'route' => 'homepage',
                'label' => 'Inicio'

            )
        );
        $menu->addChild('proveedores',array(
                'route' => 'lisdato_seccion',
                'label' => 'proveedores',
                'routeParameters' => array('slug_seccion' => 'proveedores')

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
        $menu->addChild('constructoras-e-inmobiliarias',array(
                'route' => 'lisdato_seccion',
                'label' => 'constructoras e inmobiliarias',
                'routeParameters' => array('slug_seccion' => 'constructoras-e-inmobiliarias')

            )
        );


        $menu->addChild('contactenos',array(
            'route' => 'show_contacto',
            'label' => 'contactenos'

            )
        );
        return $menu;
    }
}