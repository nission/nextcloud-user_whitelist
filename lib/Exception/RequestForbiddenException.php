<?php

namespace OCA\UserWhitelist\Exception;

class RequestForbiddenException extends WhitelistException
{
    public function __construct()
    {
        parent::__construct('request forbidden');
    }
}
