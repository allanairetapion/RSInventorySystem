<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class TicketTopics extends Model
{
	use SoftDeletes;
    protected $table = "ticket_topics";
    protected $dates = ['deleted_at'];
	protected $fillable = ['topic_id',
         'description','priority_level','status','date_updated'
    ];
}
