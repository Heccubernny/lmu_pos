<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoniepointTransaction extends Model
{
    use HasFactory;

    protected $table = 'moniepoint_transactions';

    protected $fillable = [
        'reference',
        'amount',
        'payment_method',
        'terminal_id',
        'status',
        'customer_name',
        'bank_name',
        'account_number',
        'card_brand',
        'card_last_4',
        'sale_id',
        'recipt_number',
        'store_id',
        'cashier_id',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    /**
     * Relationship to Store.
     */
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    /**
     * Relationship to Cashier (User).
     */
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id', 'person_id');
    }

    /**
     * Relationship to Sale.
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'recipt_number', 'recipt_number');
    }
}
