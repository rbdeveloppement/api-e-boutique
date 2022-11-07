<?php namespace Schemas;

class Sous_categorieSchema {

	const COLUMNS = [
		'Id_sous_categorie' => ['type'=>'varchar(255)', 'nullable'=>'', 'default'=>''],
		'nom_sous_categorie' => ['type'=>'varchar(50)', 'nullable'=>'1', 'default'=>''],
		'Id_categorie' => ['type'=>'varchar(255)', 'nullable'=>'1', 'default'=>''],
		'is_deleted' => ['type'=>'tinyint(1)', 'nullable'=>'1', 'default'=>''],
	];

}

?>