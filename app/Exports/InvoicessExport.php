<?php

namespace App\Exports;

use App\Models\Invoices;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InvoicessExport implements FromCollection, WithHeadings ,ShouldAutoSize

{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Invoices::select(['invoice_number','invoice_date' ,'due_date' ,
            'product', 'discount' ,'rate_vat' ,
            'value_vat' ,'total' ,'status', 'payment_date',
            'note','amount_comission','amount_collection',])->get();
    }

    public function headings(): array
    {
        return [
            'invoice_number','invoice_date' ,'due_date' ,
            'product', 'discount' ,'rate_vat' ,
            'value_vat' ,'total' ,'status', 'payment_date',
            'note','amount_comission','amount_collection',
        ];
    }

}
