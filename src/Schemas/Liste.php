<?php namespace Schemas ;

class Liste{

	const COLUMNS =[
		'Id_produit'=> ['type' =>'varchar(255)' ,'nullable' =>'' ,'default' => ''],
		'Id_commande'=> ['type' =>'int(11)' ,'nullable' =>'' ,'default' => ''],
	];
}