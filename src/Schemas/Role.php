<?php namespace Schemas ;

class Role{

	const COLUMNS =[
		'Id_role'=> ['type' =>'varchar(255)' ,'nullable' =>'' ,'default' => ''],
		'niveau_acces'=> ['type' =>'int(11)' ,'nullable' =>'1' ,'default' => ''],
	];
}