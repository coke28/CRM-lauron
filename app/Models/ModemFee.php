<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModemFee extends Model
{
    use HasFactory;

         /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product',
        'statusCode',
        'statusID',
        'statusName',
        'statusDefinition',
        'status'

    ];

   
    protected $table = 'modemFee';

    public $timestamps = false;
}
