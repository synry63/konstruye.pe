<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/17/16
 * Time: 1:44 PM
 */
namespace AppBundle\Controller;

use AppBundle\Entity\Banner;
use AppBundle\Entity\ComentarioNegocio;
use AppBundle\Entity\ConstructoraInmobiliaria;
use AppBundle\Entity\Especialista;
use AppBundle\Entity\Inmueble;
use AppBundle\Entity\Logo;
use AppBundle\Entity\Negocio;
use AppBundle\Entity\Proveedor;
use AppBundle\Form\Type\ComentarioNegocioType;
use AppBundle\Form\Type\EspecialistaType;
use AppBundle\Form\Type\ProveedorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Ivory\GoogleMap\Overlays\Marker;
use Ivory\GoogleMap\Overlays\Animation;
use Ivory\GoogleMap\Places\Autocomplete;
use Ivory\GoogleMap\Places\AutocompleteComponentRestriction;
use Ivory\GoogleMap\Places\AutocompleteType;
use Ivory\GoogleMap\Helper\Places\AutocompleteHelper;
use Ivory\GoogleMap\Overlays\InfoWindow;
use Ivory\GoogleMap\Events\MouseEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class EspecialistaController extends Controller
{
    public function registerConfirmationAction(Request $request){

        return $this->render('FOSUserBundle:Profile:negocio_register_confirmation.html.twig');
    }
    public function registerAction(Request $request){ // FIX ERROR ON /home/pmary/www/html/konstruye.pe/vendor/vich/uploader-bundle/Metadata/MetadataReader.php

        $user = $this->container->get('security.context')->getToken()->getUser();
        $esp = new Especialista();

        $form = $this->createForm(new EspecialistaType(), $esp);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $esp->setUser($user);
            $slug = $this->get('slugify')->slugify($esp->getNombre());
            $esp->setSlug($slug);
            $em = $this->getDoctrine()->getManager();
            $em->persist($esp);
            $em->flush();
            //$request->getSession()->getFlashBag()->add('success', 'Gracias por registrarte !');
            return $this->redirectToRoute('negocio_register_confirmation');
        }

        return $this->render(
            'FOSUserBundle:Profile:register_especialista.html.twig',array(
                'form'=>$form->createView()
            )
        );
    }
}