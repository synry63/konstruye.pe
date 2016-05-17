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
            ->orderBy('n.registeredAt', 'DESC');
        $query = $qb->getQuery();

        return $query;
    }
}