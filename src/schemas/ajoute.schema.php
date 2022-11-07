<?php namespace Schemas;

class AjouteSchema {

	const COLUMNS = [
		'Id_client' => ['type'=>'varchar(255)', 'nullable'=>'', 'default'=>''],
		'Id_favoris' => ['type'=>'varchar(255)', 'nullable'=>'', 'default'=>''],
		'is_deleted' => ['type'=>'tinyint(1)', 'nullable'=>'1', 'default'=>''],
	];

}

?>