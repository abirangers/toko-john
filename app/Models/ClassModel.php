<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

// Rename to ClassModel.php to avoid conflicts with PHP keywords
class ClassModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Sluggable;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'major_id'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    
}