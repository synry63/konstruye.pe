<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/17/16
 * Time: 2:38 PM
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class ConstructoraInmobiliariaRepository extends EntityRepository
{
    /**
     * @return mixed
     */
    public function getNegocios($slug_categoria = null){
        $qb = $this->createQueryBuilder('n')
            ->select('n as negocio,avg(cp.nota) as mymoy')
            ->leftJoin('n.comentarios','cp')
            ->orderBy('n.registeredAt', 'DESC')
            ->where('n.isAccepted = :state')
            ->setParameter('state', true);
        ;
        if($slug_categoria!=null) {
            $qb->join('n.categoriasListado','cl')
                ->andWhere('cl.slug = :cate')
                ->setParameter('cate', $slug_categoria);
        }
        $qb->addGroupBy('n');
        $query = $qb->getQuery();

        return $query;
    }
    public function getNegociosByCategoria($cate){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('n as negocio,avg(cp.nota) as mymoy')
            ->from('AppBundle\Entity\ConstructoraInmobiliaria', 'n')
            ->join('n.categoriasListado','cl')
            ->leftJoin('n.comentarios','cp')
            ->where('cl = :cate')
            ->andWhere('n.isAccepted = :state')
            ->setParameters(array(
                'cate' => $cate,
                'state'=>true
            ));
        $qb->addGroupBy('n');
        //$qb->addGroupBy('cp');
        $query = $qb->getQuery();


        return $query;
    }
    /**
     * @param int $limit
     * @return mixed
     */
    public function getBestNegocios($limit = 5){

        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder();
        $qb->select('n as negocio,avg(cp.nota) as mymoy')
            ->from('AppBundle\Entity\ConstructoraInmobiliaria', 'n')
            ->join('n.comentarios','cp')
            ->setMaxResults( $limit );
        $qb->addOrderBy('mymoy', 'DESC');
        $qb->addGroupBy('n');
        $query = $qb->getQuery();

        return $query->getResult();
    }
    public function searchNegociosNames($search){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('n')
            ->from('AppBundle\Entity\ConstructoraInmobiliaria', 'n')
            ->where($qb->expr()->like('n.nombre', ':search'))
            ->setParameter('search', '%' . $search . '%');
        $query = $qb->getQuery();
        $negocios = $query->getResult();
        return $negocios;
        //$nombres = array_column($negocios, 'nombre');
        //return $nombres;
    }
    public function getNegociosBy($search,$slug_categoria = null){

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('n as negocio,avg(cp.nota) as mymoy')
            ->from('AppBundle\Entity\ConstructoraInmobiliaria', 'n')
            ->leftJoin('n.comentarios','cp')
            ->where($qb->expr()->like('n.nombre', ':search'))
            ->setParameter('search', '%' . $search . '%');

        if($slug_categoria!=null) {
            $qb->join('n.categoriasListado','cl')
                ->andWhere('cl.slug = :cate')
                ->setParameter('cate', $slug_categoria);
        }

        $qb->addGroupBy('n');
        $query = $qb->getQuery();
        //$negocios = $query->getResult();
        return $query;
    }
}