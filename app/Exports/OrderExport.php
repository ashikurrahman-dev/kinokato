<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrderExport implements FromQuery, WithHeadings,WithMapping
{ 

    use Exportable; 

    public function map($order): array
    {
        return [
            $order->invoiceID,
            $order->customers->customerName,
            $order->customers->customerAddress,
            $order->customers->customerPhone,
            $order->subTotal,
            implode(', ', $order->orderproducts->pluck('productName')->toArray()),
            '',
            '',

        ];
    }

    public function query()
    { 
        return Order::with(['orderproducts', 'customers', 'cities', 'zones','areas'])->where('status', 'Ready to Ship');

    }


    public function headings(): array
    {
        return ["Invoice", "Name", "Address", "Phone", "Amount", "Note", "Contact Name", "Contact Phone"];
    }



}
