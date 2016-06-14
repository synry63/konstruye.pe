<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/8/16
 * Time: 1:13 PM
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class ProductoRepository extends EntityRepository
{

    public function getProductos($search){
        $qb = $this->createQueryBuilder('p');
        $qb->where($qb->expr()->like('p.nombre', ':search'))
            ->setParameter('search', '%' . $search . '%');
        $query = $qb->getQuery();
        //$negocios = $query->getResult();
        return $query;
    }
}