<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'is_available',
        'stock',
        'category_id'
    ];
    
    // Relasi ke kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    // Relasi ke order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}