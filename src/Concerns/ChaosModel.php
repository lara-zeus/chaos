<?php

namespace LaraZeus\Chaos\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

trait ChaosModel
{
    public static function booted(): void
    {
        parent::booted();

        static::creating(function ($model) {
            $model->setAttribute('created_by', auth()?->user()?->id ?? 0);
        });

        static::updating(function ($model) {
            $model->setAttribute('updated_by', auth()?->user()?->id ?? 0);
        });
    }

    public function createdBy(): BelongsTo
    {
        $userModel = config('auth.providers.users.model', \Illuminate\Foundation\Auth\User::class);
        return $this->belongsTo($userModel, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        $userModel = config('auth.providers.users.model', \Illuminate\Foundation\Auth\User::class);
        return $this->belongsTo($userModel, 'updated_by');
    }

    public static function isUsingActionBy(): bool
    {
        return true;
    }

    public static function isUsingSoftDelete(): bool
    {
        return in_array(SoftDeletes::class, class_uses((new static)), true)
            && ! (new static)->forceDeleting;
    }
}
