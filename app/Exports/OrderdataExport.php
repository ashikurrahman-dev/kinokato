<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Orderproduct;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderdataExport implements  FromCollection,WithHeadings,WithCustomStartCell, WithStyles
{
 
    use Exportable;
    private $collectionA; 

    public function __construct($collectionA)
    {
        $this->data = $collectionA; 
    }
    public function startCell(): string
    {
        return 'A3';
    }

    public function collection()
    { 
        return $this->data;
         
    } 


    public function headings(): array
    {

        return ["SL","Invoice","Contact","Courier Id","Paid","Return","COD","Qty","Category","SKU","Sigment","Size","Agent","Status","Form",'Note'];

    }
    
    
    public function styles(Worksheet $sheet)
    {
        $sheet->setCellValue('E1', 'RASHI FASHION');
        $sheet->mergeCells('E1:J1'); // Merge across multiple columns
        $sheet->getStyle('E1')->applyFromArray([
            'font' => [
                'name'  => 'Algerian', // Custom font family, replace with your preferred font
                'bold'  => true,
                'size'  => 24,
                'color' => ['rgb' => '000000'], // Set text color (optional)
            ],
        ]);
        $sheet->setCellValue('E2', 'Date:'.date('Y-m-d'));
        $sheet->mergeCells('E2:J2'); // Merge across multiple columns
        $sheet->getStyle('E2')->getFont()->setBold(true)->setSize(14); // Apply some styles
    }



}
