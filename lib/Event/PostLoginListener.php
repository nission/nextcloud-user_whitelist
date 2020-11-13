<?php
declare(strict_types=1);

namespace OCA\UserWhiteList\Event;

use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\User\Events\PostLoginEvent;
use OC\User\LoginException;
use OCA\UserWhitelist\Service\WhitelistService;
use OCA\UserWhitelist\Exception\UserNoAuthorizationException;

class PostLoginListener implements IEventListener {

    public function handle(Event $event): void {
        if ($event instanceof PostLoginEvent) {
            $server = \OC::$server;

            try {
                $server->query(WhitelistService::class)->authorize();
            } catch (UserNoAuthorizationException $e) {
                $message = \OC::$server->getL10N('userwhitelist')->t('User not allowed, please contact admin');
                throw new LoginException($message);
            }
        }
    }
}