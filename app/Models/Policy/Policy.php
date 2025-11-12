<?php

namespace App\Models\Policy;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Policy extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection = 'sec_policies';

    protected $fillable = [
        'client_id',
        'title',
        'content',
        'document_number',
        'category_id',
        'owner_id',
        'status',
        'published_at',
        'effective_date',
        'review_date',
        'expiry_date',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'published_at' => 'date',
        'effective_date' => 'date',
        'review_date' => 'date',
        'expiry_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(PolicyCategory::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function versions()
    {
        return $this->hasMany(PolicyVersion::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps()
            ->withPivot(['read_at', 'acknowledged_at']);
    }

    public function audits()
    {
        return $this->hasMany(PolicyAudit::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'review');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopeDrafts($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeRequiringReview($query)
    {
        return $query->where('review_date', '<=', now()->addMonths(1))
            ->where('status', 'published');
    }
}
