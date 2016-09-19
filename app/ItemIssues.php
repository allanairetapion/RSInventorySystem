<?php
 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 class ItemIssues extends Model{
  
 
  protected $table = "issue_logs";
 
  protected $fillable = ['unique_id','issue','damage','reported_by','date_reported','created_at'];
  
 }
 ?>