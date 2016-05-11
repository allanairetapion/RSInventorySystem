<?php
   
	
	namespace App;
	
	use Illuminate\Database\Eloquent\Model;
	
	class IsUser extends Model{
		
		public $timestamps = false;
		protected $table = "is_users";
		
		
		protected $fillable = ['email','password'];
		protected $hidden = ['password'];
		
		
	}
	

?>
