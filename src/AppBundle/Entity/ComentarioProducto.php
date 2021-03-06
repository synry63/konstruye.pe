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
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ComentarioProductoRepository")
 * @ORM\Table(name="comentarios_productos")
 */
class ComentarioProducto
{

    /**
     * @ORM\Id @ORM\ManyToOne(targetEntity="User")
     **/
    private $user;

    /**
     * @ORM\Id @ORM\ManyToOne(targetEntity="Producto",inversedBy="comentarios")
     **/
    private $producto;
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $titulo;

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
     * @return \DateTime
     */
    public function getAdedAt()
    {
        return $this->adedAt;
    }

    /**
     * @param \DateTime $adedAt
     */
    public function setAdedAt($adedAt)
    {
        $this->adedAt = $adedAt;
    }

    /**
     * @param mixed $producto
     */
    public function setProducto($producto)
    {
        $this->producto = $producto;
    }

    /**
     * @return mixed
     */
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * @return mixed
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * @param mixed $titulo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }


    /**
     * @param mixed $comentario
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;
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