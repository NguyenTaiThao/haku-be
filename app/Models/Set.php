<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
    use HasFactory;

    protected $fillable = ['creator_id', 'name', 'description'];

    protected $table = 'sets';

    protected $with = ['cards'];

    protected $appends = ['card_count', "learned_percent"];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function cards()
    {
        return $this->hasMany(Card::class, 'set_id', 'id');
    }

    public function getCardCountAttribute()
    {
        return $this->cards()->count();
    }

    public function getLearnedPercentAttribute()
    {
        return 50;
    }
}
