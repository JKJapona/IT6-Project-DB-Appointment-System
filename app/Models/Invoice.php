<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $primaryKey = 'invoice_id';
    protected $fillable = ['appointment_id', 'total_amount', 'payment_status', 'issued_date'];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'invoice_id');
    }

    public function appointment() {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
}
