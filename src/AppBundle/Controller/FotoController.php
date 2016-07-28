<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 7/12/16
 * Time: 10:45 AM
 */
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FotoController extends Controller
{
    public function showProyectoFotosAction(Request $request,$id)
    {

        $fotos = $this->getDoctrine()->getRepository('AppBundle:FotoProyecto')->findBy(
            array('proyecto'=>$id),
            array('updatedAt' => 'desc')
        );

        return $this->render('temp5.html.twig',array(
            'fotos'=>$fotos,
        ));
    }
}