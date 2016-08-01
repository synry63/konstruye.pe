<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/25/16
 * Time: 11:33 AM
 */
namespace MyUserBundle\Handler;

use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AuthenticationException;


class AuthenticationHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface, LogoutSuccessHandlerInterface
{

    protected $router;
    protected $security;
    protected $userManager;
    protected $service_container;

    public function __construct(RouterInterface $router, SecurityContext $security, $userManager, $service_container)
    {
        $this->router = $router;
        $this->security = $security;
        $this->userManager = $userManager;
        $this->service_container = $service_container;

    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {


        if ($request->isXmlHttpRequest()) {
            $target_path = $request->getSession()->get('_security.main.target_path');
            if($target_path!=NULL){
                $targetUrl = $target_path;
            }
            else{
                $targetUrl = $request->headers->get('referer');
            }
            $result = array('success' => true,'targetUrl'=>$targetUrl);
            $response = new Response(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        else {
            $target_path = $request->getSession()->get('_security.main.target_path');
            if($target_path==NULL){
                $target_path = $this->router->generate('fos_user_profile_show');
            }
            return new RedirectResponse($target_path);
        }
    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {

        if ($request->isXmlHttpRequest()) {
            $result = array('success' => false, 'message' => ' Credenciales incorrectas'); // $exception->getMessage()
            $response = new Response(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        else{
            $request->getSession()->getFlashBag()->add('error', ' Credenciales incorrectas'); // $exception->getMessage()
            //$request->getSession()->setFlash('error', $exception->getMessage());
            $url = $this->router->generate('fos_user_security_login');
            //return $this->router->redirectToRoute('fos_user_security_login');
            return new RedirectResponse($url);
        }


    }
    public function onLogoutSuccess(Request $request)
    {

        //$referer = $request->headers->get('referer');
        //$request->getSession()->setFlash('success', 'Wylogowano');
        $target_path = $this->router->generate('homepage');

        return new RedirectResponse($target_path);
    }
}