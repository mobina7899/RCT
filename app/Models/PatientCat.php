<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientCat extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function studyPatients(){
        $this->belongsToMany(StudyPatient::class , 'patient_has_cats') ;
    }
}
