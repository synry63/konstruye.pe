<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        $imobiliarias = $this->getDoctrine()->getRepository('AppBundle:ConstructoraInmobiliaria')->getBestNegocios();
        $proveedores = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->getBestNegocios();
        $especialistas = $this->getDoctrine()->getRepository('AppBundle:Especialista')->getBestNegocios();
        $inmuebles = $this->getDoctrine()->getRepository('AppBundle:Inmueble')->getBestNegocios();

        return $this->render('index.html.twig',array(
            'imobiliarias'=>$imobiliarias,
            'proveedores'=>$proveedores,
            'especialistas'=>$especialistas,
            'inmuebles'=>$inmuebles
        ));
    }
}
