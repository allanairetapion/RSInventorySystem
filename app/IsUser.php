<?php
   
	
namespace App;

use Illuminate\Database\Eloquent\Model;	
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as ResetPassword;

	
	class IsUser extends Authenticatable{
		
		public $timestamps = false;
	protected $table = "admin";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'id','uid','email', 'password','user_type','date_registered'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	public function adminProfile(){
		return $this->hasOne("App\AdminProfile",'agent_id');
	}
		

			
	}
	

?>