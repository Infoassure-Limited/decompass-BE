<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $fillable = ['client_id', 'created_by', 'last_modified_by', 'template_id', 'template_link', 'link', 'sfdt_format', 'remark', 'status', 'is_exception'];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function template()
    {
        return $this->belongsTo(DocumentTemplate::class, 'template_id', 'id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function lastModifier()
    {
        return $this->belongsTo(User::class, 'last_modified_by ', 'id');
    }
    public function getFullDocumentLinkAttribute()
    {
        return env('APP_URL') . '/storage/' . $this->link;
    }
}
