<?php
 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 class MaintenanceSchedule extends Model{
  
  protected $table = "maintenance_schedules";
 
  protected $fillable = ['activities','agents','area','title','status','start_date','end_date'];
  
 }
 ?>