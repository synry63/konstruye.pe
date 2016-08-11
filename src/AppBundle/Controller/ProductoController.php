<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 6/14/16
 * Time: 6:26 AM
 */
namespace AppBundle\Controller;

use AppBundle\Entity\ComentarioProducto;
use AppBundle\Form\Type\ComentarioProductoType;
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
            $productos = $this->getDoctrine()->getRepository('AppBundle:Producto')->getProductosBy($search);
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

        $user = $this->container->get('security.context')->getToken()->getUser();
        $producto = $this->getDoctrine()->getRepository('AppBundle:Producto')->findOneBySlug($slug_producto);
        $moy = $this->getDoctrine()->getRepository('AppBundle:Producto')->getProductoRating($producto);
        $comments = $this->getDoctrine()->getRepository('AppBundle:ComentarioProducto')->getAllComments($producto);

        $renderOut = array(
            'producto'=>$producto,
            'moy'=>$moy,
            'comentarios'=>$comments,
        );
        if(is_object($user)){
            $comentarioProducto = $this->getDoctrine()->getRepository('AppBundle:ComentarioProducto')
                ->findOneBy(array('producto'=>$producto,'user'=>$user));
            if($comentarioProducto==null){
                $comentarioProducto = new ComentarioProducto();
                $form = $this->createForm(new ComentarioProductoType(), $comentarioProducto);
                $form->handleRequest($request);
                if ($form->isValid()) {
                    //$data = $form->getData();
                    $comentarioProducto->setUser($user);
                    $comentarioProducto->setProducto($producto);
                    //$comentarioProveedor->setNota($data->getNota());
                    //$comentarioProveedor->setComentario($data->getComentario());
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($comentarioProducto);
                    $em->flush();

                    $request->getSession()->getFlashBag()->add('success', 'Gracias por tu comentario !');
                    return $this->redirectToRoute('show_producto',array('slug_producto'=>$slug_producto));
                    //return $this->redirectToRoute('task_success');
                    //$this->redirect($request->getReferer());
                }

                $renderOut['form'] = $form->createView();

            }
        }
        //else{
            return $this->render(
                'show_producto.html.twig',$renderOut
            );
        //}



    }
    public function showProductosProveedorAction(Request $request,$slug_negocio,$page){



        $negocio = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->findOneBy(
            array('slug'=>$slug_negocio)
        );
        $productos = $this->getDoctrine()->getRepository('AppBundle:Producto')->getProductosByNegocio($negocio);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $productos,
            $page,
            6
        );

        return $this->render('ver_mas.html.twig',
            array(
                'negocio'=>$negocio,
                'productos'=>$pagination
            )
        );
    }

}