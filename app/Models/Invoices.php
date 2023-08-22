<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoices extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable =['invoice_number' ,'invoice_date' ,'due_date' ,
                          'product', 'sections_id' ,'discount' ,'rate_vat' ,
                           'value_vat' ,'total' ,'status', 'value_status',
                            'note','amount_comission','amount_collection',
        'status','value_status', 'user','payment_date'

        ];



    public function sections(){
        return $this->belongsTo(Section::class);
    }
}
