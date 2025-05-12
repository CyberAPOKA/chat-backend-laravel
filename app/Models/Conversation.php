<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'type',
        'title',
        'created_by',
    ];

    protected $casts = [
        'id' => 'string',
        'type' => 'string',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) \Str::uuid();
            }
        });
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['last_read_at', 'joined_at'])
            ->withTimestamps();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
