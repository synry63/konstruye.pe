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
use AppBundle\Entity\Proveedor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Ivory\GoogleMap\Overlays\Marker;
use Ivory\GoogleMap\Overlays\Animation;

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
            6
        );
        return $this->render($twig,array(
            'negocios'=>$pagination,
            'categorias_hijas'=>$categorias
        ));
    }

    public function showDetailAction(Request $request,$slug_seccion,$slug_negocio)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $negocio = $this->getDoctrine()->getRepository('AppBundle:Negocio')->findOneBySlug($slug_negocio);
        $comments = $this->getDoctrine()->getRepository('AppBundle:ComentarioNegocio')->getAllComments($negocio);
        $moy = $this->getDoctrine()->getRepository('AppBundle:Negocio')->getNegocioRating($negocio);

        $map = $this->get('ivory_google_map.map');
        $map->setLanguage($this->get('request')->getLocale());
        $map->setCenter(-12.0552581, -77.080205, true);
        $map->setMapOption('zoom', 3);
        $map->setBound(-2.1, -3.9, 2.6, 1.4, true, true);
        $map->setStylesheetOption('width', '100%');
        $map->setStylesheetOption('height', '300px');
        $marker = new Marker();
        // Sets your marker animation
        $marker->setAnimation(Animation::BOUNCE);
        $marker->setAnimation('bounce');
        $marker->setPosition(-12.0552581, -77.080205, true);
        // Add your marker to the map
        $map->addMarker($marker);

        $renderOut = array(
            'negocio'=>$negocio,
            'moy'=>$moy,
            'comentarios'=>$comments,
            'map'=>$map
        );
        if(is_object($user)){
            $comentarioNegocio = $this->getDoctrine()->getRepository('AppBundle:ComentarioNegocio')
                ->findOneBy(array('proveedor'=>$negocio,'user'=>$user));
            if($comentarioNegocio==null){
                $comentarioNegocio = new ComentarioNegocio();
                $form = $this->createForm(new ComentarioNegocioType(), $comentarioNegocio);
                $form->handleRequest($request);
                if ($form->isValid()) {
                    //$comentarioNegocio->setUser($user);
                    $comentarioNegocio->setNegocio($negocio);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($comentarioNegocio);
                    $em->flush();

                    $request->getSession()->getFlashBag()->add('success', 'Gracias por tu comentario !');
                    return $this->redirectToRoute('show_negocio',array('slug_seccion'=>$slug_seccion,'slug_negocio'=>$slug_negocio));

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
        $negocios = $this->getDoctrine()->getRepository('AppBundle:Negocio')->getNegociosBy($search);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $negocios,
            $page,
            6
        );
        return $this->render('temp3.html.twig',array(
            'negocios'=>$pagination,
        ));
    }
}