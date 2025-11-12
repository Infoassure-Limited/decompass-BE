<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exception extends Model
{
    use HasFactory;
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function clause()
    {
        return $this->belongsTo(Clause::class);
    }
    public function upload()
    {
        return $this->belongsTo(Upload::class);
    }
    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
