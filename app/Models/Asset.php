<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory; // SoftDeletes;
    protected $connection = 'mysql';
    protected $fillable = ['name', 'client_id', 'asset_type_id', 'description', 'purpose', 'classification', 'information_stored', 'location', 'owner_id', 'owner'];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function assetType()
    {
        return $this->belongsTo(AssetType::class);
    }
    public function riskAssessments()
    {
        return $this->hasMany(RiskAssessment::class);
    }
    public function riskRegisters()
    {
        return $this->hasMany(RiskRegister::class);
    }
}
