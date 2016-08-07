<?php
 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 class TicketLogs extends Model{
  
  
  protected $table = "ticket_logs";
 
  protected $fillable = ['id','ticket_id','message','sender'];
  
 }
 ?>