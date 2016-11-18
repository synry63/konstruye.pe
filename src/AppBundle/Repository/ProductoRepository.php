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

    public function getProductosBy($search,$c = null){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p as producto,avg(cp.nota) as mymoy')
            ->from('AppBundle\Entity\Producto', 'p')
            ->leftJoin('p.comentarios','cp');
        $qb->where($qb->expr()->like('p.nombre', ':search'))
            ->setParameter('search', '%' . $search . '%');

        if($c!=null){
            $qb->join('p.categoriasListado','pc')
                ->andWhere('pc.slug = :categoria')
                ->setParameter('categoria', $c);
        }
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
    public function getProductosByNegocioCategoria($n,$c){
        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder();
        $qb->select('p as producto,avg(cp.nota) as mymoy')
            ->from('AppBundle\Entity\Producto', 'p')
            ->leftJoin('p.comentarios','cp')
            ->where('p.negocio = :negocio')
            ->setParameter('negocio', $n);

            $qb->join('p.categoriasListado','pc')
                ->andWhere('pc.slug = :categoria')
                ->setParameter('categoria', $c);

        $qb->addGroupBy('p');
        $query = $qb->getQuery();

        return $query;
    }
    public function getProductosByNegocio($n,$orderBy = NULL){
        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder();
        $qb->select('p as producto,avg(cp.nota) as mymoy')
            ->from('AppBundle\Entity\Producto', 'p')
            ->leftJoin('p.comentarios','cp')
            ->where('p.negocio = :negocio')
            ->setParameters(array(
                'negocio' => $n,
            ));
        if($orderBy=='moy')
            $qb->addOrderBy('mymoy', 'DESC');
        $qb->addGroupBy('p');
        $query = $qb->getQuery();

        return $query;
    }
    public function getProductosRelated($producto,$limit){
        $tags = $producto->getTags();
        $tags_arr = explode(',',$tags);
        $em = $this->getEntityManager();
        $dql = 'SELECT p as producto,avg(cp.nota) as mymoy FROM AppBundle\Entity\Producto p';
        $dql.= ' LEFT JOIN p.comentarios cp';
        $dql.= ' WHERE p != :producto';
        $dql.= ' AND (';
        for($i=0;$i<count($tags_arr);$i++){
            //$dql.= ' AND (p.tags LIKE ?1 OR p.tags LIKE ?2)';
            $dql.= ' p.tags LIKE ?'.$i.' OR';
        }
        $dql = substr($dql, 0, -2);
        $dql.= ')';
        $dql.= ' GROUP BY p';
        $query = $em->createQuery($dql);
        //$test_arr = array('producto'=>$producto,0 =>'%toto%',1 => '%mar%');
        $arr_params = array('producto'=>$producto);
        foreach($tags_arr as $tag){
            $arr_params[] = '%'.$tag.'%';
        }
        $query->setParameters($arr_params);
        if($limit!=null)
            $query->setMaxResults($limit);;
        return $query->getResult();
        /*$qb = $em->createQueryBuilder();
        $qb->select('p as producto,avg(cp.nota) as mymoy')
            ->from('AppBundle\Entity\Producto', 'p')
            ->leftJoin('p.comentarios','cp')
            ->where('p != :producto')
            ->setParameter('producto', $producto);*/
            //->andWhere($qb->expr()->like('p.tags', ':search'))
            //->setParameter('search', '%' . $search . '%');
            //->setParameter('producto', $producto);
        //$ors[] = $qb->expr()->orx('p.tags LIKE '.$qb->expr()->literal('super'));
        //$ors[] = $qb->expr()->orx($qb->expr()->like('p.tags', ':?2'));
        //$qb->andWhere($ors);
        //$qb->setParameter('?2', '%super%');

        //$qb->addGroupBy('p');
        //$query = $qb->getQuery();
        //$result = $query->getResult();

        return $result;
            //->setParameter('distrito', $lugar[0]);
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