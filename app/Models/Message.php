<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'user_id',
        'content',
        'type',
        'sent_at',
    ];

    protected $casts = [
        'id' => 'string',
        'sent_at' => 'datetime',
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

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachment()
    {
        return $this->hasOne(Attachment::class);
    }
}
