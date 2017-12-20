<?php

namespace Mkk\SiteBundle\Repository;

use Doctrine\ORM\Query;
use Mkk\SiteBundle\Lib\LibTranslatableRepository;

/**
 * EtablissementRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RepositoryEtablissement extends LibTranslatableRepository
{
    /**
     * requete pour la liste d'admin.
     *
     * @return Query
     */
    public function searchAdminList(): Query
    {
        $code   = $this->getEntityName();
        $entity = "{$this->bundle}:Etablissement";
        $dql    = "SELECT {$code} FROM {$entity} {$code} WHERE {$code}.type!='enseigne'";
        $query  = $this->getQuery($dql);

        return $query;
    }

    /**
     * Return le nombre de données.
     *
     * @return array
     */
    public function totalWidgetList(): array
    {
        $code   = $this->getEntityName();
        $entity = "{$this->bundle}:Etablissement";
        $dql    = "SELECT COUNT({$code}) AS total FROM {$entity} {$code} WHERE {$code}.type!='enseigne'";
        $query  = $this->getQuery($dql);
        $result = $query->getArrayResult();

        return $result;
    }

    /**
     * Return les 5 derniers résultats.
     *
     * @return array
     */
    public function searchWidgetList(): array
    {
        $code   = $this->getEntityName();
        $entity = "{$this->bundle}:Etablissement";
        $dql    = "SELECT {$code}.id,{$code}.nom AS libelle FROM {$entity} {$code} WHERE {$code}.type!='enseigne' ORDER BY {$code}.id desc";
        $query  = $this->getQuery($dql);
        $query->setMaxResults(5);
        $result = $query->getResult();

        return $result;
    }

    /**
     * Donne la liste des etablissements sauf enseigne.
     *
     * @param array $data data
     *
     * @return Query
     */
    public function searchEtablissementSaufEnseigne($data): Query
    {
        $code          = $this->getEntityName();
        $entity        = "{$this->bundle}:Etablissement";
        $dql           = "SELECT {$code} FROM {$entity} {$code}";
        $dql           = "{$dql} LEFT JOIN {$code}.refnafsousclasse n";
        $search        = [];
        $param         = [];
        $search[]      = "{$code}.type!=:type";
        $param['type'] = 'enseigne';
        if (isset($data['lettre']) && '' !== $data['lettre']) {
            $lettre   = $data['lettre'];
            $search[] = "{$code}.nom LIKE '%{$lettre}%'";
        }

        if (isset($data['activite'])) {
            $activite = $data['activite'];
            $search[] = "{$code}.activite LIKE '%{$activite}%'";
        }

        if (isset($data['nafsousclasse'])) {
            $search[]               = 'n.id=:nafsousclasse';
            $param['nafsousclasse'] = $data['nafsousclasse'];
        }

        $dql    = $this->searchImplode($search, $dql);
        $dql    = trim($dql);
        $dql    = "{$dql} ORDER BY {$code}.nom ASC";
        $result = $this->setSearchResult($dql, $param);

        return $result;
    }

    /**
     * Donne la liste des etablissements.
     *
     * @param array $data data
     *
     * @return Query
     */
    public function searchEtablissement($data): Query
    {
        $code   = $this->getEntityName();
        $entity = "{$this->bundle}:Etablissement";
        $dql    = "SELECT {$code} FROM {$entity} {$code}";
        $dql    = "{$dql} LEFT JOIN {$code}.refnafsousclasse n";
        $search = [];
        $param  = [];
        if (isset($data['lettre']) && '' !== $data['lettre']) {
            $search[] = "{$code}.nom LIKE '%" . $data['lettre'] . "%'";
        }

        if (isset($data['nafsousclasse'])) {
            $search[]               = 'n.id=:nafsousclasse';
            $param['nafsousclasse'] = $data['nafsousclasse'];
        }

        $dql    = $this->searchImplode($search, $dql);
        $dql    = trim($dql);
        $dql    = "{$dql} ORDER BY {$code}.nom ASC";
        $result = $this->setSearchResult($dql, $param);

        return $result;
    }
}
