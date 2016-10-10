<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/18/16
 * Time: 1:14 PM
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class UbicationRepository extends EntityRepository
{
    function getUbicationBy($search){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('d')
            ->from('AppBundle\Entity\Distrito', 'd')
            ->where($qb->expr()->like('d.nombre', ':search'))
            ->setParameter('search', '%' . $search . '%');

        $query = $qb->getQuery();
        $result = $query->getResult();

        return $result;
    }
}