<?php
 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 class TicketMessages extends Model{
  
  
  protected $table = "ticket_messages";
 
  protected $fillable = ['id','ticket_id','message','sender'];
  
 }
 ?>