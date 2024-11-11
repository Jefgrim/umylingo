<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Deck extends Model
{
    use HasFactory;
    protected $fillable = [
        'language',
        'deck_description'
    ];

    public function cards()
    {
        return $this->hasMany(Card::class);
    }
}
