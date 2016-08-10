<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/7/16
 * Time: 1:49 PM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ComentarioNegocioRepository")
 * @ORM\Table(name="comentarios_negocios")
 */
class ComentarioNegocio
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="User")
     **/
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Negocio",inversedBy="comentarios")
     **/
    private $negocio;


    /** @ORM\Column(type="decimal",precision=10,scale=2)
     *  @Assert\NotBlank()
     *  @Assert\GreaterThan(
     *     value = 0
     *  )
     *  @Assert\LessThanOrEqual(
     *     value = 5
     * )
     **/
    private $nota;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $titulo;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $comentario;
    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $adedAt;

    /**
     * @param mixed $comentario
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;
    }

    /**
     * @param mixed $adedAt
     */
    public function setAdedAt($adedAt)
    {
        $this->adedAt = $adedAt;
    }

    /**
     * @return mixed
     */
    public function getAdedAt()
    {
        return $this->adedAt;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $titulo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    /**
     * @return mixed
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * @return mixed
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * @param mixed $nota
     */
    public function setNota($nota)
    {
        $this->nota = $nota;
    }

    /**
     * @return mixed
     */
    public function getNota()
    {
        return $this->nota;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $proveedor
     */
    public function setNegocio($proveedor)
    {
        $this->negocio = $proveedor;
    }

    /**
     * @return mixed
     */
    public function getNegocio()
    {
        return $this->negocio;
    }


    public function __construct() {
        $this->adedAt = new \DateTime('now');
    }
}