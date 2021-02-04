<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'content', 'expiration_date', 'image'
    ];

    public function category(){
        return $this->belongsTo("App\Category");
    }
}
