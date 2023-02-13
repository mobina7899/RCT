<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientHasCat extends Model
{
    use HasFactory;

    protected $table = 'patient_has_cats' ;

    protected $fillable = ['study_patient_id' , 'patient_cat_id'];


}
