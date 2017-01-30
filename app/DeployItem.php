<?php
 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 class DeployItem extends Model{
  
  protected $table = "deploy_logs";
 
  protected $fillable = ['itemNo','deploy_by','stationNo','created_at','updated_at'];
  
 }
 ?>