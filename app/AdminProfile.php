<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminProfile extends Model
{
	
	protected $table = "admin_profiles";

protected $fillable = [
        'first_name', 'last_name','agent_id',
    ];

	public function client(){
		return $this->belongsTo("App\Admin");
	}

}
