<?php
/**
 * Created by PhpStorm.
 * User: patrici-star
 * Date: 7/30/2016
 * Time: 9:38 AM
 */
namespace AppBundle\Controller;

use AppBundle\Entity\Banner;
use AppBundle\Entity\ConstructoraInmobiliaria;
use AppBundle\Entity\Especialista;
use AppBundle\Entity\Foto;
use AppBundle\Entity\FotoProducto;
use AppBundle\Entity\FotoProyecto;
use AppBundle\Entity\GeneralInmueble;
use AppBundle\Entity\Inmueble;
use AppBundle\Entity\Producto;
use AppBundle\Entity\Proveedor;
use AppBundle\Entity\Proyecto;
use AppBundle\Entity\ServicioInmueble;
use AppBundle\Form\Type\BannerType;
use AppBundle\Form\Type\ConstructoraType;
use AppBundle\Form\Type\EspecialistaType;
use AppBundle\Form\Type\FotoProductoType;
use AppBundle\Form\Type\FotoProyectoType;
use AppBundle\Form\Type\FotoType;
use AppBundle\Form\Type\GoogleMapType;
use AppBundle\Form\Type\InmuebleType;
use AppBundle\Form\Type\LogoType;
use AppBundle\Form\Type\ProductoType;
use AppBundle\Form\Type\ProveedorType;
use AppBundle\Form\Type\ProyectoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class PanelController extends Controller
{

    /*public function menuAllowedAction(){
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
    }*/

    public function setPanelNegocioAction(Request $request,$slug_negocio){
        $user = $this->container->get('security.context')->getToken()->getUser();
        // check if no negocio
        if(count($user->getNegocios())==0){
            $request->getSession()->getFlashBag()->add('success', 'Necesitas minimo un negocio registrado para ingresar al Panel !');
            return $this->redirectToRoute('fos_user_profile_show');
        }
        $negocio = null;
        foreach ($user->getNegocios() as $n){
            if($n->getSlug()==$slug_negocio){
                $negocio = $n;
                break;
            }
        }
        if($negocio==null)  $negocio = $user->getNegocios()[0];
        $session = $this->getRequest()->getSession();
        $session->set('negocio_id', $negocio->getId());


        return $this->redirectToRoute('profile_negocios_panel_dashboard');
    }
    /** START GESTION NEGOCIOS  **/
    public function showPanelNegocioUserDashbordAction(Request $request){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);
        //$menu = $this->menuAllowedAction($negocio_current);
        return $this->render(
            'FOSUserBundle:Profile:Panel/dashboard.html.twig',
            array('negocio'=>$negocio_current,'tipo'=>$this->get('negocio_type')->getArrayAcordingToTypeOf($negocio_current))
        );
    }
    public function showPanelNegocioUserCategoriasAction(Request $request){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);

        if($this->getRequest()->isMethod('POST'))
        {
            $categoriasListadosIds = $this->get('request')->request->get('categoriasListado');
            $categoriasListado = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findById($categoriasListadosIds);
            $negocio_current->setCategoriasListado($categoriasListado);
            $em = $this->getDoctrine()->getManager();
            $em->persist($negocio_current);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'categorias actualizadas !');
            return $this->redirectToRoute('profile_negocios_panel_gestion_negocio_cambiar_categorias');
        }
        else
        {
            $categoriasListadoNegocio = $negocio_current->getCategoriasListado();
            if($negocio_current instanceof Proveedor){
                $categorias_main = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren('proveedor');
            }
            else if($negocio_current instanceof Especialista){
                $categorias_main = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren('especialista');
            }
            else if($negocio_current instanceof ConstructoraInmobiliaria){
                $categorias_main = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren('constructura-inmobiliaria');
            }
            $out = $this->get('menu_filter')->menuChildPanel($categorias_main,$categoriasListadoNegocio);
            return $this->render(
                'FOSUserBundle:Profile:Panel/cambiar_categorias.html.twig',
                array('out'=>$out,'negocio'=>$negocio_current,'tipo'=>$this->get('negocio_type')->getArrayAcordingToTypeOf($negocio_current))
            );
        }
    }
    public function showPanelNegocioUserProductosCategoriasAction(Request $request,$id){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);
        $producto = $this->getDoctrine()->getRepository('AppBundle:Producto')->find($id);
        if($this->getRequest()->isMethod('POST'))
        {
            $categoriasListadosIds = $this->get('request')->request->get('categoriasListado');
            $categoriasListado = $this->getDoctrine()->getRepository('AppBundle:CategoriaListadoProducto')->findById($categoriasListadosIds);
            $producto->setCategoriasListado($categoriasListado);
            $em = $this->getDoctrine()->getManager();
            $em->persist($producto);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'categorias actualizadas !');
            return $this->redirectToRoute('profile_negocios_panel_gestion_negocio_cambiar_categorias_producto',array('id'=>$id));
        }
        else
        {
            $categorias_main = $this->getDoctrine()->getRepository('AppBundle:CategoriaListadoProducto')->getCategoriasMain();
            $out = $this->get('menu_filter')->menuChildPanel($categorias_main,$producto->getCategoriasListado());
            return $this->render(
                'FOSUserBundle:Profile:Panel/producto_categorias.html.twig',
                array('out'=>$out,'producto'=>$producto,'negocio'=>$negocio_current,'tipo'=>$this->get('negocio_type')->getArrayAcordingToTypeOf($negocio_current))
            );
        }
    }
    public function showPanelNegocioUserDatosAction(Request $request){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);
        if($negocio_current instanceof Proveedor){
            $form = $this->createForm(new ProveedorType(), $negocio_current);
        }
        else if($negocio_current instanceof Especialista){
            $form = $this->createForm(new EspecialistaType(), $negocio_current);
        }
        else if($negocio_current instanceof Inmueble){
            $user = $this->container->get('security.context')->getToken()->getUser();
            $form = $this->createForm(new InmuebleType($user), $negocio_current);
        }
        else if($negocio_current instanceof ConstructoraInmobiliaria){
            $form = $this->createForm(new ConstructoraType(), $negocio_current);
        }
        $form->handleRequest($request);
        if ($form->isValid()) {
            $slug = $this->get('slugify')->slugify($negocio_current->getNombre());
            $negocio_current->setSlug($slug);
            $em = $this->getDoctrine()->getManager();
            $em->persist($negocio_current);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'datos actualizados !');
            return $this->redirectToRoute('profile_negocios_panel_gestion_negocio_cambiar_datos');
        }
        return $this->render(
            'FOSUserBundle:Profile:Panel/cambiar_datos.html.twig',
            array('negocio'=>$negocio_current,'form'=>$form->createView(),'tipo'=>$this->get('negocio_type')->getArrayAcordingToTypeOf($negocio_current))
        );
    }
    public function showPanelNegocioUserBannerAction(Request $request){

        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);
        $banner = $this->getDoctrine()->getRepository('AppBundle:Banner')->findOneBy(
            array('negocio'=>$negocio_current)
        );
        if($banner==null) $banner = new Banner();

        $form = $this->createForm(new BannerType(), $banner);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $banner->setNegocio($negocio_current);
            $em->persist($banner);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'banner actualizado !');
            return $this->redirectToRoute('profile_negocios_panel_gestion_negocio_cambiar_banner');
        }
        return $this->render(
            'FOSUserBundle:Profile:Panel/cambiar_banner.html.twig',
            array('negocio'=>$negocio_current,'banner'=>$banner,'form'=>$form->createView(),'tipo'=>$this->get('negocio_type')->getArrayAcordingToTypeOf($negocio_current))
        );
    }
    public function showPanelNegocioUserFotosAction(Request $request){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);
        $fotos = $this->getDoctrine()->getRepository('AppBundle:Foto')->findBy(
            array('negocio'=>$negocio_current),
            array('updatedAt'=>'DESC')
        );
        $foto = new Foto();
        $form = $this->createForm(new FotoType(), $foto);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $foto->setNegocio($negocio_current);
            $em->persist($foto);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'foto agregada !');
            return $this->redirectToRoute('profile_negocios_panel_gestion_negocio_cambiar_fotos');
        }
        return $this->render(
            'FOSUserBundle:Profile:Panel/cambiar_fotos.html.twig',
            array('negocio'=>$negocio_current,'fotos'=>$fotos,'form'=>$form->createView(),'tipo'=>$this->get('negocio_type')->getArrayAcordingToTypeOf($negocio_current))
        );
    }
    public function showPanelNegocioUserFotoDeleteAction(Request $request,$id){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $foto = $this->getDoctrine()->getRepository('AppBundle:Foto')->findOneBy(
            array('negocio'=>$negocio_id,'id'=>$id)
        );
        $em = $this->getDoctrine()->getManager();
        $em->remove($foto);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', 'Su foto fue borrada !');
        return $this->redirectToRoute('profile_negocios_panel_gestion_negocio_cambiar_fotos');
    }
    public function showPanelNegocioUserMapaAction(Request $request){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);
        $form = $this->createForm(new GoogleMapType());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $ubicacion = json_decode($data['ubicacion']);

            $negocio_current->setGoogleMapLat($ubicacion->lat);
            $negocio_current->setGoogleMapLng($ubicacion->lng);
            $negocio_current->setGoogleMapFormatedAddress($ubicacion->formatted_address);
            $em = $this->getDoctrine()->getManager();
            $em->persist($negocio_current);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'mapa actualizado !');

            return $this->redirectToRoute('profile_negocios_panel_gestion_negocio_cambiar_mapa');
        }
        return $this->render(
            'FOSUserBundle:Profile:Panel/cambiar_mapa.html.twig',
            array('form'=>$form->createView(),'negocio'=>$negocio_current,'tipo'=>$this->get('negocio_type')->getArrayAcordingToTypeOf($negocio_current))
        );
    }
    public function showPanelNegocioUserListFotosAction(Request $request){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);
        $fotos = $this->getDoctrine()->getRepository('AppBundle:Foto')->findBy(
            array('negocio'=>$negocio_current),
            array('sort' => 'ASC')
        );

        return $this->render(
            'FOSUserBundle:Profile:Panel/sort_fotos.html.twig',
            array('fotos'=>$fotos,'negocio'=>$negocio_current,'tipo'=>$this->get('negocio_type')->getArrayAcordingToTypeOf($negocio_current))
        );
    }
    /** START GESTION PROYECTOS INMUEBLES  **/
    public function showPanelNegocioUserInmueblesAction(Request $request){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:ConstructoraInmobiliaria')->find($negocio_id);

        $inmuebles = $this->getDoctrine()->getRepository('AppBundle:Inmueble')->findBy(
            array('constructora'=>$negocio_current),
            array('sort'=>'ASC')
        );
        return $this->render(
            'FOSUserBundle:Profile:Panel/inmuebles_ver.html.twig',
            array('inmuebles'=>$inmuebles,'negocio'=>$negocio_current,'tipo'=>$this->get('negocio_type')->getArrayAcordingToTypeOf($negocio_current))
        );
    }
    public function showPanelNegocioUserListInmueblesAction(Request $request){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);
        $inmuebles = $this->getDoctrine()->getRepository('AppBundle:Inmueble')->findBy(
            array('constructora'=>$negocio_current),
            array('sort' => 'ASC')
        );

        return $this->render(
            'FOSUserBundle:Profile:Panel/sort_inmuebles.html.twig',
            array('inmuebles'=>$inmuebles,'negocio'=>$negocio_current,'tipo'=>$this->get('negocio_type')->getArrayAcordingToTypeOf($negocio_current))
        );
    }
    public function showPanelNegocioUserInmuebleAddAction(Request $request){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:ConstructoraInmobiliaria')->find($negocio_id);
        $user = $this->container->get('security.context')->getToken()->getUser();
        $inm = new Inmueble();
        $form = $this->createForm(new InmuebleType(null), $inm);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $inm->setUser($user);
            $inm->setConstructora($negocio_current);
            $slug = $this->get('slugify')->slugify($inm->getNombre());
            $inm->setSlug($slug);
            $em = $this->getDoctrine()->getManager();
            $em->persist($inm);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Proyecto agregado !');
            return $this->redirectToRoute('profile_negocios_panel_gestion_negocio_ver_inmuebles');
        }
        return $this->render(
            'FOSUserBundle:Profile:Panel/inmueble_form.html.twig',
            array('form'=>$form->createView(),'negocio'=>$negocio_current,'tipo'=>$this->get('negocio_type')->getArrayAcordingToTypeOf($negocio_current))
        );

    }
    public function showPanelNegocioUserInmuebleEditAction(Request $request,$id){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:ConstructoraInmobiliaria')->find($negocio_id);

        $user = $this->container->get('security.context')->getToken()->getUser();
        $inm = $this->getDoctrine()->getRepository('AppBundle:Inmueble')->find($id);
        $form = $this->createForm(new InmuebleType(null), $inm);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $inm->setUser($user);
            $inm->setConstructora($negocio_current);
            $slug = $this->get('slugify')->slugify($inm->getNombre());
            $inm->setSlug($slug);
            $em = $this->getDoctrine()->getManager();
            $em->persist($inm);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Proyecto actualizado !');
            return $this->redirectToRoute('profile_negocios_panel_gestion_negocio_edit_inmueble',array('id'=>$id));
        }
        return $this->render(
            'FOSUserBundle:Profile:Panel/inmueble_form.html.twig',
            array(
                'form'=>$form->createView(),
                'negocio'=>$negocio_current,
                'inmueble'=>$inm,
                'tipo'=>$this->get('negocio_type')->getArrayAcordingToTypeOf($negocio_current)
            )
        );

    }
    public function showPanelNegocioUserInmuebleDeleteAction(Request $request,$id){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);
        $inm = $this->getDoctrine()->getRepository('AppBundle:Inmueble')->find($id);

        $request->getSession()->getFlashBag()->add('success', 'Servicio agregado !');
        return $this->redirectToRoute('profile_negocios_panel_gestion_negocio_ver_inmuebles_servicios');


    }
    public function showPanelNegocioUserInmuebleGeneralAddAction(Request $request,$id){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);

        $g = $this->getDoctrine()->getRepository('AppBundle:General')->find($id);
        if($g!=null){
            $gi = new GeneralInmueble();
            $gi->setInmueble($negocio_current);
            $gi->setGeneral($g);
            $em = $this->getDoctrine()->getManager();
            $em->persist($gi);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'General agregado !');
            return $this->redirectToRoute('profile_negocios_panel_gestion_negocio_ver_inmuebles_generales');
        }
    }
    public function showPanelNegocioUserInmuebleGeneralDeleteAction(Request $request,$id){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);
        $g = $this->getDoctrine()->getRepository('AppBundle:General')->find($id);
        $gi = $this->getDoctrine()->getRepository('AppBundle:GeneralInmueble')->findOneBy(
            array('inmueble'=>$negocio_current,'general'=>$g)
        );
        if($gi!=null){
            $em = $this->getDoctrine()->getManager();
            $em->remove($gi);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'General eliminado !');
            return $this->redirectToRoute('profile_negocios_panel_gestion_negocio_ver_inmuebles_generales');
        }
    }
    public function showPanelNegocioUserInmuebleGeneralesAction(Request $request){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);

        $generales = $this->getDoctrine()->getRepository('AppBundle:General')->findBy(
            array(),
            array('nombre'=>'ASC')
        );
        return $this->render(
            'FOSUserBundle:Profile:Panel/inmuebles_generales.html.twig',
            array(
                'negocio'=>$negocio_current,
                'generales'=>$generales,
                'tipo'=>$this->get('negocio_type')->getArrayAcordingToTypeOf($negocio_current)
            )
        );
    }
    public function showPanelNegocioUserInmuebleServiciosAction(Request $request){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);

        $servicios = $this->getDoctrine()->getRepository('AppBundle:Servicio')->findBy(
            array(),
            array('nombre'=>'ASC')
        );
        return $this->render(
            'FOSUserBundle:Profile:Panel/inmuebles_servicios.html.twig',
            array(
                'negocio'=>$negocio_current,
                'servicios'=>$servicios,
                'tipo'=>$this->get('negocio_type')->getArrayAcordingToTypeOf($negocio_current)
            )
        );

    }
    public function showPanelNegocioUserInmuebleServicioAddAction(Request $request,$id){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);

        $servicio = $this->getDoctrine()->getRepository('AppBundle:Servicio')->find($id);
        if($servicio!=null){
            $servicioInmueble = new ServicioInmueble();
            $servicioInmueble->setInmueble($negocio_current);
            $servicioInmueble->setServicio($servicio);
            $em = $this->getDoctrine()->getManager();
            $em->persist($servicioInmueble);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Servicio agregado !');
            return $this->redirectToRoute('profile_negocios_panel_gestion_negocio_ver_inmuebles_servicios');
        }

    }
    public function showPanelNegocioUserInmuebleServicioDeleteAction(Request $request,$id){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);
        $servicio = $this->getDoctrine()->getRepository('AppBundle:Servicio')->find($id);
        $servicioInmueble = $this->getDoctrine()->getRepository('AppBundle:ServicioInmueble')->findOneBy(
            array('inmueble'=>$negocio_current,'servicio'=>$servicio)
        );
        if($servicioInmueble!=null){
            $em = $this->getDoctrine()->getManager();
            $em->remove($servicioInmueble);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Servicio eliminado !');
            return $this->redirectToRoute('profile_negocios_panel_gestion_negocio_ver_inmuebles_servicios');
        }
    }
    public function showPanelNegocioUserProyectosAction(Request $request){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);

        $proyectos = $this->getDoctrine()->getRepository('AppBundle:Proyecto')->findBy(
            array('negocio'=>$negocio_current),
            array('sort'=>'ASC')
        );
        return $this->render(
            'FOSUserBundle:Profile:Panel/proyectos.html.twig',
            array(
                'negocio'=>$negocio_current,
                'proyectos'=>$proyectos,
                'tipo'=>$this->get('negocio_type')->getArrayAcordingToTypeOf($negocio_current)
            )
        );
    }
    public function showPanelNegocioUserProyectoDeleteAction(Request $request,$id){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $proyecto = $this->getDoctrine()->getRepository('AppBundle:Proyecto')->find($id);
        if($proyecto!=null){
            $em = $this->getDoctrine()->getManager();
            $em->remove($proyecto);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Proyecto borrado !');
            return $this->redirectToRoute('profile_negocios_panel_gestion_negocio_ver_proyectos');
        }

    }
    public function showPanelNegocioUserProyectoAddAction(Request $request){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);
        $proyecto = new Proyecto();
        $form = $this->createForm(new ProyectoType(), $proyecto);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $proyecto->setNegocio($negocio_current);
            $slug = $this->get('slugify')->slugify($proyecto->getNombre());
            $proyecto->setSlug($slug);
            $em->persist($proyecto);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Proyecto agregado !');
            return $this->redirectToRoute('profile_negocios_panel_gestion_negocio_ver_proyectos');
        }
        return $this->render(
            'FOSUserBundle:Profile:Panel/proyecto_form.html.twig',
            array(
                'form'=>$form->createView(),
                'negocio'=>$negocio_current,
                'tipo'=>$this->get('negocio_type')->getArrayAcordingToTypeOf($negocio_current)
            )
        );

    }
    public function showPanelNegocioUserProyectoFotosEditAction(Request $request,$id){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);
        $proyecto = $this->getDoctrine()->getRepository('AppBundle:Proyecto')->find($id);
        $fotos_proyectos = $this->getDoctrine()->getRepository('AppBundle:FotoProyecto')->findBy(
            array('proyecto'=>$proyecto),
            array('sort'=>'ASC')
        );
        $fp = new FotoProyecto();
        $form = $this->createForm(new FotoProyectoType(), $fp);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fp->setProyecto($proyecto);
            $em = $this->getDoctrine()->getManager();
            //$logo->setProveedor($proveedor);
            $em->persist($fp);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Su foto fue guardada !');
            return $this->redirectToRoute('profile_negocios_panel_gestion_negocio_edit_proyecto_fotos',array('id'=>$id));
        }

        return $this->render(
            'FOSUserBundle:Profile:Panel/proyecto_fotos_edit.html.twig',
            array(
                'form'=>$form->createView(),
                'fotos'=>$fotos_proyectos,
                'negocio'=>$negocio_current,
                'tipo'=>$this->get('negocio_type')->getArrayAcordingToTypeOf($negocio_current)
            )
        );
    }
    public function showPanelNegocioUserProyectoFotoDeleteAction(Request $request,$id){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $fp = $this->getDoctrine()->getRepository('AppBundle:FotoProyecto')->find($id);
        if($fp!=null){
            $id_p = $fp->getProyecto()->getId();
            $em = $this->getDoctrine()->getManager();
            $em->remove($fp);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Foto proyecto borrada !');
            return $this->redirectToRoute('profile_negocios_panel_gestion_negocio_edit_proyecto_fotos',array('id'=>$id_p));
        }
    }
    public function showPanelNegocioUserProyectoEditAction(Request $request,$id){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);
        $proyecto = $this->getDoctrine()->getRepository('AppBundle:Proyecto')->find($id);
        $form = $this->createForm(new ProyectoType(true), $proyecto);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $proyecto->setNegocio($negocio_current);
            $slug = $this->get('slugify')->slugify($proyecto->getNombre());
            $proyecto->setSlug($slug);
            $em->persist($proyecto);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Proyecto actualizado !');
            return $this->redirectToRoute('profile_negocios_panel_gestion_negocio_edit_proyecto',array('id'=>$id));
        }
        return $this->render(
            'FOSUserBundle:Profile:Panel/proyecto_form.html.twig',
            array(
                'form'=>$form->createView(),
                'proyecto'=>$proyecto,
                'negocio'=>$negocio_current,
                'tipo'=>$this->get('negocio_type')->getArrayAcordingToTypeOf($negocio_current)
            )
        );

    }
    public function showPanelNegocioUserListProyectosAction(Request $request){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);
        $proyectos = $this->getDoctrine()->getRepository('AppBundle:Proyecto')->findBy(
            array('negocio'=>$negocio_current),
            array('sort' => 'ASC')
        );

        return $this->render(
            'FOSUserBundle:Profile:Panel/sort_proyectos.html.twig',
            array('proyectos'=>$proyectos,'negocio'=>$negocio_current,'tipo'=>$this->get('negocio_type')->getArrayAcordingToTypeOf($negocio_current))
        );
    }
    public function showPanelNegocioUserProductosAction(Request $request){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);

        $productos = $this->getDoctrine()->getRepository('AppBundle:Producto')->findBy(
            array('negocio'=>$negocio_current),
            array('sort'=>'ASC')
        );
        return $this->render(
            'FOSUserBundle:Profile:Panel/productos.html.twig',
            array(
                'negocio'=>$negocio_current,
                'productos'=>$productos,
                'tipo'=>$this->get('negocio_type')->getArrayAcordingToTypeOf($negocio_current)
            )
        );
    }
    public function showPanelNegocioUserListProductosAction(Request $request){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);
        $productos = $this->getDoctrine()->getRepository('AppBundle:Producto')->findBy(
            array('negocio'=>$negocio_current),
            array('sort' => 'ASC')
        );

        return $this->render(
            'FOSUserBundle:Profile:Panel/sort_productos.html.twig',
            array('productos'=>$productos,'negocio'=>$negocio_current,'tipo'=>$this->get('negocio_type')->getArrayAcordingToTypeOf($negocio_current))
        );
    }
    public function showPanelNegocioUserProductoAddAction(Request $request){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);
        $producto = new Producto();
        $form = $this->createForm(new ProductoType(), $producto);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $producto->setNegocio($negocio_current);
            $slug = $this->get('slugify')->slugify($producto->getNombre());
            $producto->setSlug($slug);
            $em->persist($producto);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Producto agregado !');
            return $this->redirectToRoute('profile_negocios_panel_gestion_negocio_ver_productos');
        }
        return $this->render(
            'FOSUserBundle:Profile:Panel/producto_form.html.twig',
            array(
                'form'=>$form->createView(),
                'negocio'=>$negocio_current,
                'tipo'=>$this->get('negocio_type')->getArrayAcordingToTypeOf($negocio_current)
            )
        );
    }
    public function showPanelNegocioUserProductoEditAction(Request $request,$id){

        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);
        $producto = $this->getDoctrine()->getRepository('AppBundle:Producto')->find($id);
        $form = $this->createForm(new ProductoType(true), $producto);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $producto->setNegocio($negocio_current);
            $slug = $this->get('slugify')->slugify($producto->getNombre());
            $producto->setSlug($slug);
            $em->persist($producto);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Producto actualizado !');
            return $this->redirectToRoute('profile_negocios_panel_gestion_negocio_edit_producto',array('id'=>$id));
        }
        return $this->render(
            'FOSUserBundle:Profile:Panel/producto_form.html.twig',
            array(
                'form'=>$form->createView(),
                'producto'=>$producto,
                'negocio'=>$negocio_current,
                'tipo'=>$this->get('negocio_type')->getArrayAcordingToTypeOf($negocio_current)
            )
        );
    }
    public function showPanelNegocioUserProductoDeleteAction(Request $request,$id){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $producto = $this->getDoctrine()->getRepository('AppBundle:Proyecto')->find($id);
        if($producto!=null){
            $em = $this->getDoctrine()->getManager();
            $em->remove($producto);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Producto borrado !');
            return $this->redirectToRoute('profile_negocios_panel_gestion_negocio_ver_productos');
        }
    }
    public function showPanelNegocioUserProductoFotosEditAction(Request $request,$id){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $negocio_current = $this->getDoctrine()->getRepository('AppBundle:Negocio')->find($negocio_id);
        $producto = $this->getDoctrine()->getRepository('AppBundle:Producto')->find($id);
        $fotos_productos = $this->getDoctrine()->getRepository('AppBundle:FotoProducto')->findBy(
            array('producto'=>$producto),
            array('sort'=>'ASC')
        );
        $fp = new FotoProducto();
        $form = $this->createForm(new FotoProductoType(), $fp);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fp->setProducto($producto);
            $em = $this->getDoctrine()->getManager();
            //$logo->setProveedor($proveedor);
            $em->persist($fp);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Su foto fue guardada !');
            return $this->redirectToRoute('profile_negocios_panel_gestion_negocio_edit_producto_fotos',array('id'=>$id));
        }

        return $this->render(
            'FOSUserBundle:Profile:Panel/producto_fotos_edit.html.twig',
            array(
                'form'=>$form->createView(),
                'fotos'=>$fotos_productos,
                'producto'=>$producto,
                'negocio'=>$negocio_current,
                'tipo'=>$this->get('negocio_type')->getArrayAcordingToTypeOf($negocio_current)
            )
        );
    }
    public function showPanelNegocioUserProductoFotoDeleteAction(Request $request,$id){
        $negocio_id = $this->getRequest()->getSession()->get('negocio_id');
        if($negocio_id==null) return $this->redirectToRoute('profile_negocios_panel');
        $fp = $this->getDoctrine()->getRepository('AppBundle:FotoProducto')->find($id);
        if($fp!=null){
            $id_p = $fp->getProducto()->getId();
            $em = $this->getDoctrine()->getManager();
            $em->remove($fp);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Foto producto borrada !');
            return $this->redirectToRoute('profile_negocios_panel_gestion_negocio_edit_producto_fotos',array('id'=>$id_p));
        }
    }
    public function showPanelNegocioUserSortAction(Request $request){
        if($request->isXmlHttpRequest()) {
            $sort_string = $request->request->get('sort');
            $what = $request->request->get('what');
            $arr = explode('&',$sort_string);
            $arr_ids = array();
            foreach ($arr as $value){
                $temp = explode('=',$value)[1];
                $arr_ids[] = $temp;
            }
            $sort = 0;
            $em = $this->getDoctrine()->getManager();
            foreach ($arr_ids as $obj_id){
                $obj = $this->getDoctrine()->getRepository('AppBundle:'.$what)->find($obj_id);
                $obj->setSort($sort);
                $em->persist($obj);
                $sort++;
            }
            $em->flush();
            return new JsonResponse(array('sort' => $sort));
        }
    }
}