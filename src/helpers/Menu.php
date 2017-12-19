<?php

namespace yii2module\rest_client\helpers;

use common\enums\rbac\PermissionEnum;
use yii2lab\helpers\ModuleHelper;

class Menu {
	
	public static function getMenu() {
		return [
			'label' => 'API',
			'items' => self::getVersionMenu(),
			'visible' => YII_ENV_DEV,
		];
	}
	
	private static function getVersionMenu() {
		$all = ModuleHelper::allByApp(FRONTEND);
		$menu = [];
		foreach($all as $name => $config) {
			if($config['class'] == 'yii2module\\rest_client\\Module') {
				$menu[] = [
					'label' => self::parseVersion($name),
					'url' => $name,
					'module' => $name,
				];
			}
		}
		return $menu;
	}
	
	private static function parseVersion($name) {
		preg_match('#(v[0-9]+)#', $name, $matches);
		return !empty($matches[1]) ? $matches[1] : $name;
	}
}