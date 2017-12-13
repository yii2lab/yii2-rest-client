Установка
===

Устанавливаем зависимость:

```
composer require yii2module/yii2-rest-client
```

Создаем полномочие:

```
oRestClientAll
```

Объявляем модуль:

```php
return [
	'modules' => [
		// ...
		'rest-v1' => [
			'class' => 'yii2module\rest_client\Module',
			'baseUrl' => env('url.api') . 'v1',
			'as access' => Config::genAccess(PermissionEnum::REST_CLIENT_ALL),
		],
		// ...
	],
];
```
