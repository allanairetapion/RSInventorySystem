<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tickets extends Model {
	use SoftDeletes;
	protected $dates = [
			'deleted_at'
	];
	protected $table = "tickets";
	protected $fillable = [ 
			'id',
			'sender',
			'sender_id',
			'topic_id',
			'subject',
			'assigned_support',
			'summary',
			'priority_level',
			'attachments',
			'ticket_status',
			'department',
			'closed_by',
			'closing_report' 
	];
}