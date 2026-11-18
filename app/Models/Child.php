<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Child extends Model
{
    protected $table = 'children';

    protected $fillable = ['parent_id', 'name', 'birth_date', 'child_category_id', 'special_needs'];

    protected function casts(): array
    {
        return ['birth_date' => 'date'];
    }

    public function parent(): BelongsTo { return $this->belongsTo(User::class, 'parent_id'); }
    public function category(): BelongsTo { return $this->belongsTo(ChildCategory::class, 'child_category_id'); }
}
