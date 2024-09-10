<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity',
        'code',
        'area',
        'category_id',
        'company_id',
        'brand',
        'model',
        'serie',
        'state',
        'status',
        'performance',
        'age',
        'usefulLife',
        'headquarter_id',
        'amount',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function headquarter(): BelongsTo
    {
        return $this->belongsTo(Headquarter::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    // table name
    protected $table = 'assets';
}
