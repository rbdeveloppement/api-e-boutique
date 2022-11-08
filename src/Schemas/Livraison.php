<?php namespace Schemas ;

class Livraison{

	const COLUMNS =[
		'Id_livraison'=> ['type' =>'varchar(255)' ,'nullable' =>'' ,'default' => ''],
		'adresse'=> ['type' =>'varchar(255)' ,'nullable' =>'1' ,'default' => ''],
		'dates'=> ['type' =>'date' ,'nullable' =>'1' ,'default' => ''],
		'client'=> ['type' =>'varchar(255)' ,'nullable' =>'1' ,'default' => ''],
		'numero_d_envoi'=> ['type' =>'varchar(255)' ,'nullable' =>'1' ,'default' => ''],
		'is_deleted'=> ['type' =>'tinyint(1)' ,'nullable' =>'1' ,'default' => ''],
	];
}