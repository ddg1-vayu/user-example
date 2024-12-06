<?php

use Illuminate\Support\Facades\DB;

function getRoles()
{
	$roles = DB::table('roles')->select('id', 'name')->get();

	return $roles;
}
