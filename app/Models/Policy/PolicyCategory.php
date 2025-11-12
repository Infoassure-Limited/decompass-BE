<?php

namespace App\Models\Policy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolicyCategory extends Model
{
    use HasFactory;
    protected $connection = 'sec_policies';

    protected $fillable = ['client_id', 'name', 'description'];

    public function policies()
    {
        return $this->hasMany(Policy::class, 'category_id');
    }
}