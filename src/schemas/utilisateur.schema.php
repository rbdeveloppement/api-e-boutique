<?php namespace Schemas;

class UtilisateurSchema {

	const COLUMNS = [
		'Id_client' => ['type'=>'varchar(255)', 'nullable'=>'', 'default'=>''],
		'nom' => ['type'=>'varchar(50)', 'nullable'=>'1', 'default'=>''],
		'prenom' => ['type'=>'varchar(50)', 'nullable'=>'1', 'default'=>''],
		'adresse' => ['type'=>'varchar(50)', 'nullable'=>'1', 'default'=>''],
		'telephone' => ['type'=>'varchar(50)', 'nullable'=>'1', 'default'=>''],
		'mail' => ['type'=>'varchar(50)', 'nullable'=>'1', 'default'=>''],
		'Id_role' => ['type'=>'varchar(255)', 'nullable'=>'1', 'default'=>''],
		'is_deleted' => ['type'=>'tinyint(1)', 'nullable'=>'1', 'default'=>''],
	];

}

?>