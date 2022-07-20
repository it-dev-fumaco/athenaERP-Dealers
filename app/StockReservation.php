<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockReservation extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'tabStock Reservation';
    protected $primaryKey = 'name';
    public $timestamps = false;
    protected $keyType = 'string';
}
