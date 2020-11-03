<?php

namespace OCA\UserWhiteList\Db;

use OCP\AppFramework\Db\QBMapper;
use OCP\IDBConnection;
use OCP\DB\QueryBuilder\IQueryBuilder;

class UserMapper extends QBMapper {
    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'user_whitelist');
    }

    /**
     * @throws \OCP\AppFramework\Db\DoesNotExistException if not found
     * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException if more than one result
     */
    public function find(string $name) {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
           ->from('user_whitelist')
           ->where(
               $qb->expr()->eq('name', $qb->createNamedParameter($name, IQueryBuilder::PARAM_STR))
           );

        return $this->findEntity($qb);
    }

    public function findAll($limit=null, $offset=null) {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
           ->from('user_whitelist')
           ->setMaxResults($limit)
           ->setFirstResult($offset);

        return $this->findEntities($qb);
    }

    public function countAll() {
        $qb = $this->db->getQueryBuilder();

        $qb->selectAlias($qb->createFunction('COUNT(*)'), 'count')
           ->from('user_whitelist');

        $cursor = $qb->execute();
        $row = $cursor->fetch();
        $cursor->closeCursor();

        return $row['count'];
    }
}