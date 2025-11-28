<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'total_amount',
        'customer_name',
        'customer_phone',
        'table_number',
        'order_type',
        'special_requests',
        'completed_at',
        'payment_status',
        'payment_method',
        'payment_reference',
        'paid_at',
        'amount_paid',
        'change_amount',
        'snap_token'
    ];
    
    protected $casts = [
        'completed_at' => 'datetime',
    ];
    
    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relasi ke order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    // Relasi ke menus melalui order items
    public function menus()
    {
        return $this->hasManyThrough(
            Menu::class,
            OrderItem::class,
            'order_id',
            'id',
            'id',
            'menu_id'
        );
    }
}