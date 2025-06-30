<?php

namespace App\Exports;

use App\Organization;
use App\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Hyperlink;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TicketsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $tickets = Ticket::all();
        $tickets = $tickets->map(function ($ticket, $key) {
            return [
                "id" => $ticket->id,
                "organization" => $ticket->organization->org_name,
                "ticket_holder" => $ticket->ticketHolder ? $ticket->ticketHolder->getFullName() : "",
                "assigned_to" => $ticket->assignedTo ? $ticket->assignedTo->getFullName() : "",
                "status" => $ticket->getStatusNameAttribute(),
                "category" => $ticket->getCategoryNameAttribute(),
                "subject" => $ticket->name,
                "priority" => $ticket->getPriorityNameAttribute(),
                "ticket_date" => $ticket->created_at,
                "due_date" => $ticket->due_date
            ];
        });
        // dd($tickets);
        return $tickets;
    }

    public function headings(): array
    {
        return [
            "No#",
            "Organization",
            "TicketHolder",
            "Assigned to",
            "Status",
            "Category",
            "Subject",
            "Priority",
            "Ticket Date",
            "Due Date"
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ["font" => ["bold" => true]]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                /** @var Worksheet $sheet */
                foreach ($event->sheet->getColumnIterator('A', "A") as $row) {
                    foreach ($row->getCellIterator() as $cell) {
                        $id = $cell->getValue();
                        if($id != "No#"){

                            $link = url("/update-ticket/" . $id);
                            $cell->setHyperlink(new Hyperlink($link, 'Read'));

                            // Upd: Link styling added
                            $event->sheet->getStyle($cell->getCoordinate())->applyFromArray([
                                'font' => [
                                    'color' => ['rgb' => '0000FF'],
                                    'underline' => 'single'
                                ]
                            ]);
                        }

                    }
                }
            },
        ];
    }
}
