<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/8/16
 * Time: 1:13 PM
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class UserRepository extends EntityRepository
{
    public function searchAllUsers($search){
        $em = $this->getEntityManager();
        $dql = 'SELECT  u FROM AppBundle\Entity\User u';
        //$dql.= ' LEFT JOIN u.negocios n';
        $dql.= ' WHERE CONCAT(u.nombres,\' \',u.apellidos) LIKE ?1';
        $dql.= ' OR u.nombreEmpresa LIKE ?2';

        $query = $em->createQuery($dql);
        $query->setParameters(array(
            1 => '%'.$search.'%',
            2 => '%'.$search.'%'
        ));
        $r = $query->getResult();

        return $r;
    }
}