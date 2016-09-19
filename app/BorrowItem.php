<?php
 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 class BorrowItem extends Model{
  
  public $timestamps = false;
  protected $table = "borrow_logs";
 
  protected $fillable = ['unique_id','borrowee','borrowerStationNo','borrower','dateBorrowed'];
  
 }
 ?>