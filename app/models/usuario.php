<?php

class usuario extends Eloquent{
	
	protected $table = 'partefpp_usuarios';

	public $timestamps = false;

        protected $primaryKey = "Id";
}
