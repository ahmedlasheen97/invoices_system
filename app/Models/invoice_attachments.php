<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoice_attachments extends Model
{
    use HasFactory;
   

    protected $fillable = [
        'file',
        'invoice_number',
        'invoice_id',
        'created_by',
        'updated_by',
    ];

    public function invoice()
    {
        return $this->belongsTo(invoices::class, 'invoice_id');
    }
   
}
