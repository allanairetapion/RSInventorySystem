<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientProfile extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = "client_profiles";
	protected $fillable = [ 
			'first_name',
			'last_name',
			'client_id',
	];
	public function client() {
		return $this->belongsTo ( "App\User" );
	}
}
