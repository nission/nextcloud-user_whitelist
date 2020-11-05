<?php

namespace OCA\UserWhitelist\Exception;

class WhitelistException extends \Exception
{
    const USER_NOT_FOUND = 1;
    const USER_NO_AUTHOR = 2;
    const USER_FORBIDDEN = 3;
    const PARAM_ERROR = 4;

    protected function formatCode()
    {
        return (int)sprintf('4%05d', $this->getCode());
    }
}
