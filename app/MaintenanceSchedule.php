<?php
 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 class MaintenanceSchedule extends Model{
  
  protected $table = "maintenance_schedules";
 
  protected $fillable = ['activities','area','title','status','start_date','end_date'];
  
 }
 ?>