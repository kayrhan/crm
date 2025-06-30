<?php

namespace App\Exports;

use App\Category;
use App\Helpers\Helper;
use App\Organization;
use App\Priority;
use App\Status;
use App\Ticket;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TicketExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents {

    use Exportable;

    protected $ticket_ids;

    function __construct($ticket_ids) {
        $this->ticket_ids = $ticket_ids;
    }

    public function registerEvents(): array {
        $total_row = count($this->ticket_ids);
        return [
            AfterSheet::class  => function (AfterSheet $event) use ($total_row) {
                $cellRange = 'A1:O1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);

                $waste = 3;

                for ($i = 2; $i <= $total_row + 2; $i++) {
                    if ($i % 2 == 0) {
                        $event->sheet->getStyle('A' . $i . ':Q' . $i)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF8ad5f5'); // blue
                    }
                    else {
                        $event->sheet->getStyle('A' . $i . ':Q' . $i)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFFFF'); // white
                    }
                }

                for ($i = 1; $i <= $total_row + $waste; $i++) {
                    $event->sheet->getStyle('A' . $i . ':Q' . $i)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ]
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                }
                $columns = ["H", "I", "J", "K", "L", "M", "N"]; // for effort times
                foreach ($columns as $column) {
                    $event->sheet->getComment($column . "1")->getText()->createTextRun('Discount is included.');
                }
                $event->sheet->getStyle('G' . ($total_row + $waste))->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFe6f58a');
                $event->sheet->getStyle('H' . ($total_row + $waste) . ":N" . ($total_row + $waste))->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF54f276');
            },
        ];
    }

    public function collection() {

        $row = [];
        $i = 1;

        $total_coding_m = 0;
        $total_consulting_m = 0;
        $total_testing_m = 0;
        $total_it_support_m = 0;
        $total_spent_m = 0;
        $total_design_minutes = 0;
        $total_analysis_m = 0;

        foreach ($this->ticket_ids as $ticket_id) {
            $ticket_id          = intval($ticket_id);
            $ticket             = Ticket::find($ticket_id);
            $org                = Organization::find($ticket->org_id);
            $ticket_personnel   = User::find($ticket->personnel);
            $ticket_category    = Category::find($ticket->category);
            $ticket_priority    = Priority::find($ticket->priority);
            $ticket_status      = Status::find($ticket->status_id);

            $times              = Helper::calculateDiscountedEffortsByType($ticket_id);
            $coding             = sprintf("%02d:%02d", $times[0][1][0], $times[0][1][1]);
            $consulting         = sprintf("%02d:%02d", $times[0][2][0], $times[0][2][1]);
            $testing            = sprintf("%02d:%02d", $times[0][3][0], $times[0][3][1]);
            $it_support         = sprintf("%02d:%02d", $times[0][6][0], $times[0][6][1]);
            $design             = sprintf("%02d:%02d", $times[0][7][0], $times[0][7][1]);
            $analysis           = sprintf("%02d:%02d", $times[0][8][0], $times[0][8][1]);
            $total_time         = sprintf("%02d:%02d", $times[1][0], $times[1][1]);

            $total_coding_m         += $times[0][1][1] + ($times[0][1][0] * 60);
            $total_consulting_m     += $times[0][2][1] + ($times[0][2][0] * 60);
            $total_testing_m        += $times[0][3][1] + ($times[0][3][0] * 60);
            $total_it_support_m     += $times[0][6][1] + ($times[0][6][0] * 60);
            $total_design_minutes   += $times[0][7][1] + ($times[0][7][0] * 60);
            $total_analysis_m       += $times[0][8][1] + ($times[0][8][0] * 60);
            $total_spent_m          += $times[1][1] + ($times[1][0] * 60);

            $name = $ticket_personnel->first_name ?? "";
            $surname = $ticket_personnel->surname ?? "";
            $body = array(
                $i,
                $ticket->id ?? "",
                $org->org_name ?? "",
                $name . " " . $surname,
                $ticket->name ?? "",
                $ticket_category->name ?? "",
                $ticket_priority->name ?? "",
                $coding ?? "",
                $consulting ?? "",
                $testing ?? "",
                $it_support ?? "",
                $design ?? "",
                $analysis ?? "",
                $total_time ?? "",
                $ticket_status->name ?? "",
                Carbon::parse($ticket->created_at)->format("d.m.Y"),
                $ticket->close_date ? Carbon::parse($ticket->close_date)->format("d.m.Y") : "",
            );

            $row[] = $body;
            $i++;
        }

        $total_coding_h         = intval($total_coding_m / 60);
        $total_coding_m         = $total_coding_m % 60;
        $total_consulting_h     = intval($total_consulting_m / 60);
        $total_consulting_m     = $total_consulting_m % 60;
        $total_testing_h        = intval($total_testing_m / 60);
        $total_testing_m        = $total_testing_m % 60;
        $total_it_support_h     = intval($total_it_support_m / 60);
        $total_it_support_m     = $total_it_support_m % 60;
        $total_design_hours     = intval($total_design_minutes / 60);
        $total_design_minutes   = $total_design_minutes % 60;
        $total_analysis_h       = intval($total_analysis_m / 60);
        $total_analysis_m       = $total_analysis_m % 60;
        $total_spent_h          = intval($total_spent_m / 60);
        $total_spent_m          = $total_spent_m % 60;

        $footer = array(
            "", "", "", "", "", "", "TOTAL TIMES",
            sprintf("%02d:%02d", $total_coding_h, $total_coding_m),
            sprintf("%02d:%02d", $total_consulting_h, $total_consulting_m),
            sprintf("%02d:%02d", $total_testing_h, $total_testing_m),
            sprintf("%02d:%02d", $total_it_support_h, $total_it_support_m),
            sprintf("%02d:%02d", $total_design_hours, $total_design_minutes),
            sprintf("%02d:%02d", $total_analysis_h, $total_analysis_m),
            sprintf("%02d:%02d", $total_spent_h, $total_spent_m),
        );
        $row[] = array();
        $row[] = $footer;
        return new Collection([$row]);
    }

    public function headings(): array {
        return [
            '#',
            'Ticket ID',
            'Ticket From',
            'Personnel',
            'Subject',
            'Category',
            'Priority',
            'Coding',
            'Consulting',
            'Testing',
            'IT-Support',
            'Design',
            'Analysis',
            'Total Time',
            'Status',
            'Ticket Date',
            'Done Date'
        ];
    }
}
