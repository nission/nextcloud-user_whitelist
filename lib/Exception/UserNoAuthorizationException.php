<?php

namespace OCA\UserWhitelist\Exception;

class UserNoAuthorizationException extends WhitelistException
{
    public function __construct()
    {
        parent::__construct('User has no authorization');
    }
}
