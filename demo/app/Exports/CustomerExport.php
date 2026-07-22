<?php

namespace App\Exports;

use App\Models\Customers;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class CustomerExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $data;

    public function __construct()
    {
        
    }

    public function view(): View
    {
        // dd($data);
        return view('book_download', [
            'invoices' => Customers::where('customer_type', 1)->get()
        ]);
    }
}
