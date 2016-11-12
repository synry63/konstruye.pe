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
use AppBundle\Form\Type\CotizacionProductoType;
use AppBundle\Form\Type\CotizacionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class ProductoController extends Controller
{
    public function searchProductoAction(Request $request,$page){

        $search = $request->query->get('search');
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

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Inicio', $this->get("router")->generate("homepage"));
        $breadcrumbs->addItem('Resultado de busqueda');

        return $this->render('layout_productos.html.twig',array(
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
        $fotos = $this->getDoctrine()->getRepository('AppBundle:FotoProducto')->findBy(
            array('producto'=>$producto),
            array('updatedAt'=>'DESC')
        );
        $moy = $this->getDoctrine()->getRepository('AppBundle:Producto')->getProductoRating($producto);
        $comments = $this->getDoctrine()->getRepository('AppBundle:ComentarioProducto')->getAllComments($producto);
        $producto_related = $this->getDoctrine()->getRepository('AppBundle:Producto')->getProductosRelated($producto,4);

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Proveedores', $this->get("router")->generate("lisdato_seccion",array('slug_seccion'=>'proveedores')));
        $breadcrumbs->addItem($producto->getNegocio()->getNombre(), $this->get("router")->generate("show_negocio",array('slug_negocio'=>$producto->getNegocio()->getSlug())));
        $breadcrumbs->addItem('Productos',$this->get("router")->generate("ver_mas_productos_proveedor",array('slug_negocio'=>$producto->getNegocio()->getSlug())));
        $breadcrumbs->addItem($producto->getNombre());

        $renderOut = array(
            'producto'=>$producto,
            'moy'=>$moy,
            'fotos'=>$fotos,
            'productos_relacionados'=>$producto_related,
            'comentarios'=>$comments,
        );
        if(is_object($user)){
            $comentarioProducto = $this->getDoctrine()->getRepository('AppBundle:ComentarioProducto')
                ->findOneBy(array('producto'=>$producto,'user'=>$user));
            $renderOut['myc'] = $comentarioProducto;
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
    public function contizacionAction($slug_producto,Request $request)
    {
        //if ($request->isXmlHttpRequest()) {
        $producto = $this->getDoctrine()->getRepository('AppBundle:Producto')->findOneBy(array('slug'=>$slug_producto));
        $negocio =$producto->getNegocio();
        $form = $this->createForm(new CotizacionType(),NULL,array(
            'action' => $this->generateUrl('producto_solicitar_cotizacion',array('slug_producto'=>$slug_producto)),
            'method' => 'POST',
        ));
        $form->handleRequest($request);


        if ($form->isSubmitted()) {

            if($form->isValid()){
                $data = $form->getData();
                // send email to negocio
                $message = \Swift_Message::newInstance();
                $imgUrl = $message->embed(\Swift_Image::fromPath('http://i.imgur.com/DE6vZW0.png'));
                $message->setSubject('Konstruye - Formulario Cotizacion - Producto')
                    ->setFrom(array('sistema@konstruye.pe'=>'Konstruye'))
                    ->setTo($negocio->getEmail())
                    ->setBody(
                        $this->renderView(
                            'emails/cotizacion_negocio_producto.html.twig',
                            array(
                                'nombre' => $data['name'],
                                'email' => $data['email'],
                                'asunto' => $data['subject'],
                                'negocio'=>$negocio,
                                'producto'=>$producto,
                                'telefono' => $data['tel'],
                                'mensaje' => $data['message'],
                                'logo'=>$imgUrl
                            )
                        )
                    );

                // send email to user as auto responder
                $message_user = \Swift_Message::newInstance();
                $imgUrl_user = $message_user->embed(\Swift_Image::fromPath('http://i.imgur.com/DE6vZW0.png'));
                $message_user->setSubject('Konstruye - Formulario Cotizacion - Producto')
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
                                'producto'=>$producto,
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
                $url = $this->generateUrl('show_producto',array('slug_producto'=>$slug_producto));
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
            'cotizacion_producto.html.twig',
            array('form' => $form->createView(),'producto'=>$producto)
        );
        // }
    }
    public function showProductosProveedorAction(Request $request,$slug_negocio,$page){
        $negocio = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->findOneBy(
            array('slug'=>$slug_negocio)
        );
        $productos = $this->getDoctrine()->getRepository('AppBundle:Producto')->getProductosByNegocio($negocio);

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Proveedores', $this->get("router")->generate("lisdato_seccion",array('slug_seccion'=>'proveedores')));
        $breadcrumbs->addItem($negocio->getNombre(), $this->get("router")->generate("show_negocio",array('slug_negocio'=>$negocio->getSlug())));
        $breadcrumbs->addItem('Productos');


        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $productos,
            $page,
            6
        );

        return $this->render('layout_productos.html.twig',
            array(
                'productos'=>$pagination
            )
        );
    }

}