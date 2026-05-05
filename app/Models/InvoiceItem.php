<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $primaryKey = 'item_id';
    protected $fillable = ['invoice_id', 'service_id', 'quantity', 'unit_price'];

    public function invoice() {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function service() {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
