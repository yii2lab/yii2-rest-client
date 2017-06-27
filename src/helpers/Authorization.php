<?php

namespace yii2module\rest_client\helpers;

use Yii;
use yii2lab\test\models\Login;

class Authorization
{

    static public function loginListForSelect() {
	    $loginList = Login::all(['is_active' => 1]);
	    $loginListForSelect = [];
	    if(!empty($loginList)) {
            foreach($loginList as $login) {
                $loginListForSelect[$login['login']] = $login['login'] . ' - ' . $login['description'];
            }
        }
        $loginListForSelect = ArrayHelper::merge(['' => 'Guest'], $loginListForSelect);
        return $loginListForSelect;
    }

    static public function getToken($login)
    {
        $loginItem = Login::one($login);
        if(empty($loginItem)) {
            return null;
        }
        $modelAuth = Request::createRequestFrom('auth', 'post', [
            'login' => $login,
            'password' => $loginItem['password'],
        ]);
	    
        $response = Request::httpRequest($modelAuth);
		if(empty($response)) {
            return null;
        }
        if($response->statusCode != 200) {
            return null;
        }
        $bodyAuth = json_decode($response->content);
        if(empty($bodyAuth) || empty($bodyAuth->token)) {
            return null;
        }
        Token::save($login, $bodyAuth->token);
        return $bodyAuth->token;
    }

    static public function sendRequest($model)
    {
        if(empty($model->authorization)) {
            $record = Request::send($model);
            return $record;
        }

        $token = Token::load($model->authorization);
        if(empty($token)) {
            $token = Authorization::getToken($model->authorization);
        }
        $record = self::putTokenInModel($model, $token);

        if($record->status == 401) {
            $token = Authorization::getToken($model->authorization);
            $record = self::putTokenInModel($model, $token);
        }

        return $record;
    }

    static public function putTokenInModel($model, $token)
    {
        $modelAuth = clone $model;
		if(!empty($token)) {
            $modelAuth->headerKeys[] = 'Authorization';
            $modelAuth->headerValues[] = $token;
            $modelAuth->headerActives[] = 1;
        }
        $record = Request::send($modelAuth);
        return $record;
    }

}