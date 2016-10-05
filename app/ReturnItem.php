<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model{

	public $timestamps = false;
	protected $table = "return_logs";

	protected $fillable = ['itemNo','receiver','borrower','created_at','updated_at'];

}
?>