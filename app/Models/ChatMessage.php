<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $hidden = ['created_at', 'updated_at'];

//    protected $appends = ['sent'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*public function getSentAttribute()
    {
        return $this->created_at->diffForHumans();
    }*/
}