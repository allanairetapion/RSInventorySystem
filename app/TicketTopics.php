<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketTopics extends Model
{
    protected $table = "ticket_topics";
	public $timestamps = false;
	protected $fillable = [
         'topic_id','description','status','date_updated'
    ];
}
