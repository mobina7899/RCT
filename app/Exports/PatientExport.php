<?php

namespace App\Exports;

use App\Models\actor\Patient;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;

class PatientExport implements FromView, WithEvents
{
    use Exportable;

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
        $patients = Patient::all();
        foreach ($patients as $patient)
            $patient->birthdate = Verta($patient->birthdate)->format('Y/m/d');
        return view('dashboard/excel/patient', [
            'patients' => $patients
        ]);
    }
}
