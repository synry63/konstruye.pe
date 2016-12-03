<?php
/**
 * Created by PhpStorm.
 * User: patrici-star
 * Date: 12/3/2016
 * Time: 9:50 AM
 */
namespace AppBundle\Tool;

use Symfony\Component\Routing\RouterInterface;

class GenerateMenuFilter
{
    protected $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function menuChild($route,$key,$value,$menu,$slug,$search = null){
        $out = '<ul>';
        foreach ($menu as $menu_item){
            $class = "";
            $url = $this->router->generate($route,array($key=>$value,'slug_categoria'=>$menu_item->getSlug(),'search'=>$search));
            if($menu_item->getSlug()==$slug) $class='selected';

            $out.='<li class="'.$class.'"><a href="'.$url.'">'.$menu_item->getNombre().'</a>';
            if($menu_item->getSlug()==$slug){
                $out.= $this->menuChild($route,$key,$value,$menu_item->getChildren(),null,$search);
            }
            if($this->isInside($menu_item->getChildren(),$slug)){
                $out.= $this->menuChild($route,$key,$value,$menu_item->getChildren(),$slug,$search);
            }
            $out.='</li>';
        }
        $out.='</ul>';
        return $out;

    }
    public function menuMain($route_name,$key,$value,$menu,$search = null){
        $out = '<ul>';
        foreach ($menu as $menu_item){
            $url = $this->router->generate($route_name,array($key=>$value,'slug_categoria'=>$menu_item->getSlug(),'search'=>$search));
            $out.='<li>';
            $out.='<a href="'.$url.'">'.$menu_item->getNombre().'</a>';
            $out.='</li>';
        }
        $out.='</ul>';

        return $out;
    }
    private function isInside($menu,$slug){
        $trouve = false;
        //while(count($menu->getChildren())>0 && $trouve==false){
        foreach ($menu as $menu_item){
            if($menu_item->getSlug()==$slug && $trouve==false){
                //$trouve = true;
                return true;
                //break;
            }
            if(count($menu_item->getChildren())>0 && $trouve==false)
                $trouve = $this->isInside($menu_item->getChildren(),$slug);
        }

        //}
        return $trouve;
    }
}
