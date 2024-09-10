<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'path',
        'asseet_id',
        'size',
        'type',
    ];

    /**
     * Get the asset that owns the file.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    // table name
    protected $table = 'files';
}
