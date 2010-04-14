<?php

function db_create_field_list($table, $fields, $table_alias = NULL) {
	$field_list = array();
	
	if ( true === is_array($fields) ) {
		$field_list = array_map('create_field_alias', $fields, array_fill(0, count($fields), $table_alias));
	}
	
	return $field_list;
}

function db_create_field_alias($field, $table_alias) {
	return ( false === empty($table_alias) ? $table_alias . '.' : NULL ) . str_replace('`', NULL, $field);
}

function db_sanitize_field_list($field_list) {
	foreach ( $field_list as $i => $value ) {
		$field_list[$i] = str_replace("`", NULL, $value);
	}
	return $field_list;
}

function db_create_table_alias($table) {
	$alias = NULL;
	$table = trim($table);
	$table = str_replace('_', ' ', $table);
	
	$words = explode(' ', $table);
	foreach ( $words as $word ) {
		$word = trim($word);
		if ( false === empty($word) ) {
			$alias .= $word[0];
		}
	}
	
	return $alias;
}

function db_now($time = NULL) {
	if ( true === empty($time) ) {
		$time = time();
	}
	return date('Y-m-d H:i:s', $time);
}