<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kredit extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function piutang(): BelongsTo
    {
        return $this->belongsTo(Piutang::class);
    }
}
