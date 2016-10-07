<?php
 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 class ItemIssues extends Model{
  
 
  protected $table = "issue_logs";
 
  protected $fillable = ['itemNo','issue','damage','itemUser','reported_by','date_reported','created_at','updated_at'];
  
 }
 ?>