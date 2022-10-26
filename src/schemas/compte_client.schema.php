<?php namespace Schemas;

class Compte_clientSchema {

	const COLUMNS = [
		'Id_compte_client' => ['type'=>'varchar(255)', 'nullable'=>'', 'default'=>''],
		'login' => ['type'=>'varchar(255)', 'nullable'=>'1', 'default'=>''],
		'mot_de_passe' => ['type'=>'varchar(50)', 'nullable'=>'1', 'default'=>''],
		'Id_client' => ['type'=>'varchar(255)', 'nullable'=>'1', 'default'=>''],
	];

}

?>