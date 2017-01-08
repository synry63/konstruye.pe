<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/18/16
 * Time: 1:14 PM
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class CategoriaListadoProductoRepository extends EntityRepository
{
    function getCategoriasMainFromProducts($arr_ids){

        $qb = $this->createQueryBuilder('c')
            ->join('c.productos','cp')
            ->where("cp IN (:productIds)")
            ->andWhere("c.parent is NULL")
            ->setParameter('productIds', $arr_ids);

        $query = $qb->getQuery();

        return $query->getResult();
    }
    function getCategoriasMain(){
        $qb = $this->createQueryBuilder('c')
            ->where("c.parent is NULL");

        $query = $qb->getQuery();

        return $query->getResult();
    }
    function getCategoriasChildren($type,$order = "ASC"){
        $cate =  $this->findOneBy(array('slug'=>$type));
        $qb = $this->createQueryBuilder('c')
            ->where('c.parent = :parent')
            ->setParameter('parent', $cate)
            ->orderBy('c.slug', $order);

        $query = $qb->getQuery();

        return $query->getResult();
    }
}