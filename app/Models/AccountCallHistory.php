<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountCallHistory extends Model
{
    use HasFactory;

    protected $table = 'accountCallHistory';
    public $timestamps = false;
}
