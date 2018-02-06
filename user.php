<?php

/*
 * Get User Data From Id
 * @params
 * ['id' => '' , 'value' =>]
 */
function get( $arg =array() ){
	global $wpdb;

	//Require field
	$user_table_field = ['name', 'family', 'email', 'password'];

	$id = $arg['id'];
	if(!is_array($arg)) $id = $arg;

	$query = $wpdb->get_row("SELECT * FROM `user` WHERE `id` = $id", ARRAY_A);
	if(array_key_exists("value", $arg)) {
		return $query[$arg['value']];
	} else {
		return $query;
	}
}



function add( $arg =array() ){
	global $wpdb;

	//Require field
	$require = ['name', 'family', 'email', 'password'];

	$field = [
		'name' => $arg['name'],
		'family' => $arg['family'],
		'email' => $arg['email'],
	];

	$password = MD5 (rand(10000, 99999));
	if( array_key_exists("password" , $arg) ) {
		$password = MD5( $arg['password'] );
	}
	$field['password'] = $password;

	$wpdb->insert("user", $field);
	$user_id = $wpdb->insert_id;

	//create meta extra
	$meta = [];
	foreach($arg as $k => $v){
		if(!in_array($k, $require)) {
			$meta[$k] = $v;
		}
	}
	if(count($meta) >0) {
		$wpdb->insert("user_meta", [
			'user_id' => $user_id,
			'meta_value' => json_encode($meta),
		]);
	}
}