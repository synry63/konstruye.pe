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
    public function searchAllNegocios($search){
        $em = $this->getEntityManager();
        $dql = 'SELECT  n FROM AppBundle\Entity\Negocio n';
        //$dql.= ' LEFT JOIN u.negocios n';
        $dql.= ' WHERE n.nombre LIKE ?1';

        $query = $em->createQuery($dql);
        $query->setParameters(array(
            1 => '%'.$search.'%'
        ));
        $r = $query->getResult();

        return $r;
    }
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
    public function getBestNegociosByUser($user,$limit = 5){
        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder();
        $qb->select('p as negocio,avg(cp.nota) as mymoy')
            ->from('AppBundle\Entity\Proveedor', 'p')
            ->join('p.comentarios','cp')
            ->join('p.categoriasListado','cl')
            ->where('p.user = :user')
            ->setParameter('user', $user)
            ->setMaxResults( $limit );
        $qb->addOrderBy('mymoy', 'DESC');
        $qb->addGroupBy('p');
        $query = $qb->getQuery();

        return $query->getResult();
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
    public function getNegociosUserByCategory($cate,$user){
        $qb = $this->createQueryBuilder('p')
            ->join('p.categoriasListado','cl')
            ->where('cl = :cate')
            ->andWhere('p.isAccepted = :state')
            ->andWhere('p.user = :user')
            ->orderBy('p.registeredAt', 'DESC')
            ->setParameters(array(
                'cate' => $cate,
                'state' => true,
                'user' => $user,
            ));
        $query = $qb->getQuery();
        $result = $query->getResult();

        return $result;
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