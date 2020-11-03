<?php

namespace OCA\UserWhitelist\Service;

class ApiService
{
    const SIGIN_SALT = '*&!@981AbIU';

    public function sign($params)
    {
        unset($params['sign']);
        // Remove private params
        foreach($params as $k=>$v) {
            if ($k[0] === '_') {
                unset($params[$k]);
            }
        }

        ksort($params);
        $unencrypt = '';
        foreach($params as $key=>$value) {
            $unencrypt .= $key.'|'.$value;
        }

        return md5($unencrypt . self::SIGIN_SALT);
    }

    public function isLegalRq($params)
    {
        return ($params['sign'] ?? '') === $this->sign($params);
    }

}
