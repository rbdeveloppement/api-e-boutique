<?php namespace Schemas;

class produitSchema {

	const COLUMNS = [
		'Id_produit' => ['type'=>'varchar(255)', 'nullable'=>'', 'default'=>''],
		'nom' => ['type'=>'varchar(100)', 'nullable'=>'1', 'default'=>''],
		'description' => ['type'=>'varchar(255)', 'nullable'=>'1', 'default'=>''],
		'prix' => ['type'=>'decimal(19,4)', 'nullable'=>'1', 'default'=>''],
		'image' => ['type'=>'varchar(50)', 'nullable'=>'1', 'default'=>''],
		'stock' => ['type'=>'int(11)', 'nullable'=>'1', 'default'=>''],
		'Id_sous_categorie' => ['type'=>'varchar(255)', 'nullable'=>'1', 'default'=>''],
	];

}

?>