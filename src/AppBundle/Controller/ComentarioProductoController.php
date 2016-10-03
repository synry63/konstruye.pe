<?php
/**
 * Created by PhpStorm.
 * User: patrici-star
 * Date: 9/25/2016
 * Time: 1:37 AM
 */
namespace AppBundle\Controller;

use AppBundle\Entity\ComentarioProducto;
use AppBundle\Form\Type\ComentarioProductoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ComentarioProductoController extends Controller
{
    public function comentarProductoUserNewAction($slug_producto,Request $request){

        $user = $this->container->get('security.context')->getToken()->getUser();
        $producto = $this->getDoctrine()->getRepository('AppBundle:Producto')->findOneBy(array('slug'=>$slug_producto));
        if(is_object($user)){
            //$comentarioProveedor = $this->getDoctrine()->getRepository('AppBundle:ComentarioProveedor')
            // ->findOneBy(array('proveedor'=>$proveedor,'user'=>$user));
            $comentarioProducto = new ComentarioProducto();

            $form = $this->createForm(new ComentarioProductoType(),$comentarioProducto,array(
                'action' => $this->generateUrl('producto_me_comentar_new',array('slug_producto'=>$slug_producto)),
                'method' => 'POST',
            ));
            //$form = $this->createForm(new ComentarioProveedorType(), $comentarioProveedor);
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                if($form->isValid()){
                    $comentarioProducto->setUser($user);
                    $comentarioProducto->setProducto($producto);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($comentarioProducto);
                    $em->flush();

                    $request->getSession()->getFlashBag()->add('success', 'Gracias por tu comentario !');
                    //return $this->redirectToRoute('proveedor_detail',array('slug_site'=>$slug_site,'slug_proveedor'=>$slug_proveedor));
                    $url = $this->generateUrl('show_producto',array('slug_producto'=>$slug_producto));
                    $response = new JsonResponse();
                    $response->setData(array(
                        'success' => $url
                    ));
                    return $response;
                    //return $this->redirectToRoute('task_success');
                    //$this->redirect($request->getReferer());
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
            //$renderOut['form'] = $form->createView();
            return $this->render(
                'comentario_producto.html.twig',array(
                    'form'=>$form->createView()
                )
            );
        }
    }
    public function comentarProductoUserEditAction($slug_producto,Request $request){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $producto = $this->getDoctrine()->getRepository('AppBundle:Producto')->findOneBy(array('slug'=>$slug_producto));

        if(is_object($user)){
            $comentarioProductos = $this->getDoctrine()->getRepository('AppBundle:ComentarioProducto')
                ->findOneBy(array('producto'=>$producto,'user'=>$user));
            //$comentarioProveedor = new ComentarioProveedor();

            $form = $this->createForm(new ComentarioProductoType(),$comentarioProductos,array(
                'action' => $this->generateUrl('producto_me_comentar_edit',array('slug_producto'=>$slug_producto)),
                'method' => 'POST',
            ));
            //$form = $this->createForm(new ComentarioProveedorType(), $comentarioProveedor);
            $form->handleRequest($request);
            if ($form->isSubmitted()){
                if($form->isValid()){
                    $comentarioProductos->setUser($user);
                    $comentarioProductos->setProducto($producto);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($comentarioProductos);
                    $em->flush();

                    $request->getSession()->getFlashBag()->add('success', 'Comentario editado gracias !');
                    //return $this->redirectToRoute('proveedor_detail',array('slug_site'=>$slug_site,'slug_proveedor'=>$slug_proveedor));
                    $url = $this->generateUrl('show_producto',array('slug_producto'=>$slug_producto));
                    $response = new JsonResponse();
                    $response->setData(array(
                        'success' => $url
                    ));
                    return $response;
                    //return $this->redirectToRoute('task_success');
                    //$this->redirect($request->getReferer());
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
            //$renderOut['form'] = $form->createView();
            return $this->render(
                'comentario_producto.html.twig',array(
                    'form'=>$form->createView()
                )
            );
        }
    }
}