<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/17/16
 * Time: 1:44 PM
 */
namespace AppBundle\Controller;

use AppBundle\Entity\ConstructoraInmobiliaria;
use AppBundle\Entity\Especialista;
use AppBundle\Entity\Inmueble;
use AppBundle\Entity\Proveedor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ListadoController extends Controller
{
    /**
     * @Route("/{slug_seccion}/{page}", name="lisdato_seccion",defaults={"page" = 1},requirements={
     *      "slug_seccion": "constructoras-e-inmobiliarias|compra-venta-y-alquiler-inmuebles|especialistas-servicios-personales|proveedores",
     *      "page": "\d+"
     * })
     */
    public function listadoSeccionAction(Request $request,$slug_seccion,$page)
    {

        if($slug_seccion=="constructoras-e-inmobiliarias"){
            $negocios = $this->getDoctrine()->getRepository('AppBundle:ConstructoraInmobiliaria')->getNegocios();
        }
        else if($slug_seccion=="compra-venta-y-alquiler-inmuebles"){
            $negocios = $this->getDoctrine()->getRepository('AppBundle:Inmueble')->getNegocios();
        }
        else if($slug_seccion=="especialistas-servicios-personales"){
            $negocios = $this->getDoctrine()->getRepository('AppBundle:Especialista')->getNegocios();
        }
        else{
            $negocios = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->getNegocios();
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $negocios,
            $page,
            6
        );
        return $this->render('temp.html.twig',array(
            'negocios'=>$pagination
        ));
    }
    /**
     * @Route("/{slug_seccion}/{slug_negocio}", name="lisdato_seccion",defaults={"page" = 1},requirements={
     *      "slug_seccion": "constructoras-e-inmobiliarias|compra-venta-y-alquiler-inmuebles|especialistas-servicios-personales|proveedores"
     * })
     */
    public function showDetailAction(Request $request,$slug_seccion,$slug_negocio)
    {
        $negocio = $this->getDoctrine()->getRepository('AppBundle:Negocio')->findOneByslug($slug_negocio);

        if($negocio!=NULL){
            if($negocio instanceof ConstructoraInmobiliaria){
                return $this->render('temp.html.twig',array(
                    'negocios'=>$negocio
                ));
            }
            else if($negocio instanceof Proveedor){
                return $this->render('temp.html.twig',array(
                    'negocios'=>$negocio
                ));
            }
            else if($negocio instanceof Especialista){
                return $this->render('temp.html.twig',array(
                    'negocios'=>$negocio
                ));
            }
            else{
                return $this->render('temp.html.twig',array(
                    'negocios'=>$negocio
                ));
            }
        }
    }
}