<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/17/16
 * Time: 12:58 PM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ConstructoraInmobiliariaRepository")
 */
class ConstructoraInmobiliaria extends Negocio
{
    /**
     * @ORM\OneToMany(targetEntity="Inmueble", mappedBy="constructora")
     */
    private $inmuebles;

    /**
     * @return mixed
     */
    public function getInmuebles()
    {
        return $this->inmuebles;
    }

    /**
     * @param mixed $inmuebles
     */
    public function setInmuebles($inmuebles)
    {
        $this->inmuebles = $inmuebles;
    }


}