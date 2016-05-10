<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
	public $timestamps = false;
	protected $table = "clients";
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password','department_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
    ];
	
	public function client_profile(){
		return $this->hasOne("App\ClientProfile");
	}
	
}
