<?php

namespace Mkk\SiteBundle\Repository;

use Doctrine\ORM\Query;
use Mkk\SiteBundle\Lib\LibRepository;

/**
 * HoraireRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RepositoryPage extends LibRepository
{
    /**
     * requete pour la liste d'admin.
     *
     * @return Query
     */
    public function searchAdminList(): Query
    {
        $entity = $this->getEntityName();
        $dql    = "SELECT {$entity} FROM {$this->bundle}:Page {$entity}";
        $query  = $this->getQuery($dql);

        return $query;
    }
}