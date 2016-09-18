<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'items';
    protected $fillable = [
    		'unique_id','itemNo','company', 'stationNo','itemType','model','brand','morningClient','nightClient','arrivalDate','itemStatus'
    ];
    
    
}