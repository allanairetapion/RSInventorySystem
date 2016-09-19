<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketTopics extends Model
{
    protected $table = "ticket_topics";
	public $timestamps = false;
	protected $fillable = [
         'description','default_priority','status','date_updated'
    ];
}
