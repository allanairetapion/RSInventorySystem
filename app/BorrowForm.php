<?php
 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 class BorrowForm extends Model{
  
  public $timestamps = false;
  protected $table = "borrow_form";
 
  protected $fillable = ['item','model','brand','unique_identifier','item_no','lent','borrower','date_borrowed'];
  
 }
 ?>