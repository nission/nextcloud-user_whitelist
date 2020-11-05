<?php

namespace OCA\UserWhitelist\Exception;

class ParamErrorException extends WhitelistException
{
    public function __construct($message = null)
    {
        parent::__construct($message ?? 'params error', $this->formatCode(self::PARAM_ERROR));
    }
}
