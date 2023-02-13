<?php

namespace App\Models;

use App\Models\actor\Patient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyPatient extends Model
{
    use HasFactory;

    protected $table = 'study_patient' ;

    protected $fillable = ['study_id' , 'patient_id' , 'block' , 'group'];

    public function patientCats(){
        $this->belongsToMany(PatientCat::class , 'patient_has_cats') ;
    }

    public function study(){

        return $this->belongsTo(Study::class)->withDefault();
    }

    public function patient(){

        return $this->belongsTo(Patient::class)->withDefault();
    }
}
