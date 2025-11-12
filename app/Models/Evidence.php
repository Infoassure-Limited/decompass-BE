<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evidence extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'evidence';
    protected $fillable = [
        'title',
        'consulting_id'
    ];
    public function consulting()
    {
        return $this->belongsTo(Consulting::class);
    }

    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }

    public function clientEvidences()
    {
        return $this->hasMany(ClientEvidence::class, 'evidence_id', 'id');
    }
}
