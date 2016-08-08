<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/16/16
 * Time: 4:05 PM
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class ComentarioProductoRepository extends EntityRepository
{
    public function getAllComments($producto){

        $result = $this->findBy(array('producto' => $producto));
        return $result;
    }
}