<?php
/**
 * Created by PhpStorm.
 * User: patrici-star
 * Date: 9/15/2016
 * Time: 11:21 AM
 */
namespace AppBundle\Entity;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;

class FOSUBUserProvider extends BaseClass
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

    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {

        $property = $this->getProperty($response);
        $username = $response->getUsername();
        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();
        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';
        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }
        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
        $this->userManager->updateUser($user);
    }
    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {

        $pic = imageCreateFromJpeg($response->getProfilePicture());
        $username = $response->getUsername();
        $nombres = $response->getFirstName();
        $apellidos = $response->getLastName();

        $email = $response->getEmail();
        //$user = $this->userManager->findUserBy(array('facebookId' => $username));
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));
        $user_test_email = null;
        if($user==null){
            $user_test_email = $this->userManager->findUserByEmail($email);
        }


        /*if($user==NULL && $another_user!=NULL){
            echo "Estas ingresando con con facebook con el siguientes email que ya fue
            registrado : <b>".$email."</b> ya fue registrado.<br/>
            ";
            exit;
        }*/
        //when the user is registrating
        if (null === $user && $user_test_email===null) {
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';
            $setter_token = $setter.'AccessToken';

            $path = dirname(dirname(dirname(dirname (__FILE__))));
            $path.="/web/images/users/";
            $filename_out = "userprofilepic_".rand();
            $output_filename = $path.$filename_out;
            $jpeg_quality = 100;
            imagejpeg($pic, $output_filename, $jpeg_quality);

            // create new user here
            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());
            //I have set all requested data with the user's username
            //modify here with relevant data

            $username = $this->slugify($nombres).'.'.$this->slugify($apellidos).'.'.substr(uniqid(),0,5);

            $user->setUsername($username);
            $user->setEmail($email);
            //$user->setPassword($username);

            $user->setNombres($nombres);
            $user->setApellidos($apellidos);
            $user->setEnabled(true);

            $fotoProfile = new FotoProfile();
            $fotoProfile->setUser($user);
            $fotoProfile->setProfileName($filename_out);

            $user->setProfile($fotoProfile);

            $this->userManager->updateUser($user);


            return $user;
        }
        else if(null === $user && $user_test_email!=null){ //if user exists via email allready
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';
            $setter_token = $setter.'AccessToken';
            $user_test_email->$setter_id($username);
            $user_test_email->$setter_token($response->getAccessToken());
            $this->userManager->updateUser($user_test_email);

            return $user_test_email;

        }
        else{ //if user exists via facebook - go with the HWIOAuth way

            $user = parent::loadUserByOAuthUserResponse($response);

            $serviceName = $response->getResourceOwner()->getName();

            $setter = 'set' . ucfirst($serviceName) . 'AccessToken';
            //update access token
            $user->$setter($response->getAccessToken());

            return $user;
        }

    }
}