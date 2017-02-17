<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model {
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'items';
	protected $fillable = [ 
			'unique_id',
			'itemNo',
			'serviceTag',
			'company',
			'stationNo',
			'itemType',
			'specification',
			'photo',
			'model',
			'brand',
			'morningClient',
			'nightClient',
			'created_at',
			'itemStatus',
			'updated_at' 
	];
}