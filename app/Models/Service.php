<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    protected $primaryKey = 'service_id';
    protected $fillable = ['service_name', 'department_id', 'price'];

    /**
     * Relationship to Department
     * Links department_id to the Departments table
     */
    public function department(): BelongsTo 
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    /**
     * Relationship to Invoice Items
     * A service can appear in many invoice line items
     */
    public function invoiceItems(): HasMany 
    {
        // 'service_id' is the foreign key on the Invoice_Items table
        // 'service_id' is the local key on the Services table
        return $this->hasMany(InvoiceItem::class, 'service_id', 'service_id');
    }
}