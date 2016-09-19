<?php
 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 class BrokenItem extends Model{
  
  
  protected $table = "broken_logs";
 
  protected $fillable = ['unique_id','damage','reported_by','created_at'];
  
 }
 ?>