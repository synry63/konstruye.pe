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

class ProductoController extends Controller
{
    public function searchProductoAction(Request $request,$search,$page){

        $negocios = $this->getDoctrine()->getRepository('AppBundle:Producto')->getProductos($search);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $negocios,
            $page,
            6
        );
        return $this->render('temp4.html.twig',array(
            'productos'=>$pagination,
        ));
    }
    public function showDetailAction(Request $request,$slug_producto)
    {
        $producto = $this->getDoctrine()->getRepository('AppBundle:Producto')->findOneBySlug($slug_producto);
        return $this->render('temp.html.twig',array(
            'producto'=>$producto
        ));


    }
}