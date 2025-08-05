<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomHomeSectionItem extends Model
{
    protected $fillable = [
        'section_id',
        'content_id',
        'content_type',
        'order',
    ];

    public function section()
    {
        return $this->belongsTo(CustomHomeSection::class, 'section_id');
    }

    public function content()
    {
        return $this->morphTo(null, 'content_type', 'content_id');
    }
}

