<?php namespace Schemas;

class CommandeSchema {

	const COLUMNS = [
		'Id_commande' => ['type'=>'int(11)', 'nullable'=>'', 'default'=>''],
		'dates_achat' => ['type'=>'date', 'nullable'=>'1', 'default'=>''],
		'Id_livraison' => ['type'=>'varchar(255)', 'nullable'=>'1', 'default'=>''],
		'Id_client' => ['type'=>'varchar(255)', 'nullable'=>'1', 'default'=>''],
	];

}

?>