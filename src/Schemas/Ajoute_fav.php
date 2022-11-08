<?php namespace Schemas ;

class Ajoute_fav{

	const COLUMNS =[
		'Id_client'=> ['type' =>'varchar(255)' ,'nullable' =>'' ,'default' => ''],
		'Id_favoris'=> ['type' =>'varchar(255)' ,'nullable' =>'' ,'default' => ''],
		'is_deleted'=> ['type' =>'tinyint(1)' ,'nullable' =>'1' ,'default' => ''],
	];
}