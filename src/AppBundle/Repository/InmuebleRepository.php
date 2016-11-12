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
    public function getNegocios($dormi = null,$lugar = null,$precio_min = null,$precio_max = null,$servicio = null,$general = null,$structure = null,$operacion = null){
        $qb = $this->createQueryBuilder('n')
            ->select('n as negocio,avg(cp.nota) as mymoy')
            ->leftJoin('n.comentarios','cp')
            ->orderBy('n.registeredAt', 'DESC')
            ->where('n.isAccepted = :state')
            ->setParameter('state', true);
        ;
        if($servicio!=null){
            $qb->join('n.sevicios','si')
                ->andWhere('si.servicio = :servicio')
                ->setParameter('servicio', $servicio);
        }
        if($general!=null){
            $qb->join('n.generales','ng')
                ->andWhere('ng.general = :general')
                ->setParameter('general', $general);
        }
        if(!empty($structure)){
            $qb->join('n.structure','s')
                ->andWhere('s = :structure')
                ->setParameter('structure', $structure);
        }
        if(!empty($operacion)){
            $qb->join('n.operacion','o')
                ->andWhere('o = :operacion')
                ->setParameter('operacion', $operacion);
        }
        if($dormi!=null){
            $qb->andWhere('n.dormitorios = :dormi')
                ->setParameter('dormi', $dormi);
        }
        if($precio_min!=null && $precio_max!=null){
            $qb->andWhere('n.precioSoles BETWEEN :min AND :max')
                ->setParameter('min', $precio_min)
                ->setParameter('max', $precio_max);
        }
        if($lugar!=null && count($lugar)>0){
            $number = count($lugar);
            if($number==3){
                $qb->andWhere('n.distrito = :distrito')
                    ->setParameter('distrito', $lugar[0]);
                $qb->andWhere('n.provincia = :provincia')
                    ->setParameter('provincia', $lugar[1]);
                $qb->andWhere('n.departamento = :departamento')
                    ->setParameter('departamento', $lugar[2]);
            }
            else if($number==2){
                $qb->andWhere('n.departamento = :departamento')
                    ->setParameter('departamento', $lugar[0]);
                $qb->andWhere('n.provincia = :provincia')
                    ->setParameter('provincia', $lugar[1]);
            }
            else if($number==1){
                $qb->andWhere('n.departamento = :departamento')
                    ->setParameter('departamento', $lugar[0]);
            }

        }
        $qb->addGroupBy('n');
        $query = $qb->getQuery();
        return $query;
    }
    public function getMinPrice($structure = null,$operacion = null,$lugar = null){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('MIN(n.precioSoles) as minPrice')
            ->from('AppBundle\Entity\Inmueble', 'n');

        if($lugar!=null && count($lugar)>0){
            $number = count($lugar);
            if($number==3){
                $qb->andWhere('n.distrito = :distrito')
                    ->setParameter('distrito', $lugar[0]);
                $qb->andWhere('n.provincia = :provincia')
                    ->setParameter('provincia', $lugar[1]);
                $qb->andWhere('n.departamento = :departamento')
                    ->setParameter('departamento', $lugar[2]);
            }
            else if($number==2){
                $qb->andWhere('n.departamento = :departamento')
                    ->setParameter('departamento', $lugar[0]);
                $qb->andWhere('n.provincia = :provincia')
                    ->setParameter('provincia', $lugar[1]);
            }
            else if($number==1){
                $qb->andWhere('n.departamento = :departamento')
                    ->setParameter('departamento', $lugar[0]);
            }

        }
        if(!empty($structure)){
            $qb->join('n.structure','st')
                ->andWhere('st = :structure')
                ->setParameter('structure', $structure);
        }
        if(!empty($operacion)){
            $qb->join('n.operacion','o')
                ->andWhere('o = :operacion')
                ->setParameter('operacion', $operacion);
        }

        $query = $qb->getQuery();
        return $query->getOneOrNullResult();
    }
    public function getMaxPrice($structure = null,$operacion = null,$lugar = null){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('MAX(n.precioSoles) as maxPrice')
            ->from('AppBundle\Entity\Inmueble', 'n');

        if($lugar!=null && count($lugar)>0){
            $number = count($lugar);
            if($number==3){
                $qb->andWhere('n.distrito = :distrito')
                    ->setParameter('distrito', $lugar[0]);
                $qb->andWhere('n.provincia = :provincia')
                    ->setParameter('provincia', $lugar[1]);
                $qb->andWhere('n.departamento = :departamento')
                    ->setParameter('departamento', $lugar[2]);
            }
            else if($number==2){
                $qb->andWhere('n.departamento = :departamento')
                    ->setParameter('departamento', $lugar[0]);
                $qb->andWhere('n.provincia = :provincia')
                    ->setParameter('provincia', $lugar[1]);
            }
            else if($number==1){
                $qb->andWhere('n.departamento = :departamento')
                    ->setParameter('departamento', $lugar[0]);
            }

        }
        if(!empty($structure)){
            $qb->join('n.structure','st')
                ->andWhere('st = :structure')
                ->setParameter('structure', $structure);
        }
        if(!empty($operacion)){
            $qb->join('n.operacion','o')
                ->andWhere('o = :operacion')
                ->setParameter('operacion', $operacion);
        }

        $query = $qb->getQuery();
        return $query->getOneOrNullResult();
    }
    public  function getGeneralesWithInmueblesCount($structure = null,$operacion = null,$lugar = null){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('g as general,count(g) cantidad')
            ->from('AppBundle\Entity\General', 'g')
            ->join('g.inmuebles','gi')
            ->join('gi.inmueble','n')
            ->where('n.isAccepted = :state')
            ->setParameter('state', true);

        if($lugar!=null && count($lugar)>0){
            $number = count($lugar);
            if($number==3){
                $qb->andWhere('n.distrito = :distrito')
                    ->setParameter('distrito', $lugar[0]);
                $qb->andWhere('n.provincia = :provincia')
                    ->setParameter('provincia', $lugar[1]);
                $qb->andWhere('n.departamento = :departamento')
                    ->setParameter('departamento', $lugar[2]);
            }
            else if($number==2){
                $qb->andWhere('n.departamento = :departamento')
                    ->setParameter('departamento', $lugar[0]);
                $qb->andWhere('n.provincia = :provincia')
                    ->setParameter('provincia', $lugar[1]);
            }
            else if($number==1){
                $qb->andWhere('n.departamento = :departamento')
                    ->setParameter('departamento', $lugar[0]);
            }

        }
        if(!empty($structure)){
            $qb->join('n.structure','st')
                ->andWhere('st = :structure')
                ->setParameter('structure', $structure);
        }
        if(!empty($operacion)){
            $qb->join('n.operacion','o')
                ->andWhere('o = :operacion')
                ->setParameter('operacion', $operacion);
        }

        $qb->addGroupBy('general');
        $qb->addOrderBy('g.nombre', 'ASC');
        $query = $qb->getQuery();

        return $query->getResult();
    }
    public  function getServiciosWithInmueblesCount($structure = null,$operacion = null,$lugar = null){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('s as servicio,count(s) cantidad')
            ->from('AppBundle\Entity\Servicio', 's')
            ->join('s.inmuebles','si')
            ->join('si.inmueble','n')
            ->where('n.isAccepted = :state')
            ->setParameter('state', true);

        if($lugar!=null && count($lugar)>0){
            $number = count($lugar);
            if($number==3){
                $qb->andWhere('n.distrito = :distrito')
                    ->setParameter('distrito', $lugar[0]);
                $qb->andWhere('n.provincia = :provincia')
                    ->setParameter('provincia', $lugar[1]);
                $qb->andWhere('n.departamento = :departamento')
                    ->setParameter('departamento', $lugar[2]);
            }
            else if($number==2){
                $qb->andWhere('n.departamento = :departamento')
                    ->setParameter('departamento', $lugar[0]);
                $qb->andWhere('n.provincia = :provincia')
                    ->setParameter('provincia', $lugar[1]);
            }
            else if($number==1){
                $qb->andWhere('n.departamento = :departamento')
                    ->setParameter('departamento', $lugar[0]);
            }

        }
        if(!empty($structure)){
            $qb->join('n.structure','st')
                ->andWhere('st = :structure')
                ->setParameter('structure', $structure);
        }
        if(!empty($operacion)){
            $qb->join('n.operacion','o')
                ->andWhere('o = :operacion')
                ->setParameter('operacion', $operacion);
        }

        $qb->addGroupBy('servicio');
        $qb->addOrderBy('s.nombre', 'ASC');
        $query = $qb->getQuery();

        return $query->getResult();
    }
    public function getMaxDormiWithCount($structure = null,$operacion = null,$lugar = null){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('n.dormitorios as dormitorios,count(n) cantidad')
            ->from('AppBundle\Entity\Inmueble', 'n')
            ->where($qb->expr()->isNotNull('n.dormitorios'))
            ->andWhere('n.isAccepted = :state')
            ->setParameter('state', true);

        if($lugar!=null && count($lugar)>0){
            $number = count($lugar);
            if($number==3){
                $qb->andWhere('n.distrito = :distrito')
                    ->setParameter('distrito', $lugar[0]);
                $qb->andWhere('n.provincia = :provincia')
                    ->setParameter('provincia', $lugar[1]);
                $qb->andWhere('n.departamento = :departamento')
                    ->setParameter('departamento', $lugar[2]);
            }
            else if($number==2){
                $qb->andWhere('n.departamento = :departamento')
                    ->setParameter('departamento', $lugar[0]);
                $qb->andWhere('n.provincia = :provincia')
                    ->setParameter('provincia', $lugar[1]);
            }
            else if($number==1){
                $qb->andWhere('n.departamento = :departamento')
                    ->setParameter('departamento', $lugar[0]);
            }

        }
        if(!empty($structure)){
            $qb->join('n.structure','s')
                ->andWhere('s = :structure')
                ->setParameter('structure', $structure);
        }
        if(!empty($operacion)){
            $qb->join('n.operacion','o')
                ->andWhere('o = :operacion')
                ->setParameter('operacion', $operacion);
        }

        $qb->addGroupBy('dormitorios');
        $qb->addOrderBy('dormitorios', 'ASC');
        $query = $qb->getQuery();

        return $query->getResult();

    }
    public function getNegociosByCategoria($cate){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('n as negocio,avg(cp.nota) as mymoy')
            ->from('AppBundle\Entity\Inmueble', 'n')
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
    public function getInmueblesByDormitorios($dormitorios){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('n as negocio,avg(cp.nota) as mymoy')
            ->leftJoin('n.comentarios','cp')
            //->orderBy('n.registeredAt', 'DESC')
            ->where('n.isAccepted = :state')
            ->andWhere('n.dormitorios = :dormitorios')
            ->setParameters(array(
                'state', true,
                'dormitorios', $dormitorios,

            ));
        ;
        $qb->addOrderBy('mymoy', 'DESC');
        $qb->addGroupBy('n');
        $query = $qb->getQuery();

        return $query->getResult();
    }
}