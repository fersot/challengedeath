<?php

namespace App\Http\Models\Entities;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'event';

    protected $primaryKey = 'id';

    public $timestamps = false;
    public $fillable = [
        'id',
        'email',
        'name',
        'date',
    ];
}
