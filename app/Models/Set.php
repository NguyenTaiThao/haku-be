<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'description'];

    protected $table = 'sets';

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
}
