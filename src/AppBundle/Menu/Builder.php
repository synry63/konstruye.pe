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
use AppBundle\Form\Type\InmuebleType;
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
            'extras' => array(
                'icon' => 'fa fa-tachometer'
            )
        ));
        $menu->addChild('gestion-negocio', array(
            'uri' => '#',
            'label' => 'Gestion Negocio',
            'extras' => array(
                'icon' => 'fa fa-briefcase'
            )
        ));
        $menu['gestion-negocio']->addChild('cambiar-datos', array(
            'label'=>'Cambiar Datos',
            'route' => 'profile_negocios_panel_gestion_negocio_cambiar_datos',
            'extras' => array(
                'icon' => 'fa fa-pencil-square-o'
            )
        ));

        $menu['gestion-negocio']->addChild('cambiar-banner', array(
            'label'=>'Cambiar Banner',
            'route' => 'profile_negocios_panel_gestion_negocio_cambiar_banner',
            'extras' => array(
                'icon' => 'fa fa-picture-o'
            )
        ));
        if($negocio instanceof Inmueble){
            $menu['gestion-negocio']->addChild('cambiar-anunciante', array(
                'label'=>'Cambiar Anunciante',
                'route' => 'profile_negocios_panel_gestion_negocio_cambiar_anunciante',
                'extras' => array(
                    'icon' => 'fa fa-picture-o'
                )
            ));
        }
        else{
            $menu['gestion-negocio']->addChild('cambiar-logo', array(
                'label'=>'Cambiar Logo',
                'route' => 'profile_negocios_panel_gestion_negocio_cambiar_logo',
                'extras' => array(
                    'icon' => 'fa fa-picture-o'
                )
            ));
        }

        $menu['gestion-negocio']->addChild('cambiar-imagenes', array(
            'label'=>'Cambiar Imagenes',
            'route' => 'profile_negocios_panel_gestion_negocio_cambiar_fotos',
            'extras' => array(
                'icon' => 'fa fa-picture-o'
            )
        ));
        $menu['gestion-negocio']->addChild('ordenar-imagenes', array(
            'label'=>'Ordenar Imagenes',
            'route' => 'profile_negocios_panel_gestion_negocio_list_fotos',
            'extras' => array(
                'icon' => 'fa fa-plus'
            )
        ));
        $menu['gestion-negocio']->addChild('cambiar-ubicacion', array(
            'label'=>'Ubicacion Google Map',
            'route' => 'profile_negocios_panel_gestion_negocio_cambiar_mapa',
            'extras' => array(
                'icon' => 'fa fa-map'
            )
        ));
        if($negocio instanceof Proveedor){
            $menu->addChild('gestion-producto', array(
                'uri' => '#',
                'label' => 'Gestion de Productos',
                'extras' => array(
                    'icon' => 'fa fa-shopping-basket'
                )
            ));
            $menu['gestion-producto']->addChild('ver-productos', array(
                'label'=>'ver Productos',
                'route' => 'profile_negocios_panel_gestion_negocio_ver_productos',
                'extras' => array(
                    'icon' => 'fa fa-shopping-basket'
                )
            ));
            $menu['gestion-producto']->addChild('agregar-productos', array(
                'label'=>'Agregar Productos',
                'route' => 'profile_negocios_panel_gestion_negocio_add_producto',
                'extras' => array(
                    'icon' => 'fa fa-plus'
                )
            ));
            $menu['gestion-producto']->addChild('ordenar-productos', array(
                'label'=>'Ornedar Productos',
                'route' => 'profile_negocios_panel_gestion_negocio_list_productos',
                'extras' => array(
                    'icon' => 'fa fa-sort'
                )
            ));
        }
        else if($negocio instanceof ConstructoraInmobiliaria){
            $menu->addChild('gestion-proyectos', array(
                'uri' => '#',
                'label' => 'Gestion de Proyectos',
                'extras' => array(
                    'icon' => 'fa fa-clipboard'
                )
            ));
            $menu['gestion-proyectos']->addChild('ver-proyectos', array(
                'label'=>'ver Proyectos',
                'route' => 'profile_negocios_panel_gestion_negocio_ver_inmuebles',
                'extras' => array(
                    'icon' => 'fa fa-building'
                )
            ));
            $menu['gestion-proyectos']->addChild('agregar-proyecto', array(
                'label'=>'Agregar Proyecto',
                'route' => 'profile_negocios_panel_gestion_negocio_add_inmueble',
                'extras' => array(
                    'icon' => 'fa fa-plus'
                )
            ));
            $menu['gestion-proyectos']->addChild('ordenar-proyecto', array(
                'label'=>'Ornedar Proyecto',
                'route' => 'profile_negocios_panel_gestion_negocio_list_inmuebles',
                'extras' => array(
                    'icon' => 'fa fa-sort'
                )
            ));
        }
        else if($negocio instanceof Especialista){
            $menu->addChild('gestion-proyectos', array(
                'uri' => '#',
                'label' => 'Gestion de Proyectos',
                'extras' => array(
                    'icon' => 'fa fa-clipboard'
                )
            ));
            $menu['gestion-proyectos']->addChild('ver-proyectos', array(
                'label'=>'ver Proyectos',
                'route' => 'profile_negocios_panel_gestion_negocio_ver_proyectos',
                'extras' => array(
                    'icon' => 'fa fa-building'
                )

            ));
            $menu['gestion-proyectos']->addChild('agregar-proyecto', array(
                'label'=>'Agregar Proyecto',
                'route' => 'profile_negocios_panel_gestion_negocio_add_proyecto',
                'extras' => array(
                    'icon' => 'fa fa-plus'
                )

            ));
            $menu['gestion-proyectos']->addChild('ordenar-proyecto', array(
                'label'=>'Ornedar Proyecto',
                'route' => 'profile_negocios_panel_gestion_negocio_list_proyectos',
                'extras' => array(
                    'icon' => 'fa fa-sort'
                )
            ));
        }
        else if($negocio instanceof Inmueble){
            $menu->addChild('gestion-inmueble', array(
                'uri' => '#',
                'label' => 'Gestion de Inmueble',
            ));
            //$menu['gestion-inmueble']->addChild('cambiar-datos-principales', array('label'=>'Cambiar Datos principales','uri' => '#'));
            $menu['gestion-inmueble']->addChild('cambiar-datos-servicio', array('label'=>'Ver Inmueble Servicios','route' => 'profile_negocios_panel_gestion_negocio_ver_inmuebles_servicios'));
            $menu['gestion-inmueble']->addChild('cambiar-datos-generales', array('label'=>'Ver Inmueble Datos Generales','route' => 'profile_negocios_panel_gestion_negocio_ver_inmuebles_generales'));
        }

        return $menu;
    }
    public function menuProfileSettings(FactoryInterface $factory, array $options){
        $menu = $factory->createItem('root');
        $menu->addChild('change-data',array(
                'route' => 'fos_user_profile_edit',
                'label' => 'Cambiar mis datos',
                'extras' => array(
                    'icon' => 'fa fa-edit'
                )

            )
        );
        $menu->addChild('change-password',array(
                'route' => 'fos_user_change_password',
                'label' => 'Cambiar mi contraseña',
                'extras' => array(
                    'icon' => 'fa fa-lock'
                )
            )
        );
        return $menu;
    }
    public function menuProfile(FactoryInterface $factory, array $options){
        $menu = $factory->createItem('root');
        /*$menu->addChild('about-me',array(
                'route' => 'fos_user_profile_show',
                'label' => 'Mis datos',
                'extras' => array(
                    'icon' => 'fa fa-user'
                )
            )
        );*/
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
        $menu->addChild('faq',array(
                'route' => 'show_faq',
                'label' => 'FAQ'

            )
        );
        return $menu;
    }
}