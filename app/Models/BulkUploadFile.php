<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulkUploadFile extends Model
{
    protected $connection = 'mysql';

    protected $fillable = ['client_id', 'user_id', 'type', 'filename', 'path', 'columns', 'data', 'status'];
    protected $casts = [
        'columns' => 'array',
        'data' => 'array',
        'status' => 'string',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
