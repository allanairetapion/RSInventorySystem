<?php
   
	
namespace App;

use Illuminate\Database\Eloquent\Model;	
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as ResetPassword;

	
	class IsUser extends Authenticatable{
		
		public $timestamps = false;
		protected $table = "is_users";
	
		protected $fillable = ['first_name','last_name','email','password','phone_number','date_created','confirmation_code'];
		
		
		    protected $hidden = [
        'password', 'remember_token',
    ];
		

			
	}
	

?>