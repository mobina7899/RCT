<?php

namespace App\Exports;
use App\Models\Study;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;

class RandExport implements FromView, WithEvents
{
    use Exportable;
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setRightToLeft(true);
            },
        ];
    }

    public function view(): View
    {

        $patients = Study::find($this->id)->studyPatient;

        return view('dashboard/excel/rand', [
            'patients' => $patients
        ]);
    }
}
