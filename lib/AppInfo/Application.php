<?php

declare(strict_types=1);

namespace OCA\UserWhiteList\AppInfo;

use OC\User\LoginException;
use OCA\UserWhiteList\Event\PostLoginListener;
use OCA\UserWhitelist\Exception\UserNoAuthorizationException;
use OCA\UserWhitelist\Middleware\WhitelistMiddleware;
use OCP\AppFramework\App;
use OCA\UserWhitelist\Service\WhitelistService;
use OCP\EventDispatcher\IEventDispatcher;
use OCP\User\Events\PostLoginEvent;

class Application extends App
{
    public function __construct()
    {
        parent::__construct('userwhitelist');

        $container = $this->getContainer();
        $server = $container->getServer();
        // register middleware
        $container->registerMiddleWare(WhitelistMiddleware::class);

        if (strpos($server->getRequest()->getPathInfo(), '/ocs/') !== 0) {
            // register login action
            /* @var IEventDispatcher $eventDispatcher */
            $dispatcher = $this->getContainer()->query(IEventDispatcher::class);
            $dispatcher->addServiceListener(PostLoginEvent::class, PostLoginListener::class);

            try {
                $server->query(WhitelistService::class)->authorize();
            } catch (UserNoAuthorizationException $e) {
                $server->getUserSession()->logout();
            }
        }
    }
}
