<?php

namespace App\Traits;

use App\Models\User;

trait HasCreatedByAndUpdatedByTrait
{
    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updated_by()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }
}
