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
    public function getNegocios(){
        $qb = $this->createQueryBuilder('n')
            ->select('n as negocio,avg(cp.nota) as mymoy')
            ->leftJoin('n.comentarios','cp')
            ->orderBy('n.registeredAt', 'DESC')
            ->where('n.isActive = :state')
            ->setParameter('state', true);
        ;

        $qb->addGroupBy('n');
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
}