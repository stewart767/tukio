<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateField extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'field_key',
        'field_label',
        'field_type',
        'is_required',
        'default_value',
        'sort_order',
        'configuration',
    ];

    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'sort_order' => 'integer',
            'configuration' => 'array',
        ];
    }

    /**
     * Get the template that owns the field.
     */
    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}
