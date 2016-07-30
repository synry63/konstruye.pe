<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/8/16
 * Time: 1:13 PM
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class NegocioRepository extends EntityRepository
{
    /**
     * @param int $limit
     * @return mixed
     */
    public function getBestNegocios($parent_category,$limit = 5){

        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder();
        $qb->select('p as negocio,avg(cp.nota) as mymoy')
            ->from('AppBundle\Entity\Proveedor', 'p')
            ->join('p.comentarios','cp')
            ->join('p.categoriasListado','cl')
            ->where('cl.parent = :cate')
            ->setMaxResults( $limit )
            ->setParameter('cate', $parent_category);
        $qb->addOrderBy('mymoy', 'DESC');
        $qb->addGroupBy('p');
        $query = $qb->getQuery();

        return $query->getResult();
    }
    public function getNegociosBy($search){

        $qb = $this->createQueryBuilder('n');
        $qb->where($qb->expr()->like('n.nombre', ':search'))
           ->setParameter('search', '%' . $search . '%');
        $qb->orWhere($qb->expr()->like('n.tags', ':search'))
            ->setParameter('search', '%' . $search . '%');
        $query = $qb->getQuery();
        //$negocios = $query->getResult();
        return $query;
    }
    public function getNegociosOrderRecent(){
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.registeredAt', 'DESC')
            ->where('n.isActive = :state')
            ->setParameter('state', true)
        ;

        $query = $qb->getQuery();

        return $query;
    }
    /**
     * @param $cate
     * @return mixed
     */
    public function getNegociosByCategory($cate){


        $qb = $this->createQueryBuilder('p')
            ->join('p.categoriasListado','cl')
            ->where('cl = :cate')
            ->andWhere('p.isActive = :state')
            ->setParameters(array(
                'cate' => $cate,
                'state'=>true
            ));
        $query = $qb->getQuery();


        return $query;
    }

    /**
     * @param $negocio
     * @return mixed
     */
    public function getNegocioRating($negocio){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('avg(cn.nota) as mymoy')
            ->from('AppBundle\Entity\Negocio', 'n')
            ->join('n.comentarios','cn')
            ->where('n = :negocio')
            ->setParameter('negocio', $negocio);
        $qb->addGroupBy('n');
        $query = $qb->getQuery();
        $result = $query->getOneOrNullResult();
        if($result!=null){
            $result = $result['mymoy'];
        }
        else{
            $result = 0;
        }
        return $result;
    }
}