<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'section',
        'type',
    ];

    /**
     * Retrieve a setting value.
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = static::query()->where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return $setting->casted_value;
    }

    /**
     * Persist a setting value.
     */
    public static function setValue(string $key, $value, string $section = 'general', ?string $type = null): Setting
    {
        return static::query()->updateOrCreate(
            ['key' => $key],
            [
                'value' => static::prepareValue($value, $type),
                'section' => $section,
                'type' => $type ?? (is_array($value) ? 'json' : 'string'),
            ]
        );
    }

    /**
     * Format the value before persistence.
     */
    protected static function prepareValue($value, ?string $type = null)
    {
        $actualType = $type ?? (is_array($value) ? 'json' : 'string');

        if ($actualType === 'json' && is_array($value)) {
            return json_encode($value);
        }

        return $value;
    }

    /**
     * Accessor for decoded value.
     */
    public function getCastedValueAttribute()
    {
        if (($this->type ?? null) === 'json') {
            return json_decode($this->attributes['value'] ?? '[]', true) ?? [];
        }

        return $this->attributes['value'] ?? null;
    }
}
