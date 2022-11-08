<?php namespace Schemas ;

class Favoris{

	const COLUMNS =[
		'Id_favoris'=> ['type' =>'varchar(255)' ,'nullable' =>'' ,'default' => ''],
		'is_deleted'=> ['type' =>'tinyint(1)' ,'nullable' =>'1' ,'default' => ''],
	];
}