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
        $row = static::query()
            ->where('code', $code)
            ->where('key', $key)
            ->where('user_id', $userId)
            ->first();
        
        return $row ? $row->typed_value : $default;
    }
    
    public static function put(string $code, string $key, $value, ?int $userId = null, bool $json = false): self
    {
        return static::query()->updateOrCreate(
            ['user_id' => $userId, 'code' => $code, 'key' => $key],
            [
                'json' => $json,
                'value' => $json ? json_encode($value, JSON_UNESCAPED_UNICODE) : (string) $value,
            ]
        );
    }
}