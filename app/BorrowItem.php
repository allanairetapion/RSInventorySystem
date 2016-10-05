<?php
 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 class BorrowItem extends Model{
  
  protected $table = "borrow_logs";
 
  protected $fillable = ['itemNo','borrowee','borrowerStationNo','borrower','created_at','updated_at'];
  
 }
 ?>