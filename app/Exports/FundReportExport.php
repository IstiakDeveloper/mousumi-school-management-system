<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Carbon\Carbon;

class FundReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $transactions;
    protected $year;
    protected $month;
    protected $summary;

    public function __construct($transactions, $summary, $year, $month)
    {
        $this->transactions = $transactions;
        $this->summary = $summary;
        $this->year = $year;
        $this->month = $month;
    }

    public function collection()
    {
        return collect($this->transactions);
    }

    public function headings(): array
    {
        return [
            ['Fund Management Report'],
            ['Period: ' . Carbon::createFromDate($this->year, $this->month, 1)->format('F Y')],
            ['Opening Balance: ৳' . number_format($this->summary['opening_balance'], 2)],
            [''],
            ['Date', 'Description', 'Income', 'Expense', 'Running Balance']
        ];
    }

    public function map($transaction): array
    {
        return [
            Carbon::parse($transaction['date'])->format('d M, Y'),
            $transaction['description'],
            $transaction['type'] === 'in' ? number_format($transaction['amount'], 2) : '',
            $transaction['type'] === 'out' ? number_format($transaction['amount'], 2) : '',
            number_format($transaction['running_balance'], 2)
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = count($this->transactions) + 5; // 5 is the number of header rows

        // Style for title
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal('center');

        // Style for period and opening balance
        $sheet->mergeCells('A2:E2');
        $sheet->mergeCells('A3:E3');
        $sheet->getStyle('A2:A3')->getAlignment()->setHorizontal('left');

        // Style for headers
        $sheet->getStyle('A5:E5')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E9ECEF']
            ]
        ]);

        // Align numbers to right
        $sheet->getStyle('C6:E' . $lastRow)->getAlignment()->setHorizontal('right');

        // Add totals row
        $totalRow = $lastRow + 1;
        $sheet->setCellValue('A' . $totalRow, 'Totals');
        $sheet->mergeCells('A' . $totalRow . ':B' . $totalRow);
        $sheet->setCellValue('C' . $totalRow, '=SUM(C6:C' . $lastRow . ')');
        $sheet->setCellValue('D' . $totalRow, '=SUM(D6:D' . $lastRow . ')');
        $sheet->setCellValue('E' . $totalRow, number_format($this->summary['closing_balance'], 2));

        // Style totals row
        $sheet->getStyle('A' . $totalRow . ':E' . $totalRow)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F8F9FA']
            ]
        ]);

        // Add borders
        $sheet->getStyle('A5:E' . $totalRow)->getBorders()->getAllBorders()->setBorderStyle('thin');

        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            5 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 40,
            'C' => 15,
            'D' => 15,
            'E' => 15,
        ];
    }

    public function title(): string
    {
        return Carbon::createFromDate($this->year, $this->month, 1)->format('F Y');
    }
}
