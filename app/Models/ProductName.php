<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductName extends Model
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

   
    protected $table = 'productName';

    public $timestamps = false;
}
