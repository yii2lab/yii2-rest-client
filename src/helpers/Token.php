<?php

namespace yii2module\rest_client\helpers;

use Yii;
use woop\foundation\yii\helpers\FileHelper;
use woop\extension\store\Store;

class Token
{

    public static $fileAlias = '@runtime/rest-client/data.json';

    static public function load($login) {
        $store = new Store('Json');
        $data = $store->load(self::$fileAlias, 'token.' . $login);
        return $data;
    }

    static public function save($login, $token) {
        $store = new Store('Json');
        $store->update(self::$fileAlias, 'token.' . $login, $token);
    }

}