<?php

namespace OCA\UserWhitelist\Exception;

class WhitelistException extends \Exception
{
    protected function formatCode()
    {
        return sprintf('%06d', $this->getCode());
    }
}
