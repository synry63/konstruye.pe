<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Inmueble;
use AppBundle\Form\Type\ContactType;
use AppBundle\Form\Type\InmuebleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class InmuebleController extends Controller
{
    /**
     * @param $text
     * @return mixed|string
     * slugify a text
     */
    private function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }
    public function registerConfirmationAction(Request $request){

        return $this->render('FOSUserBundle:Profile:negocio_register_confirmation.html.twig');
    }
    public function registerAction(Request $request){ // FIX ERROR ON /home/pmary/www/html/konstruye.pe/vendor/vich/uploader-bundle/Metadata/MetadataReader.php


        $user = $this->container->get('security.context')->getToken()->getUser();
        $inm = new Inmueble();

        $form = $this->createForm(new InmuebleType(), $inm);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $inm->setUser($user);
            $inm->setSlug($this->slugify($inm->getNombre()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($inm);
            $em->flush();
            //$request->getSession()->getFlashBag()->add('success', 'Gracias por registrarte !');
            return $this->redirectToRoute('negocio_register_confirmation');
        }

        return $this->render(
            'FOSUserBundle:Profile:register_inmueble.html.twig',array(
                'form'=>$form->createView()
            )
        );
    }
}
