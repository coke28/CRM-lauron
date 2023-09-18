<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmClient extends Model
{
    use HasFactory;

           /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'clientName',
        'clientDescription',
    ];

    
   
    protected $table = 'crmClient';

    public $timestamps = false;

}
