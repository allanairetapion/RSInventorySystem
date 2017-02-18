<?php
 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 class MaintenanceStation extends Model{
  
 
  protected $table = "stations";
 
  protected $fillable = ['id','description','ipAddress'];
  
 }
 ?>