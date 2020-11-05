<?php

namespace OCA\UserWhitelist\Exception;

class UserNotExistException extends WhitelistException
{
    public function __construct()
    {
        parent::__construct('User not exists', $this->formatCode(self::USER_NOT_FOUND));
    }
}
