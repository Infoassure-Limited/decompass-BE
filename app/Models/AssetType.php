<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class AssetType extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    protected $fillable = ['name', 'client_id'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
    protected function assetSamples(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
}
