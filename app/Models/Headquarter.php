<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Headquarter extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'address',
        'company_id',
        'status',
    ];

    // table name
    protected $table = 'headquarters';

    //Relationships
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
