<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'pos_items';
    protected $primaryKey = 'item_id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'category',
        'supplier',
        'item_number',
        'description',
        'cost_price',
        'unit_price',
        'quantity',
        'staff_id',
        'status',
    ];

    /**
     * Relationship to Category model mapped on the Category name.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category', 'name');
    }

    /**
     * Accessor for category_id (backwards compatibility).
     */
    public function getCategoryIdAttribute()
    {
        $cat = Category::where('name', $this->category)->first();
        return $cat ? $cat->id : null;
    }

    /**
     * Mutator for category_id (backwards compatibility).
     */
    public function setCategoryIdAttribute($value)
    {
        $cat = Category::find($value);
        if ($cat) {
            $this->attributes['category'] = $cat->name;
        }
    }
}
