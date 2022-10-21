<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = ['set_id', 'front_content', 'back_content'];

    protected $table = 'cards';

    public function set()
    {
        return $this->belongsTo(Set::class, 'set_id', 'id');
    }
}
