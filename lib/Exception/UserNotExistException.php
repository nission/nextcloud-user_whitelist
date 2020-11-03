<?php

namespace OCA\UserWhitelist\Exception;

class UserNotExistException extends WhitelistException
{
    const USER_NOT_FOUND = 1;

    public function __construct()
    {
        parent::__construct('User not exists', $this->formatCode(self::USER_NOT_FOUND));
    }
}
