<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceMedia extends Model
{
    use HasFactory;
    protected $fillable = [
        'resource_id',
        'image_link'
    ];
    /**
     * Get the resource that owns the ResourceMedia
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
