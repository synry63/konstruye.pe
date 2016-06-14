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

class NegocioController extends Controller
{

    public function listadoSeccionAction(Request $request,$slug_seccion,$page)
    {

        if($slug_seccion=="constructoras-e-inmobiliarias"){
            $negocios = $this->getDoctrine()->getRepository('AppBundle:ConstructoraInmobiliaria')->getNegocios();
            $categorias = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren('constructura-inmobiliaria');
        }
        else if($slug_seccion=="compra-venta-y-alquiler-inmuebles"){
            $negocios = $this->getDoctrine()->getRepository('AppBundle:Inmueble')->getNegocios();
            $categorias = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren('inmueble');


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
        return $this->render('temp.html.twig',array(
            'negocios'=>$pagination,
            'categorias_hijas'=>$categorias
        ));
    }

    public function showDetailAction(Request $request,$slug_seccion,$slug_negocio)
    {

        $negocio = $this->getDoctrine()->getRepository('AppBundle:Negocio')->findOneBySlug($slug_negocio);

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