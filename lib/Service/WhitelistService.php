<?php

namespace OCA\UserWhitelist\Service;

use OCA\UserWhitelist\Exception\UserNoAuthorizationException;
use OCA\UserWhiteList\Db\User;
use OCA\UserWhiteList\Db\UserMapper;
use OCA\UserWhitelist\Exception\UserNotExistException;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\IGroupManager;
use OCP\ILogger;
use OCP\IUserSession;
use OCP\L10N\IFactory as L10nFactory;

class WhitelistService
{
    const DEFAULT_PAGE_SIZE = 10;
    const DEFAULT_PAGE = 1;

    /** @var OCP\IL10N*/
    private $l10n;
    /** @var ILogger $logger */
    private $logger;
    /** @var string 用户id */
    private $userId;
    /** @var OCA\UserWhiteList\Db\UserMapper $userMapper */
    private $userMapper;
    /** @var bool 是否为管理员 */
    private $isAdmin;

    public function __construct(L10nFactory $l10n, IUserSession $session, IGroupManager $group, UserMapper $userMapper, ILogger $logger)
    {
        $this->l10n = $l10n->get('userwhitelist');
        $this->userMapper = $userMapper;
        $this->logger = $logger;

        if ($user = $session->getUser()) {
            $this->userId = $user->getUID();
            $this->isAdmin = $group->isAdmin($this->userId);
        }
    }

    /**
     * 权限检查
     *
     * @throws UserNoAuthorizationException
     * @throws UserNotExistException
     */
    public function authorize()
    {
        if ($this->userId && !$this->isAuthorize()) {
            throw new UserNoAuthorizationException($this->l10n->t('user forbidden'));
        }
    }

    /**
     * @throws UserNotExistException
     */
    public function isAuthorize()
    {
        if ($this->isAdmin) {
            return true;
        }

        try {
            /** @var OCA\UserWhiteList\Db\User $user */
            $user = $this->userMapper->find($this->userId);

            return $user->isEnable();
        } catch (DoesNotExistException $e) {
            $this->addUser($this->userId, 'auto add');

            throw new UserNotExistException();
        }
    }

    /**
     * 同步用户信息
     */
    public function syncUser(string $name, int $status, $remark = '')
    {
        try {
            $user = $this->userMapper->find($name);
            if ($user->status !== $status) {
                if ($user->isEnable($status)) {
                    $this->enableUser($user, $remark . ' enable');
                } else {
                    $this->disableUser($user, $remark . ' disable');
                }
            }
        } catch (DoesNotExistException $e) {
            $this->addUser($name, $remark, $status);
        }
    }

    /**
     * 增加白名单用户
     */
    public function addUser(string $name, string $remark = '', $status = null)
    {
        $this->logger->info('auto add user{username}', ['username' => $name]);

        try {
            $user = $this->userMapper->find($name);
            $this->enableUser($user, $remark);
        } catch (DoesNotExistException $e) {
            $user = new User();
            $user->setName($name);
            $user->setCreate();
            $user->setCreateUser($this->userId);
            $user->setEdit();
            $user->setEditUser($this->userId);
            $user->setRemark($remark);
            if (is_null($status)) {
                $user->enable();
            } else {
                $user->setStatus($status);
            }

            $this->userMapper->insert($user);
        }
    }

    public function batchAddUser(array $names, string $remark = '')
    {
        foreach ($names as $name) {
            $this->logger->info('add user {username}, admin {admin_name}', ['username' => $name, 'admin_name' => $this->userId]);
            $this->addUser($name, $remark);
        }
    }

    public function enableUser($name, string $remark = '')
    {
        try {
            if ($name instanceof User) {
                $user = $name;
            } else {
                /** @var OCA\UserWhiteList\Db\User $user */
                $user = $this->userMapper->find($name);
            }

            if (!$user->isEnable()) {
                $this->logger->info('{admin} enable user{username}', ['admin' => $this->userId, 'username' => $name]);

                $user->enable();
                $user->setEdit();
                $user->setEditUser($this->userId);
                $this->addRemark($user, $remark);

                $this->userMapper->update($user);
            }
        } catch (DoesNotExistException $e) {
            throw new UserNotExistException();
        }
    }

    public function disableUser($name, string $remark = '')
    {
        try {
            if ($name instanceof User) {
                $user = $name;
            } else {
                /** @var OCA\UserWhiteList\Db\User $user */
                $user = $this->userMapper->find($name);
            }

            if ($user->isEnable()) {
                $this->logger->info('{admin} disable user{username}', ['admin' => $this->userId, 'username' => $name]);

                $user->disable();
                $user->setEdit();
                $user->setEditUser($this->userId);
                $this->addRemark($user, $remark);

                $this->userMapper->update($user);
            }
        } catch (DoesNotExistException $e) {
            throw new UserNotExistException();
        }
    }

    public function paginationUser($page = self::DEFAULT_PAGE, $pageSize = self::DEFAULT_PAGE_SIZE)
    {
        $page = $page <= 0 ? self::DEFAULT_PAGE : $page;
        $pageSize = $pageSize > 50 || $pageSize <= 0 ? self::DEFAULT_PAGE_SIZE : $pageSize;

        return $this->userMapper->findAll($pageSize, ($page - 1) * $pageSize);
    }

    private function addRemark(&$user, $remark)
    {
        if (($dbRemark = $user->getRemark())) {
            $newRemark = $remark ? $dbRemark."\n".$remark : '';
        } else {
            $newRemark = $remark;
        }

        $user->setRemark($newRemark);
    }
}
