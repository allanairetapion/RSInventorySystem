<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketTopics extends Model
{
    protected $table = "ticket_topics";
	public $timestamps = false;
	protected $fillable = [
         'description','status',
    ];
}
