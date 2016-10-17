<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 6/2/16
 * Time: 12:01 PM
 */
namespace AppBundle\Controller;


use AppBundle\Entity\FotoProfile;
use AppBundle\Form\Type\FotoProfileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;



class UserController extends Controller
{



    public function userCommentEditAction($slug_negocio,Request $request){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $negocio = $this->getDoctrine()->getRepository('AppBundle:Negocio')->findOneBy(array('slug'=>$slug_negocio));

        if(is_object($user)){
            $comentarioNegocio = $this->getDoctrine()->getRepository('AppBundle:ComentarioNegocio')
                ->findOneBy(array('negocio'=>$negocio,'user'=>$user));
            //$comentarioProveedor = new ComentarioProveedor();

            $form = $this->createForm(new ComentarioProveedorType(),$comentarioNegocio,array(
                'action' => $this->generateUrl('profile_negocio_comentar_edit',array('slug_negocio'=>$slug_negocio)),
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
                    $url = $this->generateUrl('show_profile_comments');
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

    public function userCommentsShowAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $ccoments = $this->getDoctrine()->getRepository('AppBundle:ComentarioNegocio')->findBy(array('user'=>$user));
        return $this->render(
            'FOSUserBundle:Profile:show_comments.html.twig',
            array(
                'comentarios'=>$ccoments,
            )
        );

    }
    public function showProfileAction(){
        $user = $this->container->get('security.context')->getToken()->getUser();

        return $this->render(
            'FOSUserBundle:includes:profile_image_left.html.twig'
        );
    }
    public function userFavoritosFotosShowAction(){

        $user = $this->container->get('security.context')->getToken()->getUser();
        $fotos = $this->getDoctrine()->getRepository('AppBundle:FotoUserGusta')
            ->findBy(
                array('user'=>$user),
                array('updatedAt'=>'DESC')
            );


        return $this->render(
            'FOSUserBundle:Profile:show_fotos_favoritos.html.twig',
            array(
                'fotos_proveedor_favoritas'=>$fotos,
            )
        );
    }

    public function userFavoritosFotosInspiracionesShowAction(){

        $user = $this->container->get('security.context')->getToken()->getUser();
        $fotos = $this->getDoctrine()->getRepository('AppBundle:InspiracionUserGusta')
            ->findBy(
                array('user'=>$user),
                array('updatedAt'=>'DESC')
            );
        return $this->render(
            'FOSUserBundle:Profile:show_fotos_inspiraciones_favoritos.html.twig',
            array(
                'fotos_inspiraciones_favoritas'=>$fotos,
            )
        );
    }

    public function userFavoritosProveedoresShowAction(){

        $user = $this->container->get('security.context')->getToken()->getUser();
        /*$proveedores = $this->getDoctrine()->getRepository('AppBundle:UserProveedorGusta')
            ->findBy(
                array('user'=>$user),
                array('updatedAt'=>'DESC')
            );
        */
        $categoria_wdding = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>'wedding'));
        $categoria_dinner = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>'dinner'));
        $categoria_kids = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>'kids'));
        $categoria_party = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>'party'));

        $proveedores_w = $this->getDoctrine()->getRepository('AppBundle:User')->getProveedoresFavotiros($user,$categoria_wdding);
        $proveedores_d = $this->getDoctrine()->getRepository('AppBundle:User')->getProveedoresFavotiros($user,$categoria_dinner);
        $proveedores_k = $this->getDoctrine()->getRepository('AppBundle:User')->getProveedoresFavotiros($user,$categoria_kids);
        $proveedores_p = $this->getDoctrine()->getRepository('AppBundle:User')->getProveedoresFavotiros($user,$categoria_party);
        /*foreach ($proveedores as $p){
            foreach($p->getCategoriasListado() as $c){
                //$c_p = $c->getParent();
                if($c->getParent()!=NULL){
                    $c_p = $c->getParent();
                    if($c_p->slug=='wedding'){
                        $proveedores_w[] = $p;
                    }
                }
            }
        }*/
        return $this->render(
            'FOSUserBundle:Profile:show_proveedores_favoritos.html.twig',
            array(
                'proveedores_favoritos_w'=>$proveedores_w,
                'proveedores_favoritos_d'=>$proveedores_d,
                'proveedores_favoritos_k'=>$proveedores_k,
                'proveedores_favoritos_p'=>$proveedores_p,
            )
        );
    }


    public function userFavoritosVotosShowAction(){

        $user = $this->container->get('security.context')->getToken()->getUser();
        $votos = $this->getDoctrine()->getRepository('AppBundle:VotoUserGusta')
            ->findBy(
                array('user'=>$user),
                array('updatedAt'=>'DESC')
            );
        return $this->render(
            'FOSUserBundle:Profile:show_votos_favoritos.html.twig',
            array(
                'votos'=>$votos,
            )
        );
    }

    public function userFotoProfileShowAction(Request $request){

        $user = $this->container->get('security.context')->getToken()->getUser();
        $profile = $user->getProfile();

        if($profile==NULL){
            $profile = new FotoProfile();
            $profile->setUser($user);
        }

        $form = $this->createForm(new FotoProfileType(), $profile);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //$logo->setProveedor($proveedor);
            $em->persist($profile);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Your foto had change !');
            return $this->redirectToRoute('change_user_foto');
        }

        return $this->render(
            'FOSUserBundle:Profile:change_foto.html.twig',
            array(
                'profile' => $profile,
                'form' => $form->createView())
        );
    }
    /**
     * @Route("/profile/image-processing", name="processing_user_foto")
     */
    public function userFotoProfileProcessingShowAction(Request $request){
        $imagePath = $this->get('kernel')->getRootDir() . '/../web/images/users/temps/' . $this->getRequest()->getBasePath();


        $allowedExts = array("gif", "jpeg", "jpg", "png", "GIF", "JPEG", "JPG", "PNG");
        $temp = explode(".", $_FILES["img"]["name"]);
        $extension = end($temp);


        //Check write Access to Directory

        if(!is_writable($imagePath)){
            $response = Array(
                "status" => 'error',
                "message" => 'Can`t upload File; no write Access'
            );
            print json_encode($response);
            return;
        }

        if ( in_array($extension, $allowedExts))
        {
            if ($_FILES["img"]["error"] > 0)
            {
                $response = array(
                    "status" => 'error',
                    "message" => 'ERROR Return Code: '. $_FILES["img"]["error"],
                );
            }
            else
            {

                $filename = $_FILES["img"]["tmp_name"];
                list($width, $height) = getimagesize( $filename );
                $rand = rand();
                move_uploaded_file($filename,  $imagePath . $rand);
                $my_path = '/images/users/temps/';
                $response = array(
                    "status" => 'success',
                    "url" => $my_path.$rand,
                    "width" => $width,
                    "height" => $height
                );

            }
        }
        else
        {
            $response = array(
                "status" => 'error',
                "message" => 'something went wrong, most likely file is to large for upload. check upload_max_filesize, post_max_size and memory_limit in you php.ini',
            );
        }

        print json_encode($response);
        exit;
    }
    /**
     * @Route("/profile/image-crop", name="crop_user_foto")
     */
    public function userFotoProfileCropShowAction(Request $request){
        $imgUrl = $_POST['imgUrl'];
        $pieces = explode("/", $imgUrl);
        $filename = $pieces[count($pieces)-1];
// original sizes
        $imgInitW = $_POST['imgInitW'];
        $imgInitH = $_POST['imgInitH'];
// resized sizes
        $imgW = $_POST['imgW'];
        $imgH = $_POST['imgH'];
// offsets
        $imgY1 = $_POST['imgY1'];
        $imgX1 = $_POST['imgX1'];
// crop box
        $cropW = $_POST['cropW'];
        $cropH = $_POST['cropH'];
// rotation angle
        $angle = $_POST['rotation'];

        $jpeg_quality = 100;
        $path = $this->get('kernel')->getRootDir() . '/../web/images/users/' . $this->getRequest()->getBasePath();
        $filename_out = "userprofilepic_".rand();
        $output_filename = $path.$filename_out;


// uncomment line below to save the cropped image in the same location as the original image.
//$output_filename = dirname($imgUrl). "/croppedImg_".rand();

        $what = getimagesize($path.'temps/'.$filename);
        switch(strtolower($what['mime']))
        {
            case 'image/png':
                //$img_r = imagecreatefrompng($path.'temps/'.$filename);
                $source_image = imagecreatefrompng($path.'temps/'.$filename);
                $type = '.png';
                break;
            case 'image/jpeg':
                //$img_r = imagecreatefromjpeg($path.'temps/'.$filename);
                $source_image = imagecreatefromjpeg($path.'temps/'.$filename);
                error_log("jpg");
                $type = '.jpeg';
                break;
            case 'image/gif':
                //$img_r = imagecreatefromgif($path.'temps/'.$filename);
                $source_image = imagecreatefromgif($path.'temps/'.$filename);
                $type = '.gif';
                break;
            default: die('image type not supported');
        }


//Check write Access to Directory

        if(!is_writable(dirname($output_filename))){
            $response = Array(
                "status" => 'error',
                "message" => 'Can`t write cropped File'
            );
        }else{

            // resize the original image to size of editor
            $resizedImage = imagecreatetruecolor($imgW, $imgH);
            imagecopyresampled($resizedImage, $source_image, 0, 0, 0, 0, $imgW, $imgH, $imgInitW, $imgInitH);
            // rotate the rezized image
            $rotated_image = imagerotate($resizedImage, -$angle, 0);
            // find new width & height of rotated image
            $rotated_width = imagesx($rotated_image);
            $rotated_height = imagesy($rotated_image);
            // diff between rotated & original sizes
            $dx = $rotated_width - $imgW;
            $dy = $rotated_height - $imgH;
            // crop rotated image to fit into original rezized rectangle
            $cropped_rotated_image = imagecreatetruecolor($imgW, $imgH);
            imagecolortransparent($cropped_rotated_image, imagecolorallocate($cropped_rotated_image, 0, 0, 0));
            imagecopyresampled($cropped_rotated_image, $rotated_image, 0, 0, $dx / 2, $dy / 2, $imgW, $imgH, $imgW, $imgH);
            // crop image into selected area
            $final_image = imagecreatetruecolor($cropW, $cropH);
            imagecolortransparent($final_image, imagecolorallocate($final_image, 0, 0, 0));
            imagecopyresampled($final_image, $cropped_rotated_image, 0, 0, $imgX1, $imgY1, $cropW, $cropH, $cropW, $cropH);
            // finally output png image
            //imagepng($final_image, $output_filename.$type, $png_quality);
            imagejpeg($final_image, $output_filename.$type, $jpeg_quality);
            $my_path = '/images/users/';
            $response = Array(
                "status" => 'success',
                "message" => 'cambio realizado, en unos segundo la pagina se va actualizar para visualizar el cambio',
                "url" => $my_path.$filename_out.$type,
                "targetUrl" => $this->get('router')->generate('fos_user_profile_show')
            );

            // PERSISTANCE
            $user = $this->container->get('security.context')->getToken()->getUser();
            $fotoProfile = $user->getProfile();
            if($fotoProfile==NULL){
                $fotoProfile = new FotoProfile();
                $fotoProfile->setUser($user);
            }
            $fotoProfile->setProfileName($filename_out.$type);
            $em = $this->getDoctrine()->getManager();
            $em->persist($fotoProfile);
            $em->flush();
        }
        print json_encode($response);
        exit;
    }
    public function createNegociosUserAction(Request $request){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $negocios = $user->getNegocios();

        return $this->render(
            'FOSUserBundle:Profile:negocios_selection.html.twig',
            array(
                'negocios' => $negocios
        ));
    }
    public function showNegociosUserAction(Request $request){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $negocios = $this->getDoctrine()->getRepository('AppBundle:Negocio')
            ->findBy(
                array('user'=>$user),
                array('registeredAt'=>'DESC')
            );
        return $this->render(
            'FOSUserBundle:Profile:show_negocios.html.twig',
            array(
                'negocios' => $negocios
            ));
    }
    public function showUserSettingsAction(Request $request){
        return $this->render('FOSUserBundle:Profile:show_settings.html.twig');
    }
}