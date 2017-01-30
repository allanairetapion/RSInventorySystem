<?php
 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 class RepairItem extends Model{
  
  protected $table = "repair_logs";
 
  protected $fillable = ['itemNo','damage','repaired_by','created_at','updated_at'];
  
 }
 ?>