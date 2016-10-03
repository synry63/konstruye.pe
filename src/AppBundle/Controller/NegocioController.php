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
use AppBundle\Entity\Proveedor;
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
    /**
     * @param $text
     * @return mixed|string
     * slugify a text
     */
    private function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }

    public function listadoSeccionCategoriaAction(Request $request,$slug_seccion,$slug_categoria,$page)
    {

        $twig = 'layout_categorias.html.twig';
        $categoria = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>$slug_categoria));

        if($slug_seccion=="constructoras-e-inmobiliarias"){
            $negocios = $this->getDoctrine()->getRepository('AppBundle:ConstructoraInmobiliaria')->getNegociosByCategoria($categoria);
            $categorias = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren('constructura-inmobiliaria');
        }
        else if($slug_seccion=="compra-venta-y-alquiler-inmuebles"){
            $negocios = $this->getDoctrine()->getRepository('AppBundle:Inmueble')->getNegociosByCategoria($categoria);
            $categorias = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren('inmueble');
            $twig = 'layout_compra_y_venta.html.twig';

        }
        else if($slug_seccion=="especialistas-servicios-personales"){
            $negocios = $this->getDoctrine()->getRepository('AppBundle:Especialista')->getNegociosByCategoria($categoria);
            $categorias = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren('especialista');
        }
        else{
            $negocios = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->getNegociosByCategoria($categoria);
            $categorias = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren('proveedor');
        }
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $negocios,
            $page,
            4
        );
        return $this->render($twig,array(
            'negocios'=>$pagination,
            'categorias_hijas'=>$categorias
        ));

    }
    public function listadoSeccionAction(Request $request,$slug_seccion,$page)
    {
        $twig = 'layout_categorias.html.twig';
        if($slug_seccion=="constructoras-e-inmobiliarias"){
            $negocios = $this->getDoctrine()->getRepository('AppBundle:ConstructoraInmobiliaria')->getNegocios();
            $categorias = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren('constructura-inmobiliaria');
        }
        else if($slug_seccion=="compra-venta-y-alquiler-inmuebles"){
            $negocios = $this->getDoctrine()->getRepository('AppBundle:Inmueble')->getNegocios();
            $categorias = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren('inmueble');
            $twig = 'layout_compra_y_venta.html.twig';

        }
        else if($slug_seccion=="especialistas-servicios-personales"){
            $negocios = $this->getDoctrine()->getRepository('AppBundle:Especialista')->getNegocios();
            $categorias = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren('especialista');
        }
        else{
            $negocios = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->getNegocios();
            $categorias = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren('proveedor');
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $negocios,
            $page,
            4
        );
        return $this->render($twig,array(
            'negocios'=>$pagination,
            'categorias_hijas'=>$categorias
        ));
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

        if($slug_seccion=="constructoras-e-inmobiliarias"){
            $out = $this->getDoctrine()->getRepository('AppBundle:ConstructoraInmobiliaria')->searchNegociosNames($search);
        }
        else if($slug_seccion=="compra-venta-y-alquiler-inmuebles"){

        }
        else if($slug_seccion=="especialistas-servicios-personales"){
            $out = $this->getDoctrine()->getRepository('AppBundle:Especialista')->searchNegociosNames($search);
        }
        else if($slug_seccion=="proveedores"){
            $out = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->searchNegociosNames($search);
        }


        //if($slug_seccion=="null"){
        //}

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
        $comments = $this->getDoctrine()->getRepository('AppBundle:ComentarioNegocio')->getAllComments($negocio);
        $moy = $this->getDoctrine()->getRepository('AppBundle:Negocio')->getNegocioRating($negocio);
        $productos = $this->getDoctrine()->getRepository('AppBundle:Producto')->getProductosByNegocio($negocio)->getResult();
        $renderOut = array(
            'negocio'=>$negocio,
            'productos'=>$productos,
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
                return $this->render('show_proveedores.html.twig',$renderOut);
            }
            else if($negocio instanceof Especialista){
                return $this->render('show_categorias.html.twig',$renderOut);
            }
            else{
                return $this->render('show_categorias.html.twig',$renderOut);
            }
        }
    }
    public function searchNegocioAction(Request $request,$search,$page){

        if(empty($search)){
            $negocios = array();
        }
        else{
            $negocios = $this->getDoctrine()->getRepository('AppBundle:Negocio')->getNegociosBy($search);

        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $negocios,
            $page,
            6
        );
        return $this->render('resultado_busqueda_negocios.html.twig',array(
            'negocios'=>$pagination,
        ));
    }

}