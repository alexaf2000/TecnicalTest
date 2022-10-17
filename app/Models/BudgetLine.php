<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'net_amount', 'vat', 'vat_amount', 'total_amount', 'created_at', 'updated_at'
    ];

    /**
     * Returns the budget owner of this line
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    /**
     * Returns the VAT amount based on the existens attributes
     *
     * @param float $netAmount
     * @param float $vat
     * @return float
     */
    static public function calculateVatAmount(float $netAmount, float $vat): float
    {
        return floatval(($netAmount * $vat) / 100);
    }

    /**
     * Returns the total of the current budget line
     *
     * @param float $netAmount
     * @param float $vat
     * @return float
     */
    static public function calculateTotal(float $netAmount, float $vat): float
    {
        return floatval($netAmount + $vat);
    }
}
