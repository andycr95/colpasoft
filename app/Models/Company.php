<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nit',
        'name',
        'address',
        'phone',
        'email',
        'status',
    ];

    // table name
    protected $table = 'companies';

    //Relationships
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function headquarters(): HasMany
    {
        return $this->hasMany(Headquarter::class);
    }
}
