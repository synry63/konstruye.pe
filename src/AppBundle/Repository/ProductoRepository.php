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

        return $query;
    }
    public function searchProductosNames($search){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p.nombre as nombre')
            ->from('AppBundle\Entity\Producto', 'p')
            ->where($qb->expr()->like('p.nombre', ':search'))
            ->setParameter('search', '%' . $search . '%');
        $qb->addOrderBy('nombre', 'ASC');
        $query = $qb->getQuery();
        $productos = $query->getResult();

        $nombres = array_column($productos, 'nombre');
        return $nombres;
    }
}