<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    
	protected $table = "tickets";

	protected $fillable = [
         'sender','topic','subject','summary','priority','status','department','closed_by','closing_report',
    ];

	

}