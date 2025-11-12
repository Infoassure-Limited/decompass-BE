<?php

namespace App\Models\VendorDueDiligence;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DueDiligenceEvidence extends Model
{
    use HasFactory;
    protected $connection = 'vdd';
    protected $table = 'due_diligence_evidence';

    public function getFullDocumentLinkAttribute()
    {
        return env('APP_URL') . '/storage/' . $this->link;
    }
}
