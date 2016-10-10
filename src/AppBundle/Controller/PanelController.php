<?php
/**
 * Created by PhpStorm.
 * User: patrici-star
 * Date: 7/30/2016
 * Time: 9:38 AM
 */
namespace AppBundle\Controller;

use AppBundle\Entity\ConstructoraInmobiliaria;
use AppBundle\Entity\Proveedor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PanelController extends Controller
{
    public function menuAllowedAction(){
        $menu_items = array('dashboard'=>true,'negocio'=>true,'proyectos'=>false,'inmueble'=>false,'productos'=>false);

        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        $negocio = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);

        if($negocio instanceof Proveedor){
            $menu_items['productos'] = true;
        }
        else if($negocio instanceof ConstructoraInmobiliaria){
            $menu_items['proyectos'] = true;
        }
        return $this->render('FOSUserBundle:Profile:Panel/Menu/menu.html.twig',
            array('menu'=>$menu_items)
        );
    }

    public function setPanelNegocioAction(Request $request,$slug_negocio){
        $user = $this->container->get('security.context')->getToken()->getUser();
        // check if no negocio
        if(count($user->getNegocios())==0){
            $request->getSession()->getFlashBag()->add('success', 'Necesitas minimo un negocio registrado para ingresar al Panel !');
            return $this->redirectToRoute('fos_user_profile_show');
        }
        $negocio = $this->getDoctrine()->getRepository('AppBundle:Negocio')
            ->findOneBy(
                array('slug'=>$slug_negocio,'user'=>$user)
            );
        $session = $this->getRequest()->getSession();
        $session->set('negocio_id', $negocio->getId());

        return $this->redirectToRoute('profile_negocios_panel_dashboard');
    }

    public function showPanelNegocioUserDashbordAction(Request $request){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);
        //$menu = $this->menuAllowedAction($negocio_current);
        return $this->render(
            'FOSUserBundle:Profile:Panel/dashboard.html.twig',
            array('negocio'=>$negocio_current)
        );
    }
    public function showPanelNegocioUserNegocioDatosAction(Request $request){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);

        $form = $this->createForm(new ProveedorType(), $proveedor);
        $form->handleRequest($request);
    }

}