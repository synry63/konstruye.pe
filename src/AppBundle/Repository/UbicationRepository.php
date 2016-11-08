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
    function getDistritos($search){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('d')
            ->from('AppBundle\Entity\Distrito', 'd')
            ->where($qb->expr()->like('d.nombre', ':search'))
            ->setParameter('search', '%' . $search . '%');

        /*$q = $em->createQuery('select d from AppBundle\Entity\Distrito d WHERE d.nombre LIKE :search')
            ->setParameter('search','%'.$search.'%');*/
        $query = $qb->getQuery();
        $result = $query->getResult();

        return $result;
    }
    function getProvincias($search){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p')
            ->from('AppBundle\Entity\Provincia', 'p')
            ->where($qb->expr()->like('p.nombre', ':search'))
            ->setParameter('search', '%' . $search . '%');

        $query = $qb->getQuery();
        $result = $query->getResult();

        return $result;
    }
    function getDepartamentos($search){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p')
            ->from('AppBundle\Entity\Departamento', 'p')
            ->where($qb->expr()->like('p.nombre', ':search'))
            ->setParameter('search', '%' . $search . '%');

        $query = $qb->getQuery();
        $result = $query->getResult();

        return $result;
    }
}