<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ConstructoraInmobiliaria;
use AppBundle\Entity\Especialista;
use AppBundle\Entity\Inmueble;
use AppBundle\Entity\Proveedor;
use AppBundle\Entity\User;
use AppBundle\Form\Type\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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
    public function testAction(Request $request)
    {
        $r = $this->getDoctrine()->getRepository('AppBundle:User')->searchAllUsers('pa');
        $r2 = $this->getDoctrine()->getRepository('AppBundle:Negocio')->searchAllNegocios('pa');
        $merged = array_merge($r, $r2);
        $arr_out = array();
        foreach ($merged as $value){
            $obj = new \stdClass();
            $label = '';
            if($value instanceof User){
                $obj->type = 'Perfil';
                if(!empty($value->getNombreEmpresa())){
                    $label = $value->getNombreEmpresa();
                }
                else{
                    $label = $value->getNombres().' '.$value->getApellidos();
                }
            }
            else if($value instanceof Proveedor){
                $obj->type = 'Proveedor';
                $label = $value->getNombre();
            }
            else if($value instanceof Especialista){
                $obj->type = 'Especialista';
                $label = $value->getNombre();
            }
            else if($value instanceof ConstructoraInmobiliaria){
                $obj->type = 'Constructura e Inmobiliaria';
                $label = $value->getNombre();
            }
            /*else if($value instanceof Inmueble){
                $obj->type = 'Inmueble';
                $label = $value->getNombre();
            }*/
            $obj->value = $value->getId();
            $obj->label = $label;
            $arr_out[] = $obj;
        }
        $response = new JsonResponse($arr_out);
        return $response;
        /*return $this->render('temp.html.twig',array(
            'r'=>$merged,
        ));*/
    }
    public function ubicacionesAction(Request $request)
    {
        $search = $request->query->get('q');
        $out =array();
        $departamentos = $this->getDoctrine()->getRepository('AppBundle:Departamento')->getDepartamentos($search);
        foreach ($departamentos as $d){
            $item = $d->getNombre();
            $out[] = $item;
        }
        $provincias = $this->getDoctrine()->getRepository('AppBundle:Provincia')->getProvincias($search);
        foreach ($provincias as $p){
            $item = $p->getNombre().', '.$p->getDepartamento()->getNombre();
            $out[] = $item;
        }
        $distritos = $this->getDoctrine()->getRepository('AppBundle:Distrito')->getDistritos($search);
        foreach ($distritos as $d){
            $item = $d->getNombre().', '.$d->getProvincia()->getNombre().', '.$d->getProvincia()->getDepartamento()->getNombre();
            $out[] = $item;
        }
        $response = new JsonResponse();
        $response->setData($out);
        return $response;
    }
    public function faqAction(Request $request){
        return $this->render(
            'faq.html.twig'
        );
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
    /**
     * @Route("/provinces", name="select_provinces")
     */
    public function provincesAction(Request $request)
    {
        $departamento_id = $request->request->get('departamento_id');

        $em = $this->getDoctrine()->getManager();
        $provinces = $em->getRepository('AppBundle:Provincia')->findBy(array('departamento'=>$departamento_id));
        $out = array();
        foreach ($provinces as $p){
            $obj = new \stdClass();
            $obj->id = $p->getId();
            $obj->nombre = $p->getNombre();
            $out[] =$obj;
        }
        return new JsonResponse($out);
    }

    /**
     * @Route("/cities", name="select_cities")
     */
    public function citiesAction(Request $request)
    {
        $provincia_id = $request->request->get('provincia_id');

        $em = $this->getDoctrine()->getManager();
        $distritos = $em->getRepository('AppBundle:Distrito')->findBy(array('provincia'=>$provincia_id));
        $out = array();
        foreach ($distritos as $d){
            $obj = new \stdClass();
            $obj->id = $d->getId();
            $obj->nombre = $d->getNombre();
            $out[] =$obj;
        }
        return new JsonResponse($out);
    }
}
