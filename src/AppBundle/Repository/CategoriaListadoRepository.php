<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/18/16
 * Time: 1:14 PM
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class CategoriaListadoRepository extends EntityRepository
{
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