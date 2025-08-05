<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomHomeSection extends Model
{
    protected $fillable = [
        'name',
        'active',
        'order',
    ];

    // Relação 1:N com os itens da seção
    public function items(): HasMany
    {
        return $this->hasMany(CustomHomeSectionItem::class, 'section_id')
            ->orderBy('order')
            ->limit(10); // ⬅️ limita a 10
    }
}
