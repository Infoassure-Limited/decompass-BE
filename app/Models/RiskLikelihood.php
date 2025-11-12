<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskLikelihood extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'name', 'value', 'matrix', 'summary'];
}
