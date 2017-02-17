<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceSchedule extends Model {
	use SoftDeletes;
	protected $table = "maintenance_schedules";
	protected $dates = [ 
			'deleted_at' 
	];
	protected $fillable = [ 
			'activities',
			'agents',
			'area',
			'title',
			'status',
			'start_date',
			'end_date' 
	];
}
?>