<?php
 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 class MaintenanceActivity extends Model{
  
  protected $table = "maintenance_activities";
 
  protected $fillable = ['activity','description'];
  
 }
 ?>