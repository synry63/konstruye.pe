<?php
/**
 * Created by PhpStorm.
 * User: patrici-star
 * Date: 9/25/2016
 * Time: 1:37 AM
 */
namespace AppBundle\Controller;

use AppBundle\Entity\ComentarioNegocio;
use AppBundle\Form\Type\ComentarioNegocioType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ComentarioNegocioController extends Controller
{
    public function comentarNegocioUserNewAction($slug_negocio,Request $request){

        $user = $this->container->get('security.context')->getToken()->getUser();
        $negocio = $this->getDoctrine()->getRepository('AppBundle:Negocio')->findOneBy(array('slug'=>$slug_negocio));
        if(is_object($user)){
            //$comentarioProveedor = $this->getDoctrine()->getRepository('AppBundle:ComentarioProveedor')
            // ->findOneBy(array('proveedor'=>$proveedor,'user'=>$user));
            $comentarioNegocio = new ComentarioNegocio();

            $form = $this->createForm(new ComentarioNegocioType(),$comentarioNegocio,array(
                'action' => $this->generateUrl('negocio_me_comentar_new',array('slug_negocio'=>$slug_negocio)),
                'method' => 'POST',
            ));
            //$form = $this->createForm(new ComentarioProveedorType(), $comentarioProveedor);
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                if($form->isValid()){
                    $comentarioNegocio->setUser($user);
                    $comentarioNegocio->setNegocio($negocio);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($comentarioNegocio);
                    $em->flush();

                    $request->getSession()->getFlashBag()->add('success', 'Gracias por tu comentario !');
                    //return $this->redirectToRoute('proveedor_detail',array('slug_site'=>$slug_site,'slug_proveedor'=>$slug_proveedor));
                    $url = $this->generateUrl('show_negocio',array('slug_negocio'=>$slug_negocio));
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
                'comentario_negocio.html.twig',array(
                    'form'=>$form->createView()
                )
            );
        }
    }
    public function comentarNegocioUserEditAction($slug_negocio,Request $request){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $negocio = $this->getDoctrine()->getRepository('AppBundle:Negocio')->findOneBy(array('slug'=>$slug_negocio));

        if(is_object($user)){
            $comentarioNegocio = $this->getDoctrine()->getRepository('AppBundle:ComentarioNegocio')
                ->findOneBy(array('negocio'=>$negocio,'user'=>$user));
            //$comentarioProveedor = new ComentarioProveedor();

            $form = $this->createForm(new ComentarioNegocioType(),$comentarioNegocio,array(
                'action' => $this->generateUrl('negocio_me_comentar_edit',array('slug_negocio'=>$slug_negocio)),
                'method' => 'POST',
            ));
            //$form = $this->createForm(new ComentarioProveedorType(), $comentarioProveedor);
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                if($form->isValid()){
                    $comentarioNegocio->setUser($user);
                    $comentarioNegocio->setNegocio($negocio);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($comentarioNegocio);
                    $em->flush();

                    $request->getSession()->getFlashBag()->add('success', 'Comentario editado gracias !');
                    //return $this->redirectToRoute('proveedor_detail',array('slug_site'=>$slug_site,'slug_proveedor'=>$slug_proveedor));
                    $url = $this->generateUrl('show_negocio',array('slug_negocio'=>$slug_negocio));
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
                'comentario_negocio.html.twig',array(
                    'form'=>$form->createView()
                )
            );
        }
    }
}