<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/17/16
 * Time: 1:19 PM
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class InmuebleRepository extends EntityRepository
{
    /**
     * @return mixed
     */
    public function getNegocios(){
        $qb = $this->createQueryBuilder('n')
            ->select('n as negocio,avg(cp.nota) as mymoy')
            ->leftJoin('n.comentarios','cp')
            ->orderBy('n.registeredAt', 'DESC')
            ->where('n.isAccepted = :state')
            ->setParameter('state', true);
        ;
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
            ->from('AppBundle\Entity\Inmueble', 'n')
            ->join('n.comentarios','cp')
            ->setMaxResults( $limit );
        $qb->addOrderBy('mymoy', 'DESC');
        $qb->addGroupBy('n');
        $query = $qb->getQuery();

        return $query->getResult();
    }
}