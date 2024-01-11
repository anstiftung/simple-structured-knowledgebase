<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait HasCreatedByAndUpdatedByTrait
{
    public static function bootHasCreatedByAndUpdatedByTrait()
    {
        static::creating(function ($model) {
            if (!$model->isDirty('created_by_id') && Auth::id()) {
                $model->created_by_id = Auth::id();
            }
            if (!$model->isDirty('updated_by_id') && Auth::id()) {
                $model->updated_by_id = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (!$model->isDirty('updated_by_id') && Auth::id()) {
                $model->updated_by_id = Auth::id();
            }
        });
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updated_by()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }
}
