<?php namespace Schemas;

class CategorieSchema {

	const COLUMNS = [
		'Id_categorie' => ['type'=>'varchar(255)', 'nullable'=>'', 'default'=>''],
		'nom_categorie' => ['type'=>'varchar(255)', 'nullable'=>'1', 'default'=>''],
	];

}

?>