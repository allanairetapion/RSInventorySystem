<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminProfile extends Model {
	use SoftDeletes;
	protected $table = "admin_profiles";
	protected $dates = [ 
			'deleted_at' 
	];
	protected $fillable = [ 
			'first_name',
			'last_name',
			'agent_id' 
	];
	public function client() {
		return $this->belongsTo ( "App\Admin" );
	}
}
