<?php
 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 class BrokenItem extends Model{
  
  
  protected $table = "broken_logs";
 
  protected $fillable = ['itemNo','damage','reported_by','itemUser','brokenStatus','brokenSummary','created_at','updated_at'];
  
 }
 ?>