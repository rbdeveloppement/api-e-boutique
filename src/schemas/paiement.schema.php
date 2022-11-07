<?php namespace Schemas;

class PaiementSchema {

	const COLUMNS = [
		'Id_paiement' => ['type'=>'varchar(255)', 'nullable'=>'', 'default'=>''],
		'dates' => ['type'=>'date', 'nullable'=>'1', 'default'=>''],
		'libellé' => ['type'=>'varchar(50)', 'nullable'=>'1', 'default'=>''],
		'Id_commande' => ['type'=>'int(11)', 'nullable'=>'1', 'default'=>''],
		'is_deleted' => ['type'=>'tinyint(1)', 'nullable'=>'1', 'default'=>''],
	];

}

?>