<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
   	
	protected $table = "tickets";

	protected $fillable = [
         'id',
         'sender',
         'sender_id',
         'topic_id',
         'subject',
         'assigned_support',
         'summary',
         'priority',
         'ticket_status',
         'department',
         'closed_by',
         'closing_report',
    ];

	

}