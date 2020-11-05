<?php

namespace OCA\UserWhiteList\Db;

use OCP\AppFramework\Db\Entity;

class User extends Entity
{
    const STATUS_TEMP = 1;
    const STATUS_ENABLE = 2;
    const STATUS_DISABLE = 3;

    protected $name;
    protected $remark;
    protected $status;
    protected $create;
    protected $createUser;
    protected $edit;
    protected $editUser;

    public function __construct()
    {
        $this->addType('status', 'integer');
    }

    public function columnToProperty($column)
    {
        if ($column === 'create_user') {
            return 'createUser';
        } elseif ($column === 'edit_user') {
            return 'editUser';
        } else {
            return parent::columnToProperty($column);
        }
    }

    public function propertyToColumn($property)
    {
        if ($property === 'createUser') {
            return 'create_user';
        } elseif ($property === 'editUser') {
            return 'edit_user';
        } else {
            return parent::propertyToColumn($property);
        }
    }

    public function temp()
    {
        $this->setStatus(self::STATUS_TEMP);
    }

    public function enable()
    {
        $this->setStatus(self::STATUS_ENABLE);
    }

    public function disable()
    {
        $this->setStatus(self::STATUS_DISABLE);
    }

    public function isEnable($status = null)
    {
        $status = $status ?? $this->getStatus();

        return self::STATUS_ENABLE === $status;
    }

    public function setStatusWrapper($status)
    {
        if (!in_array($status, [self::STATUS_DISABLE, self::STATUS_ENABLE, self::STATUS_TEMP], true)) {
            $status = self::STATUS_TEMP;
        }

        $this->setStatus($status);
    }

    public function setCreateWrapper()
    {
        if (!$this->getCreate() || '1000-01-01 00:00:00' === $this->getCreate()) {
            $this->setCreate(date('Y-m-d H:i:s'));
        }
    }

    public function setEditWrapper()
    {
        $this->setEdit(date('Y-m-d H:i:s'));
    }
}
