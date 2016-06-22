<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientProfile extends Model
{
	public $timestamps = false;
	protected $table = "client_profiles";

protected $fillable = [
        'first_name', 'last_name','client_id','date_registered'
    ];

	public function client(){
		return $this->belongsTo("App\User");
	}

}
