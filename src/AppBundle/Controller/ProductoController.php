<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 6/14/16
 * Time: 6:26 AM
 */
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductoController extends Controller
{
    public function searchProductoAction(Request $request,$search,$page){

        if(empty($search)){
            $productos = array();
        }
        else{
            $productos = $this->getDoctrine()->getRepository('AppBundle:Producto')->getProductos($search);
        }


        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $productos,
            $page,
            6
        );

        return $this->render('resultado_busqueda_productos.html.twig',array(
            'productos'=>$pagination,
        ));
    }
    public function buscarListaProductosAction(Request $request){

        $search = $request->query->get('q');
        $out = $this->getDoctrine()->getRepository('AppBundle:Producto')->searchProductosNames($search);
        $response = new JsonResponse($out);

        return $response;
    }

    public function showDetailAction(Request $request,$slug_producto)
    {
        $producto = $this->getDoctrine()->getRepository('AppBundle:Producto')->findOneBySlug($slug_producto);
        return $this->render('temp.html.twig',array(
            'producto'=>$producto
        ));


    }

}