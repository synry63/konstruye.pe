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

    public function getProductosBy($search){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p as producto,avg(pc.nota) as mymoy')
            ->from('AppBundle\Entity\Producto', 'p')
            ->leftJoin('p.comentarios','pc');
        $qb->where($qb->expr()->like('p.nombre', ':search'))
            ->setParameter('search', '%' . $search . '%');

        $qb->addGroupBy('p');
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
    public function getProductosByNegocio($n){
        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder();
        $qb->select('p as producto,avg(cp.nota) as mymoy')
            ->from('AppBundle\Entity\Producto', 'p')
            ->leftJoin('p.comentarios','cp')
            ->where('p.negocio = :negocio')
            ->setParameters(array(
                'negocio' => $n,
            ));
        //$qb->addOrderBy('mymoy', 'DESC');
        $qb->addGroupBy('p');
        $query = $qb->getQuery();

        return $query;
    }
    public function getProductoRating($producto){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('avg(cp.nota) as mymoy')
            ->from('AppBundle\Entity\Producto', 'p')
            ->join('p.comentarios','cp')
            ->where('p = :producto')
            ->setParameter('producto', $producto);
        $qb->addGroupBy('p');
        $query = $qb->getQuery();
        $result = $query->getOneOrNullResult();
        if($result!=null) $result = $result['mymoy'];
        else{
            $result = 0;
        }
        return $result;
    }
}