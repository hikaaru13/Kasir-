<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Font;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $methodPayment;
    protected $productId;

    public function __construct($startDate = null, $endDate = null, $methodPayment = null, $productId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->methodPayment = $methodPayment;
        $this->productId = $productId;
    }

    public function collection()
    {
        $query = Transaction::with('product');

        // Apply date range filter
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [
                $this->startDate . ' 00:00:00', 
                $this->endDate . ' 23:59:59'
            ]);
        }

        // Apply payment method filter
        if ($this->methodPayment) {
            $query->where('method_payment', $this->methodPayment);
        }

        // Apply product filter
        if ($this->productId) {
            $query->where('product_id', $this->productId);
        }

        // Order by date descending
        $query->orderBy('created_at', 'desc');

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Transaction ID',
            'Customer Name',
            'Product Name',
            'Product Price',
            'Quantity',
            'Payment Method',
            'Total Transaction',
            'Transaction Date',
            'Transaction Time'
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->transaction_id,
            $transaction->customer_name,
            $transaction->product->name ?? 'N/A',
            number_format($transaction->product->price ?? 0, 2),
            $transaction->qty,
            ucfirst($transaction->method_payment),
            number_format($transaction->total, 2),
            $transaction->created_at->format('d F Y'),
            $transaction->created_at->format('H:i:s')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header styling
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => Color::COLOR_WHITE]
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF4472C4'] // Dark blue background
            ]
        ]);

        // Alternate row coloring
        $sheet->getStyle('A2:I' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000']
                ]
            ]
        ]);

        // Zebra striping
        $highestRow = $sheet->getHighestRow();
        for ($i = 2; $i <= $highestRow; $i += 2) {
            $sheet->getStyle('A'.$i.':I'.$i)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFF0F0F0'); // Light gray for alternate rows
        }

        return $sheet;
    }
}