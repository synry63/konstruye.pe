<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/7/16
 * Time: 1:50 PM
 */
namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="usuarios")
 */
class User extends BaseUser
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Negocio", mappedBy="user")
     */
    private $negocios;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * @param mixed $negocios
     */
    public function setNegocios($negocios)
    {
        $this->negocios = $negocios;
    }

    /**
     * @return mixed
     */
    public function getNegocios()
    {
        return $this->negocios;
    }

}