<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_transaction',
        'max_discount',
        'quota',
        'used_count',
        'user_limit',
        'user_type',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_transaction' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function usages()
    {
        return $this->hasMany(VoucherUsage::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeValid($query)
    {
        $now = Carbon::now();
        return $query->where(function($q) use ($now) {
            $q->where(function($subQ) use ($now) {
                $subQ->whereNull('valid_from')
                     ->orWhere('valid_from', '<=', $now);
            })
            ->where(function($subQ) use ($now) {
                $subQ->whereNull('valid_until')
                     ->orWhere('valid_until', '>=', $now);
            });
        });
    }

    public function scopeAvailable($query)
    {
        return $query->where(function($q) {
            $q->whereNull('quota')
              ->orWhereRaw('used_count < quota');
        });
    }

    // Helpers
    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();

        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }

        if ($this->valid_until && $now->gt($this->valid_until)) {
            return false;
        }

        return true;
    }

    public function isAvailable()
    {
        if ($this->quota && $this->used_count >= $this->quota) {
            return false;
        }

        return true;
    }

    public function canBeUsedBy($userId)
    {
        if (!$userId) {
            // Guest user
            if ($this->user_type === 'registered') {
                return false;
            }
            return true;
        }

        // Check user limit
        $usageCount = VoucherUsage::where('voucher_id', $this->id)
            ->where('user_id', $userId)
            ->count();

        return $usageCount < $this->user_limit;
    }

    public function calculateDiscount($subtotal)
    {
        if ($subtotal < $this->min_transaction) {
            return 0;
        }

        if ($this->type === 'percentage') {
            $discount = $subtotal * ($this->value / 100);
            
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }

            return $discount;
        }

        // fixed_amount
        return min($this->value, $subtotal);
    }

    public function getFormattedValueAttribute()
    {
        if ($this->type === 'percentage') {
            return $this->value . '%';
        }
        return 'Rp ' . number_format($this->value, 0, ',', '.');
    }

    public function getStatusBadgeAttribute()
    {
        if (!$this->is_active) {
            return '<span class="badge bg-secondary">Nonaktif</span>';
        }

        if (!$this->isValid()) {
            return '<span class="badge bg-warning">Kadaluarsa</span>';
        }

        if (!$this->isAvailable()) {
            return '<span class="badge bg-danger">Habis</span>';
        }

        return '<span class="badge bg-success">Aktif</span>';
    }
}
