<?php

use yii2lab\migration\db\MigrationCreateTable as Migration;

/**
* Handles the creation of table `rest_login`.
*/
class m170607_092718_create_rest_login_table extends Migration
{
	public $table = '{{%rest_login}}';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
			'login' => $this->string(32),
			'password' => $this->string(32),
			'description' => $this->string(32),
		];

	}

	public function afterCreate()
	{
		$this->myAddPrimaryKey(['login']);
	}

}
