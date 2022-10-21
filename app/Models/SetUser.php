<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetUser extends Model
{
    use HasFactory;

    protected $fillable = ['set_id', 'use_id'];

    protected $table = 'set_users';
}
