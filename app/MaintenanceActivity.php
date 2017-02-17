<?php
 
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
 
 class MaintenanceActivity extends Model{
  use SoftDeletes;
  protected $table = "maintenance_activities";
  protected $dates = [ 
			'deleted_at' 
	];
  protected $fillable = ['id','activity','description','isDeleted'];
  
 }
 ?>