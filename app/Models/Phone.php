<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;

       /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phoneBrand',
        'phoneModel',
        'phonePrice',
    ];

    
   
    protected $table = 'phones';

    public $timestamps = false;
}
