<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    public $timestamps = false;
	protected $table = "tickets";

	protected $fillable = [
         'id','sender','sender_email','topic_id','subject','assigned_support','summary','priority','ticket_status','updated_at','department','closed_by','closing_report',
    ];

	

}