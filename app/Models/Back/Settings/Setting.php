<?php

namespace App\Models\Back\Settings;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['user_id', 'code', 'key', 'value', 'json'];
    
    protected $casts = [
        'json' => 'boolean',
    ];
    
    public function getTypedValueAttribute()
    {
        if ($this->json) {
            return json_decode($this->value ?? 'null', true);
        }
        
        return $this->value;
    }

    public static function get(string $code, string $key, ?int $userId = null, $default = null)
    {
        $q = static::query()
                   ->where('code', $code)
                   ->where('key', $key);

        if (is_null($userId)) {
            $q->whereNull('user_id');
        } else {
            $q->where('user_id', $userId);
        }

        $row = $q->orderByDesc('id')->first(); // orderByDesc za svaki slučaj

        return $row ? $row->typed_value : $default;
    }


    public static function put(string $code, string $key, $value, ?int $userId = null, bool $json = false): self
    {
        $q = static::query()
                   ->where('code', $code)
                   ->where('key', $key);

        if (is_null($userId)) {
            $q->whereNull('user_id');
        } else {
            $q->where('user_id', $userId);
        }

        $row = $q->orderByDesc('id')->first();

        $payload = [
            'user_id' => $userId,
            'code' => $code,
            'key' => $key,
            'json' => $json,
            'value' => $json ? json_encode($value, JSON_UNESCAPED_UNICODE) : (string) $value,
        ];

        if ($row) {
            $row->fill($payload)->save();
            return $row;
        }

        return static::create($payload);
    }
}