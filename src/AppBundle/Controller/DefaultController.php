<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\ContactType;
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

    public function contactoAction(Request $request)
    {
        $form = $this->createForm(new ContactType());
        $form->handleRequest($request);

        if ($form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();

            // send email to admin
            /*$message = \Swift_Message::newInstance();
            $imgUrl = $message->embed(\Swift_Image::fromPath('http://konstruye.pe/images/register_logo.png'));
            $message->setSubject('The Event Planner - Formulario Contacto')
                ->setFrom(array('sistema@konstruye.pe'=>'Konstruye'))
                ->setTo('sebastianhw@konstruye.pe')
                ->setBody(
                    $this->renderView(
                        'emails/contacto_admin.html.twig',
                        array(
                            'nombre' => $data['name'],
                            'email' => $data['email'],
                            'asunto' => $data['subject'],
                            'telefono' => $data['tel'],
                            'mensaje' => $data['message'],
                            'logo'=>$imgUrl
                        )
                    )
                );
            */
            // send email to user as auto responder
            $message_user = \Swift_Message::newInstance();
            $imgUrl_user = $message_user->embed(\Swift_Image::fromPath('http://konstruye.pe/images/register_logo.png'));
            $message_user->setSubject('Formulario de Contacto')
                ->setFrom(array('sistema@konstruye.pe'=>'Konstruye'))
                ->setTo($data['email'])
                ->setBody(
                    $this->renderView(
                        'emails/contacto_user.html.twig',
                        array(
                            'nombre' => $data['name'],
                            'email' => $data['email'],
                            'asunto' => $data['subject'],
                            'telefono' => $data['tel'],
                            'mensaje' => $data['message'],
                            'logo'=>$imgUrl_user
                        )
                    )
                );
            $message_user->setContentType("text/html");
            $this->get('mailer')->send($message_user);

            //$message->setContentType("text/html");
            //$this->get('mailer')->send($message);

            $request->getSession()->getFlashBag()->add('success', '¡Tu correo ha sido enviado! ¡Gracias!');

            $routeName = $request->get('_route');

            return $this->redirect($this->generateUrl($routeName));
        }

        return $this->render(
            'contactenos.html.twig',
            array('form' => $form->createView())
        );
    }
}
