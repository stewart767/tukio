<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'content',
        'meta_description',
        'meta_title',
        'is_active',
        'sort_order',
        // Homepage specific fields
        'welcome_title',
        'welcome_content',
        'benefits_title',
        'benefits_subtitle',
        'benefits_content',
        'faq_title',
        'faq_subtitle',
        'faq_content',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
