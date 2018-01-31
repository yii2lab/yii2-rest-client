<?php

namespace yii2module\rest_client\helpers;

use common\enums\rbac\PermissionEnum;
use yii2lab\helpers\ModuleHelper;

class Menu {
	
	public function toArray() {
		return [
			'label' => 'API',
			'items' => $this->getVersionMenu(),
			'visible' => YII_ENV_DEV,
			'access' => [PermissionEnum::REST_CLIENT_ALL],
		];
	}
	
	private function getVersionMenu() {
		$all = ModuleHelper::allByApp(FRONTEND);
		$menu = [];
		foreach($all as $name => $config) {
			if($config['class'] == 'yii2module\\rest_client\\Module') {
				$menu[] = [
					'label' => $this->parseVersion($name),
					'url' => $name,
					'module' => $name,
				];
			}
		}
		return $menu;
	}
	
	private function parseVersion($name) {
		preg_match('#(v[0-9]+)#', $name, $matches);
		return !empty($matches[1]) ? $matches[1] : $name;
	}
}