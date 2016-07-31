<?php
/**
 * Created by PhpStorm.
 * User: patrici-star
 * Date: 7/30/2016
 * Time: 9:38 AM
 */
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProyectoController extends Controller
{
    public function lastProyectosAction($id_negocio,$max = 4)
    {
        $proyectos = $this->getDoctrine()->getRepository('AppBundle:Proyecto')->findBy(
            array('negocio'=>$id_negocio),
            array('registeredAt' => 'DESC'),
            $max
        );

        return $this->render(
            'includes/proyectos_last.html.twig',
            array('proyectos' => $proyectos)
        );
    }
}