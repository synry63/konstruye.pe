<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/17/16
 * Time: 1:44 PM
 */
namespace AppBundle\Controller;

use AppBundle\Entity\ComentarioNegocio;
use AppBundle\Entity\ConstructoraInmobiliaria;
use AppBundle\Entity\Especialista;
use AppBundle\Entity\Inmueble;
use AppBundle\Entity\Operacion;
use AppBundle\Entity\Proveedor;
use AppBundle\Entity\Structure;
use AppBundle\Entity\User;
use AppBundle\Form\Type\ComentarioNegocioType;
use AppBundle\Form\Type\CotizacionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Ivory\GoogleMap\Overlays\Marker;
use Ivory\GoogleMap\Overlays\Animation;
use Ivory\GoogleMap\Places\Autocomplete;
use Ivory\GoogleMap\Places\AutocompleteComponentRestriction;
use Ivory\GoogleMap\Places\AutocompleteType;
use Ivory\GoogleMap\Helper\Places\AutocompleteHelper;
use Ivory\GoogleMap\Overlays\InfoWindow;
use Ivory\GoogleMap\Events\MouseEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class NegocioController extends Controller
{

    public function listadoSeccionAction(Request $request,$slug_seccion,$page)
    {

        $twig = 'layout_categorias.html.twig';
        $renderOut = array();
        if($slug_seccion=="constructoras-e-inmobiliarias"){
            $slug_categoria = $request->query->get('slug_categoria');
            $negocios = $this->getDoctrine()->getRepository('AppBundle:ConstructoraInmobiliaria')->getNegocios($slug_categoria);
            $categorias_main = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren('constructura-inmobiliaria');
            $renderOut['negocios'] = $negocios;
            $out = $this->get('menu_filter')->menuMain($request->get('_route'),'slug_seccion',$slug_seccion,$categorias_main);
            if($slug_categoria!=null){
                $categorias_child = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren($slug_categoria);
                $renderOut['sub_categorias'] = $categorias_child;
                $out = $this->get('menu_filter')->menuChild($request->get('_route'),'slug_seccion',$slug_seccion,$categorias_main,$slug_categoria);

            }

            $renderOut['out'] = $out;
        }
        else if($slug_seccion=="compra-venta-y-alquiler-inmuebles"){
            $dormi = $request->query->get('dormi');
            $lugar = $request->query->get('lugar');
            $precio_min = $request->query->get('preciomin');
            $precio_max = $request->query->get('preciomax');
            $servicio_id = $request->query->get('servicio');
            $general_id = $request->query->get('general');
            $structure_id = $request->query->get('structure');
            $operacion_id = $request->query->get('operacion');
            if($lugar!=null){
                $lugar_split = explode(',',$lugar);
                if(count($lugar_split)==3){
                    $arr_lugar = array('Distrito','Provincia','Departamento');
                    $arr_lugar_entity = array();
                }
                else if(count($lugar_split)==2){
                    $arr_lugar = array('Provincia','Departamento');
                    $arr_lugar_entity = array();
                }
                else if(count($lugar_split)==1){
                    $arr_lugar = array('Departamento');
                    $arr_lugar_entity = array();
                }
                for($i=0;$i<count($lugar_split);$i++){
                    $r= $this->getDoctrine()->getRepository('AppBundle:'.$arr_lugar[$i])->findOneBy(
                        array('nombre'=>trim($lugar_split[$i]))
                    );
                    $arr_lugar_entity[$i] = $r;
                }
                $lugar = $arr_lugar_entity;
            }
            $negocios = $this->getDoctrine()->getRepository('AppBundle:Inmueble')->getNegocios($dormi,$lugar,$precio_min,$precio_max,$servicio_id,$general_id,$structure_id,$operacion_id);
            $maxDormiWithCount = $this->getDoctrine()->getRepository('AppBundle:Inmueble')->getMaxDormiWithCount($structure_id,$operacion_id,$lugar);
            $servicios = $this->getDoctrine()->getRepository('AppBundle:Inmueble')->getServiciosWithInmueblesCount($structure_id,$operacion_id,$lugar);
            $generales = $this->getDoctrine()->getRepository('AppBundle:Inmueble')->getGeneralesWithInmueblesCount($structure_id,$operacion_id,$lugar);
            //if($precio_max==null && $precio_min ==null){ // if params price not set only
                $precio_max_default = $this->getDoctrine()->getRepository('AppBundle:Inmueble')->getMaxPrice($structure_id,$operacion_id,$lugar)['maxPrice'];
                $precio_min_default = $this->getDoctrine()->getRepository('AppBundle:Inmueble')->getMinPrice($structure_id,$operacion_id,$lugar)['minPrice'];
                $renderOut['precio_max_default'] = $precio_max_default;
                $renderOut['precio_min_default'] = $precio_min_default;
            //}
            /*$servicios = $this->getDoctrine()->getRepository('AppBundle:Servicio')->findBy(
                array(),
                array('nombre'=>'ASC')
            );*/
            /*$generales = $this->getDoctrine()->getRepository('AppBundle:General')->findBy(
                array(),
                array('nombre'=>'ASC')
            );*/
            $structures = $this->getDoctrine()->getRepository('AppBundle:Structure')->findBy(
                array(),
                array('nombre'=>'ASC')
            );
            $temp_s = new Structure();
            $temp_s->setId(0);
            $temp_s->setNombre('Todos');
            array_unshift($structures, $temp_s);
            //$aa = $this->getDoctrine()->getRepository('AppBundle:Inmueble')->getServiciosWithCount();
            //var_dump($aa);
            $operaciones = $this->getDoctrine()->getRepository('AppBundle:Operacion')->findBy(
                array(),
                array('nombre'=>'ASC')
            );
            $temp_o = new Operacion();
            $temp_o->setId(0);
            $temp_o->setNombre('Todos');
            array_unshift($operaciones, $temp_o);
            //var_dump($maxDormiWithCount);
            //$categorias = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren('inmueble');
            $renderOut['negocios'] = $negocios;
            $renderOut['ubicacion'] = $lugar;
            $renderOut['servicios'] = $servicios;
            $renderOut['structures'] = $structures;
            $renderOut['generales'] = $generales;
            $renderOut['operaciones'] = $operaciones;
            $renderOut['domitorios'] = $maxDormiWithCount;
            //$renderOut['categorias_hijas'] = $categorias;

            $twig = 'layout_compra_y_venta.html.twig';

        }
        else if($slug_seccion=="especialistas-servicios-personales"){

            $slug_categoria = $request->query->get('slug_categoria');
            $negocios = $this->getDoctrine()->getRepository('AppBundle:Especialista')->getNegocios($slug_categoria);
            $categorias_main = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren('especialista');

            $renderOut['negocios'] = $negocios;
            $out = $this->get('menu_filter')->menuMain($request->get('_route'),'slug_seccion',$slug_seccion,$categorias_main);
            if($slug_categoria!=null){
                $categorias_child = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren($slug_categoria);
                $renderOut['sub_categorias'] = $categorias_child;
                $out = $this->get('menu_filter')->menuChild($request->get('_route'),'slug_seccion',$slug_seccion,$categorias_main,$slug_categoria);

            }

            $renderOut['out'] = $out;
        }
        else{ // proveedores
            $slug_categoria = $request->query->get('slug_categoria');
            $negocios = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->getNegocios($slug_categoria);
            $categorias_main = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren('proveedor');

            $renderOut['negocios'] = $negocios;
            $out = $this->get('menu_filter')->menuMain($request->get('_route'),'slug_seccion',$slug_seccion,$categorias_main);
            if($slug_categoria!=null){
                $categorias_child = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren($slug_categoria);
                $renderOut['sub_categorias'] = $categorias_child;
                $out = $this->get('menu_filter')->menuChild($request->get('_route'),'slug_seccion',$slug_seccion,$categorias_main,$slug_categoria);

            }

            $renderOut['out'] = $out;
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $renderOut['negocios'],
            $page,
            9
        );
        $renderOut['negocios'] = $pagination;
        return $this->render($twig,$renderOut);
    }


    private function initGoogleMap($negocio,$slug_site = NULL){
        $map = $this->get('ivory_google_map.map');
        //$map->setApiKey('AIzaSyDSEEQgKuvd-sphCZGDm2nhmAFi9DUXlEk');
        //$map->setLanguage($this->get('request')->getLocale());
        $map->setCenter($negocio->getGoogleMapLat(), $negocio->getGoogleMapLng(), true);
        $map->setAsync(true);
        // Sets the zoom
        $map->setAutoZoom(false);
        $map->setMapOption('zoom', 16);
        $map->setMapOption('scrollwheel', false);
        //$map->setBound(-2.1, -3.9, 2.6, 1.4, true, true);
        $map->setStylesheetOption('width', '100%');
        $map->setStylesheetOption('height', '400px');
        $marker = new Marker();
        //$marker->setIcon('http://theeventplanner.pe/images/markers/'.$slug_site.'_marker.png');
        // Sets your marker animation
        //$marker->setAnimation(Animation::DROP);

        //$marker->setAnimation('bounce');
        $marker->setPosition($negocio->getGoogleMapLat(), $negocio->getGoogleMapLng(), true);
        // Add your marker to the map
        $map->addMarker($marker);

// Configure your info window options

        $infoWindow = new InfoWindow();
        $infoWindow->setPrefixJavascriptVariable('info_window_');
        $infoWindow->setPosition(0, 0, true);
        $infoWindow->setPixelOffset(1.1, 2.1, 'px', 'pt');
        $text = $negocio->getDireccion().'<br/>';
        $infoWindow->setContent('<p class="info-window">'
            .$text.'</p>');
        $infoWindow->setOpen(false);
        $infoWindow->setAutoOpen(true);
        $infoWindow->setOpenEvent(MouseEvent::CLICK);
        $infoWindow->setAutoClose(false);
        $infoWindow->setOption('disableAutoPan', true);
        $infoWindow->setOption('zIndex', 10);
        $infoWindow->setOptions(array(
            'disableAutoPan' => true,
            'zIndex'         => 10,
        ));

        $marker->setInfoWindow($infoWindow);

        return $map;
    }
    public function buscarListaNegociosAction(Request $request,$slug_seccion)
    {
        $out = array();
        $search = $request->query->get('q');
        if($slug_seccion=="todos"){
            $users = $this->getDoctrine()->getRepository('AppBundle:User')->searchAllUsers($search);
            $negocios = $this->getDoctrine()->getRepository('AppBundle:Negocio')->searchAllNegocios($search);
            $merged = array_merge($users, $negocios);
            foreach ($merged as $value){
                $obj = new \stdClass();
                $label = '';

                if($value instanceof User){
                    $obj->type = 'Perfil';
                    $obj->instance = 'User';
                    $obj->seccion = 'todos';
                    if(!empty($value->getNombreEmpresa())){
                        $label = $value->getNombreEmpresa();
                    }
                    else{
                        $label = $value->getNombres().' '.$value->getApellidos();
                    }
                }
                else if($value instanceof Proveedor){
                    $obj->type = 'Proveedor';
                    $obj->instance = 'Proveedor';
                    $obj->seccion = 'proveedores';
                    $label = $value->getNombre();
                }
                else if($value instanceof Especialista){
                    $obj->type = 'Especialista';
                    $obj->instance = 'Especialista';
                    $obj->seccion = 'especialistas-servicios-personales';
                    $label = $value->getNombre();
                }
                else if($value instanceof ConstructoraInmobiliaria){
                    $obj->type = 'Constructura e Inmobiliaria';
                    $obj->instance = 'ConstructoraInmobiliaria';
                    $obj->seccion = 'constructoras-e-inmobiliarias';
                    $label = $value->getNombre();
                }
                else if($value instanceof Inmueble){
                    continue;
                }
                $obj->identifier = $value->getId();
                $obj->label = $label;
                $out[] = $obj;
            }
        }
        else if($slug_seccion=="constructoras-e-inmobiliarias"){
            $negocios = $this->getDoctrine()->getRepository('AppBundle:ConstructoraInmobiliaria')->searchNegociosNames($search);
            foreach ($negocios as $value){
                $obj = new \stdClass();
                $obj->seccion = $slug_seccion;
                $obj->type = 'Constructura e Inmobiliaria';
                $obj->instance = 'ConstructoraInmobiliaria';
                $obj->identifier = $value->getId();
                $obj->label = $value->getNombre();
                $out[] = $obj;
            }

        }
        else if($slug_seccion=="especialistas-servicios-personales"){
            $negocios = $this->getDoctrine()->getRepository('AppBundle:Especialista')->searchNegociosNames($search);
            foreach ($negocios as $value){
                $obj = new \stdClass();
                $obj->seccion = $slug_seccion;
                $obj->type = 'Especialista';
                $obj->instance = 'Especialista';
                $obj->identifier = $value->getId();
                $obj->label = $value->getNombre();
                $out[] = $obj;
            }
        }
        else if($slug_seccion=="proveedores"){
            $negocios = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->searchNegociosNames($search);
            foreach ($negocios as $value){
                $obj = new \stdClass();
                $obj->seccion = $slug_seccion;
                $obj->type = 'Proveedor';
                $obj->instance = 'Proveedor';
                $obj->identifier = $value->getId();
                $obj->label = $value->getNombre();
                $out[] = $obj;
            }
        }

        $response = new JsonResponse($out);

        return $response;
    }
    public function contizacionAction($slug_negocio,Request $request)
    {
        //if ($request->isXmlHttpRequest()) {
            $negocio = $this->getDoctrine()->getRepository('AppBundle:Negocio')->findOneBy(array('slug'=>$slug_negocio));

            $form = $this->createForm(new CotizacionType(),NULL,array(
                'action' => $this->generateUrl('negocio_solicitar_cotizacion',array('slug_negocio'=>$slug_negocio)),
                'method' => 'POST',
            ));
            $form->handleRequest($request);

            if ($form->isSubmitted()) {

                if($form->isValid()){

                    $data = $form->getData();

                    // send email to negocio
                    $message = \Swift_Message::newInstance();
                    $imgUrl = $message->embed(\Swift_Image::fromPath('http://i.imgur.com/DE6vZW0.png'));
                    $message->setSubject('Konstruye - Formulario Cotizacion - Negocio')
                        ->setFrom(array('sistema@konstruye.pe'=>'Konstruye'))
                        ->setTo($negocio->getEmail())
                        ->setBody(
                            $this->renderView(
                                'emails/cotizacion_negocio.html.twig',
                                array(
                                    'nombre' => $data['name'],
                                    'email' => $data['email'],
                                    'asunto' => $data['subject'],
                                    'negocio'=>$negocio,
                                    'telefono' => $data['tel'],
                                    'mensaje' => $data['message'],
                                    'logo'=>$imgUrl
                                )
                            )
                        );

                    // send email to user as auto responder
                    $message_user = \Swift_Message::newInstance();
                    $imgUrl_user = $message_user->embed(\Swift_Image::fromPath('http://i.imgur.com/DE6vZW0.png'));
                    $message_user->setSubject('Konstruye - Formulario Cotizacion - Negocio')
                        ->setFrom(array('sistema@konstruye.pe'=>'Konstruye'))
                        ->setTo($data['email'])
                        ->setBody(
                            $this->renderView(
                                'emails/cotizacion_autoresponder_user.html.twig',
                                array(
                                    'nombre' => $data['name'],
                                    'email' => $data['email'],
                                    'asunto' => $data['subject'],
                                    'negocio'=>$negocio,
                                    'telefono' => $data['tel'],
                                    'mensaje' => $data['message'],
                                    'logo'=>$imgUrl_user
                                )
                            )
                        );
                    $message_user->setContentType("text/html");
                    $this->get('mailer')->send($message_user);
                    $message->setContentType("text/html");
                    $this->get('mailer')->send($message);

                    $this->get('swiftmailer.command.spool_send')->run(new ArgvInput(array()), new ConsoleOutput());

                    $request->getSession()->getFlashBag()->add('success', 'Gracias por tu solicitud !');
                    $url = $this->generateUrl('show_negocio',array('slug_negocio'=>$slug_negocio));
                    $response = new JsonResponse();
                    $response->setData(array(
                        'success' => $url
                    ));
                    return $response;
                }
                else{
                    $errors = $this->get('form_serializer')->serializeFormErrors($form, true, true);
                    $response = new JsonResponse();
                    $response->setData(array(
                        'errors' => $errors
                    ));
                    return $response;

                }

            }
            return $this->render(
                'cotizacion_negocio.html.twig',
                array('form' => $form->createView())
            );
       // }
    }
    public function showDetailAction(Request $request,$slug_negocio)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $negocio = $this->getDoctrine()->getRepository('AppBundle:Negocio')->findOneBySlug($slug_negocio);
        $fotos = $this->getDoctrine()->getRepository('AppBundle:Foto')->findBy(
            array('negocio'=>$negocio),
            array('sort'=>'ASC')
        );
        $comments = $this->getDoctrine()->getRepository('AppBundle:ComentarioNegocio')->getAllComments($negocio);
        $moy = $this->getDoctrine()->getRepository('AppBundle:Negocio')->getNegocioRating($negocio);

        //$inmuebles = $this->getDoctrine()->getRepository('AppBundle:Inmueble')->getInmueblesByDormitorios($number);

        if($negocio instanceof Proveedor){
            $breadcrumbs = $this->get("white_october_breadcrumbs");
            $breadcrumbs->addItem('Proveedores', $this->get("router")->generate("lisdato_seccion",array('slug_seccion'=>'proveedores')));
            $breadcrumbs->addItem($negocio->getNombre(), $this->get("router")->generate("show_negocio",array('slug_negocio'=>$negocio->getSlug())));
        }
        else if($negocio instanceof ConstructoraInmobiliaria){
            $breadcrumbs = $this->get("white_october_breadcrumbs");
            $breadcrumbs->addItem('constructura e inmobiliarias', $this->get("router")->generate("lisdato_seccion",array('slug_seccion'=>'constructoras-e-inmobiliarias')));
            $breadcrumbs->addItem($negocio->getNombre(), $this->get("router")->generate("show_negocio",array('slug_negocio'=>$negocio->getSlug())));
        }
        else if($negocio instanceof Especialista){
            $breadcrumbs = $this->get("white_october_breadcrumbs");
            $breadcrumbs->addItem('Especialistas servicios personales', $this->get("router")->generate("lisdato_seccion",array('slug_seccion'=>'especialistas-servicios-personales')));
            $breadcrumbs->addItem($negocio->getNombre(), $this->get("router")->generate("show_negocio",array('slug_negocio'=>$negocio->getSlug())));
        }
        else{
            $breadcrumbs = $this->get("white_october_breadcrumbs");
            $breadcrumbs->addItem('compra venta y alquiler de inmuebles', $this->get("router")->generate("lisdato_seccion",array('slug_seccion'=>'compra-venta-y-alquiler-inmuebles')));
            $breadcrumbs->addItem($negocio->getNombre(), $this->get("router")->generate("show_negocio",array('slug_negocio'=>$negocio->getSlug())));

        }
        $renderOut = array(
            'negocio'=>$negocio,
            'fotos'=>$fotos,
            'moy'=>$moy,
            'comentarios'=>$comments,
        );


        if($negocio->getGoogleMapLat()!=NULL && $negocio->getGoogleMapLng()!=NULL){
            $map = $this->initGoogleMap($negocio);
            $renderOut['map'] = $map;
        }


        if(is_object($user)){

            $comentarioNegocio = $this->getDoctrine()->getRepository('AppBundle:ComentarioNegocio')
                ->findOneBy(array('negocio'=>$negocio,'user'=>$user));
            $renderOut['myc'] = $comentarioNegocio;
            if($comentarioNegocio==null){
                $comentarioNegocio = new ComentarioNegocio();
                $form = $this->createForm(new ComentarioNegocioType(), $comentarioNegocio);
                $form->handleRequest($request);

                if ($form->isValid()) {
                    $comentarioNegocio->setUser($user);
                    $comentarioNegocio->setNegocio($negocio);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($comentarioNegocio);
                    $em->flush();
                    $request->getSession()->getFlashBag()->add('success', 'Gracias por tu comentario !');
                    return $this->redirectToRoute('show_negocio',array('slug_negocio'=>$slug_negocio));
                }
                $renderOut['form'] = $form->createView();
            }
        }

        if($negocio!=NULL){
            if($negocio instanceof ConstructoraInmobiliaria){
                return $this->render('show_categorias.html.twig',$renderOut);
            }
            else if($negocio instanceof Proveedor){
                $slug_categoria = $request->query->get('slug_categoria');
                $best_productos = $this->getDoctrine()->getRepository('AppBundle:Producto')->getProductosByNegocio($negocio,'moy')->getResult();
                $ids = array();
                foreach ($best_productos as $p){
                    $ids[] = $p['producto']->getId();
                }

                $categorias_main_products = $this->getDoctrine()->getRepository('AppBundle:CategoriaListadoProducto')->getCategoriasMainFromProducts($ids);
                $out = $this->get('menu_filter')->menuMain($request->get('_route'),'slug_negocio',$slug_negocio,$categorias_main_products);
                if($slug_categoria!=null){
                    $best_productos = $this->getDoctrine()->getRepository('AppBundle:Producto')->getProductosByNegocioCategoria($negocio,$slug_categoria)->getResult();
                    $out = $this->get('menu_filter')->menuChild($request->get('_route'),'slug_negocio',$slug_negocio,$categorias_main_products,$slug_categoria);
                }
                $renderOut['out'] = $out;
                $renderOut['productos_destacados'] = $best_productos;
                return $this->render('show_proveedores.html.twig',$renderOut);
            }
            else if($negocio instanceof Especialista){
                return $this->render('show_categorias.html.twig',$renderOut);
            }
            else{
                return $this->render('show_inmueble.html.twig',$renderOut);
            }
        }
    }
    public function searchNegocioAction(Request $request,$page){

        $search = $request->query->get('search');
        $seccion = $request->query->get('slug_seccion');
        $slug_categoria = $request->query->get('slug_categoria');
        $instance = $request->query->get('instance');
        $id = $request->query->get('id');


        if(empty($search)){
            $negocios = array();
        }
        else{
            if($seccion=="todos"){
                $obj = $this->getDoctrine()->getRepository('AppBundle:'.$instance)->find($id);
                if($obj instanceof User){
                    return $this->redirectToRoute('profile_public',array('id'=>$obj->getId()));
                }
                else if($obj instanceof ConstructoraInmobiliaria){
                    return $this->redirectToRoute('search_global_negocios',
                        array('slug_seccion'=>'constructoras-e-inmobiliarias','search'=>$search));
                }
                else if ($obj instanceof Especialista){
                    return $this->redirectToRoute('search_global_negocios',
                        array('slug_seccion'=>'especialistas-servicios-personales','search'=>$search));
                }
                else if($obj instanceof Proveedor){
                    return $this->redirectToRoute('search_global_negocios',
                        array('slug_seccion'=>'proveedores','search'=>$search));
                }
            }
            if($seccion=="especialistas-servicios-personales"){
                $negocios = $this->getDoctrine()->getRepository('AppBundle:Especialista')->getNegociosBy($search,$slug_categoria);
                $categorias_main = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren('especialista');
                $out = $this->get('menu_filter')->menuMain($request->get('_route'),'slug_seccion',$request->query->get('slug_seccion'),$categorias_main,$search);
                if($slug_categoria!=null){
                    $out = $this->get('menu_filter')->menuChild($request->get('_route'),'slug_seccion',$seccion,$categorias_main,$slug_categoria,$search);
                }
            }
            else if($seccion=="proveedores"){
                $negocios = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->getNegociosBy($search,$slug_categoria);
                $categorias_main = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren('proveedor');
                $out = $this->get('menu_filter')->menuMain($request->get('_route'),'slug_seccion',$request->query->get('slug_seccion'),$categorias_main,$search);
                if($slug_categoria!=null){
                    $out = $this->get('menu_filter')->menuChild($request->get('_route'),'slug_seccion',$seccion,$categorias_main,$slug_categoria,$search);
                }

            }
            else if($seccion=="constructoras-e-inmobiliarias"){
                $negocios = $this->getDoctrine()->getRepository('AppBundle:ConstructoraInmobiliaria')->getNegociosBy($search,$slug_categoria);
                $categorias_main = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren('constructura-inmobiliaria');
                $out = $this->get('menu_filter')->menuMain($request->get('_route'),'slug_seccion',$request->query->get('slug_seccion'),$categorias_main,$search);
                if($slug_categoria!=null){
                    $out = $this->get('menu_filter')->menuChild($request->get('_route'),'slug_seccion',$seccion,$categorias_main,$slug_categoria,$search);
                }
            }


        }
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Inicio', $this->get("router")->generate("homepage"));
        $breadcrumbs->addItem('Resultado de busqueda');


        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $negocios,
            $page,
            6
        );
        return $this->render('layout_categorias.html.twig',array(
            'negocios'=>$pagination,
            'out'=>$out
        ));
    }
    public function registerConfirmationAction(Request $request){
        return $this->render('FOSUserBundle:Profile:negocio_register_confirmation.html.twig');
    }

}