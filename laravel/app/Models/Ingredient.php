<?php

namespace App\Models;

use App\Models\Recipe;
use App\Models\License;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCreatedByAndUpdatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ingredient extends Model
{
    use HasFactory;
    use HasCreatedByAndUpdatedByTrait;

    protected $fillable = [
        'title',
        'description',
        'filename',
        'source',
        'license_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function license()
    {
        return $this->belongsTo(License::class, 'license_id');
    }

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class);
    }
}
