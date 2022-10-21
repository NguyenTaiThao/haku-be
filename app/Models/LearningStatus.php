<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningStatus extends Model
{
    use HasFactory;

    protected $fillable = ['card_id', 'user_id', 'is_remembered'];

    protected $table = 'learning_status';

    public function card()
    {
        return $this->belongsTo(Card::class, 'card_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
