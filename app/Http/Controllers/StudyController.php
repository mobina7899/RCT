<?php

namespace App\Http\Controllers;

use App\Exports\StudyExport;
use App\Http\Requests\dashboard\study\CreateStudyRequest;
use App\Http\Requests\dashboard\study\UpdateStudyRequest;
use App\Http\Requests\dashboard\TagRequest;
use App\Models\actor\Patient;
use App\Models\content\Sickness;
use App\Models\content\StudyTag;
use App\Models\EntryStudy;
use App\Models\FailureStudy;
use App\Models\Gender;
use App\Models\intervention\Intervention;
use App\Models\intervention\InterventionClass;
use App\Models\intervention\InterventionType;
use App\Models\Province;
use App\Models\Study;
use App\Models\StudySpecification;
use App\Models\Survey;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use http\Client;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;
use function GuzzleHttp\Promise\all;
use PDF;
use Excel;

class StudyController extends Controller
{
    public function index()
    {

        if (!File::exists('images/profile/' . auth()->user()->Profile->img))
            auth()->user()->profile()->update(['img' => null]);
        if (auth()->user()->hasPermissionTo('read-studies'))
            $studies = Study::paginate(10);
        else
            $studies = Study::where('researcher_id', auth()->user()->researcher->id)->paginate(10);

        $surveys = array();
        $patients = array();
        $surveyCount = 0;
        $patientCount = 0;
        foreach ($studies as $study) {
            foreach ($study->surveys as $survey) {
                $surveyCount++;
                $survey->create_date = Verta($survey->create_date)->format('Y/m/d');
                $survey->description = $survey->description == '' ? 'ندارد' : $survey->description;
                array_push($surveys, $survey);
            }
            foreach ($study->patients as $patient) {
                $patientCount++;
                $patient->birthdate = Verta($patient->birthdate)->format('Y/m/d');
                array_push($patients, $patient);
            }
            $study->start_date = $study->start_date != null ? Verta($study->start_date)->format('Y/m/d') : '';
            $study->end_date = $study->end_date != null ? Verta($study->end_date)->format('Y/m/d') : '';
        }
        return view('dashboard.content.study.manage-plan', ['studies' => $studies, 'surveys' => $surveys
            , 'surveyCount' => $surveyCount, 'patients' => $patients, 'patientCount' => $patientCount]);
    }

    public function sort($type)
    {

        if (auth()->user()->hasPermissionTo('read-studies')) {
            if ($type == 0 || $type == 1 || $type == 2)
                $studies = Study::where('status', $type)->paginate(10);
            else
                $studies = Study::orderBy($type)->paginate(10);
        } else {
            if ($type == 0 || $type == 1 || $type == 2)
                $studies = Study::where('researcher_id', auth()->user()->researcher->id)->where('status', $type)->paginate(10);
            else
                $studies = Study::where('researcher_id', auth()->user()->researcher->id)->orderBy($type)->paginate(10);
        }
        $surveys = array();
        $patients = array();
        $surveyCount = 0;
        $patientCount = 0;
        foreach ($studies as $study) {
            foreach ($study->surveys as $survey) {
                $surveyCount++;
                $survey->create_date = Verta($survey->create_date)->format('Y/m/d');
                $survey->description = $survey->description == '' ? 'ندارد' : $survey->description;
                array_push($surveys, $survey);
            }
            foreach ($study->patients as $patient) {
                $patientCount++;
                $patient->birthdate = Verta($patient->birthdate)->format('Y/m/d');
                array_push($patients, $patient);
            }
            $study->start_date = $study->start_date != null ? Verta($study->start_date)->format('Y/m/d') : '';
            $study->end_date = $study->end_date != null ? Verta($study->end_date)->format('Y/m/d') : '';
        }
        return view('dashboard.content.study.manage-plan', ['studies' => $studies, 'surveys' => $surveys
            , 'surveyCount' => $surveyCount, 'patients' => $patients, 'patientCount' => $patientCount,
            'type' => $type]);
    }

    public function update(UpdateStudyRequest $request)
    {
//        dd($request->all());
        $input = $request->all();

        if ($request->has('minimum_age'))
            $input['minimum_age'] = (!empty($request->min_year) ? $request->min_year : '0') . (!empty($request->min_month) ? '.' . $request->min_month : '.0') . (!empty($request->min_day) ? '.' . $request->min_day : '.0');

        if ($request->has('maximum_age'))
            $input['maximum_age'] = (!empty($request->max_year) ? $request->max_year : '0') . (!empty($request->max_month) ? '.' . $request->max_month : '.0') . (!empty($request->max_day) ? '.' . $request->max_day : '.0');

        if ($request->has('file') && $request->file != null) {
            $fileName = time() . '-' . $request->file('file')->getClientOriginalName();
            $request->file('file')->move('file/study', $fileName);
            $input['file'] = $fileName;
        } elseif ($request->has('file'))
            $input['file'] = Study::find($request->id)->file;

        isset($request->start_date) ? $input['start_date'] = $this->jalaliToGregorian($request->start_date) : '';
        isset($request->end_date) ? $input['end_date'] = $this->jalaliToGregorian($request->end_date) : '';
        isset($request->start_date_illness) ? $input['start_date_illness'] = $this->jalaliToGregorian($request->start_date_illness) : '';
        isset($request->end_date_illness) ? $input['end_date_illness'] = $this->jalaliToGregorian($request->end_date_illness) : '';
        isset($request->start_get_sick_ended) ? $input['start_get_sick_ended'] = $this->jalaliToGregorian($request->start_get_sick_ended) : '';
        isset($request->end_get_sick_ended) ? $input['end_get_sick_ended'] = $this->jalaliToGregorian($request->end_get_sick_ended) : '';
        isset($request->placebo) ? $input['placebo'] = 1 : '';
        isset($request->termination_illness) ? $input['termination_illness'] = 1 : '';
        isset($request->gender) ? $input['gender_id'] = $request->gender : '';

        $input['researcher_id'] = auth()->user()->hasRole('Researcher') ? auth()->user()->researcher->id : null;
        $input['assistant_id'] = auth()->user()->hasRole('Assistant') ? auth()->user()->assistant->id : null;

        if ($request->id != "" && Study::find($request->id)->step_number == 3) {
            $input['step_number'] = 3;
        } elseif (!isset($request->step_number)) {
            $input['step_number'] = 3;
            $input['status'] = 0;
        } else
            $input['status'] = 2;

        if ($request->id != "") {
            $study = Study::find($request->id);
            $study->update($input);
        } else
            $study = Study::create($input);

        if (isset($request->intervention)) {
            $study->interventions()->delete();
            $this->intervention($study, $request->intervention);
        }
        if (isset($request->tags)) {
            $tags = json_decode($request->tags);

            $tagsId = [];
            foreach ($tags as $tag) {
                if (StudyTag::where('title', $tag)->exists())
                    array_push($tagsId, StudyTag::where('title', $tag)->first()->id);
            }
            $study->tags()->sync($tagsId);
        }

        if (isset($request->study_specifications)) {
            $study_specifications = json_decode($request->study_specifications);

            foreach ($study_specifications as $specifaction) {
                $study->studyspecification()->delete();
                if (!StudySpecification::where("title", $specifaction)->where('study_id', $study->id)->count()) {
                    StudySpecification::create([
                        'title' => $specifaction,
                        'study_id' => $study->id,
                    ]);
                }
            }

            if (count($study_specifications) != $study->studyspecification->count()) {
                foreach ($study->studyspecification as $studyspefic) {
                    if (!in_array($studyspefic->title, $study_specifications)) {
                        $studyspefic->delete();
                    }
                }
            }
        }
        if (isset($request->entry_study)) {
            $entry_study = json_decode($request->entry_study);

            foreach ($entry_study as $entry) {
                if (!EntryStudy::where("title", $entry)->where('study_id', $study->id)->count()) {
                    EntryStudy::create([
                        'title' => $entry,
                        'study_id' => $study->id,
                    ]);
                }
            }

            if (count($entry_study) != $study->entrystudy->count()) {
                foreach ($study->entrystudy as $study_entry) {
                    if (!in_array($study_entry->title, $entry_study)) {
                        $study_entry->delete();
                    }
                }
            }
        }
        if (isset($request->failure_entry_study)) {
            $failure_entry_study = json_decode($request->failure_entry_study);

            foreach ($failure_entry_study as $fail_entry) {
                if (!FailureStudy::where("title", $fail_entry)->where('study_id', $study->id)->count()) {
                    FailureStudy::create([
                        'title' => $fail_entry,
                        'study_id' => $study->id,
                    ]);
                }
            }

            if (count($failure_entry_study) != $study->studyspecification->count()) {
                foreach ($study->failurestudy as $study_fail) {
                    if (!in_array($study_fail->title, $failure_entry_study)) {
                        $study_fail->delete();
                    }
                }
            }
        }
        if (!isset($request->step_number) && !$request->ajax()) {

            if ($study->sample == null) {
                return redirect()->route('study')->with('message', 'اطلاعات با موفقیت ثبت شد .برای تکمیل مراحل حجم نمونه را برای این طرح محاسبه کنید ')
                    ->with('id', $study->id);
            }else
                return redirect()->route('study')->with('message', 'اطلاعات با موفقیت ثبت شد ');

        } elseif (!isset($request->step_number) && $request->ajax())
            return response()->json($study->id, 200);
        return response()->json($study->id, 200);

    }

    public function report()
    {
        if (auth()->user()->hasPermissionTo('read-studies'))
            $studies = Study::paginate(10);
        else
            $studies = Study::where('researcher_id', auth()->user()->researcher->id)->paginate(10);

        foreach ($studies as $study) {
            $study->start_date = $study->start_date != null ? Verta($study->start_date)->format('Y/m/d') : '';
            $study->end_date = $study->end_date != null ? Verta($study->end_date)->format('Y/m/d') : '';
        }
        return view('dashboard.content.study.report', ['studies' => $studies]);
    }

    public function intervention(Study $study, $interventions)
    {
        $interventions = json_decode($interventions);
        foreach ($interventions as $intervention) {
            $temp['intervention_type_id'] = InterventionType::where('name', ($intervention->intervention_type == 'control' ? 'گروه کنترل' : 'گروه مداخله'))->first()->id;
            $temp['class_id'] = InterventionClass::where('name', ($intervention->intervention_Classifi))->first()->id;
            $temp['content'] = $intervention->intervention_desc;
            $study->interventions()->create($temp);
        }
    }

    public function studyIntervention($id)
    {
        $interventions = Intervention::where('study_id', $id)->get();

        foreach ($interventions as $intervention) {
            $intervention->intervention_type = ($intervention->type->name == 'گروه کنترل' ? 'control' : 'intervention');
            $intervention->intervention_Classifi = $intervention->class->name;
            $intervention->intervention_desc = $intervention->content;
        }
        return response()->json($interventions);
    }

    public function setPatients(Request $request)
    {
        if ($request->number > 0 && Patient::all()->count() >= $request->number) {
            $patients = Patient::all()->random($request->number);
            $study = Study::find($request->id);
            foreach ($patients as $patient)
                $study->patients()->attach($patient->id);

            return response()->json('success', 200);
        }
        return response()->json('faild', 400);
    }


    private function jalaliToGregorian($input)
    {
        list($year, $month, $day) = explode('/', $input);
        return Verta(implode('-', Verta::getGregorian($year, $month, $day)))->formatGregorian('Y-m-d');
    }

    public function edit($id)
    {
        $study = Study::find($id);

        $study->minimum_age = $study->minimum_age != null ? explode('.', $study->minimum_age) : $study->minimum_age;
        $study->maximum_age = $study->maximum_age != null ? explode('.', $study->maximum_age) : $study->maximum_age;

        $genders = Gender::all();

        $dates = Verta::now();
        $spefiction = StudySpecification::where('study_id', $study->id)->get();
        $entry = EntryStudy::where('study_id', $study->id)->get();
        $failur = FailureStudy::where('study_id', $study->id)->get();

        $dateend = $study->end_get_sick_ended != null ? Verta($study->end_get_sick_ended)->format('Y/m/d') : '';
        $datestart = $study->start_get_sick_ended != null ? Verta($study->start_get_sick_ended)->format('Y/m/d') : '';
        $datestartillness = $study->start_date_illness != null ? Verta($study->start_date_illness)->format('Y/m/d') : '';
        $dateendillness = $study->end_date_illness != null ? Verta($study->end_date_illness)->format('Y/m/d') : '';
        $startdate = $study->start_date != null ? Verta($study->start_date)->format('Y/m/d') : '';
        $enddate = $study->end_date != null ? Verta($study->end_date)->format('Y/m/d') : '';
        return view('dashboard.content.study.edit', ['study' => $study, 'dateend' => $dateend, 'datestart' => $datestart
            , 'datestartillness' => $datestartillness, 'dateendillness' => $dateendillness, 'startdate' => $startdate, 'enddate' => $enddate,
            'spefiction' => $spefiction, 'entry' => $entry, 'failur' => $failur, 'genders' => $genders, 'dates' => $dates


        ]);
    }


    public function destroy($id)
    {
        Study::find($id)->delete($id);
        return response()->json([]);
    }

    public function checkTag(TagRequest $request)
    {

        if (!StudyTag::where('title', $request->title)->exists()) {
            if (StudyTag::onlyTrashed()->where('title', $request->title)->exists()) {
                $tag = StudyTag::onlyTrashed()->where('title', $request->title)->first();
                $tag->restore();
            } else {
                $input = $request->all();
                $input['slug'] = str::slug($input['title']);
                $tag = StudyTag::create($input);
            }
        } else
            $tag = StudyTag::where('title', $request->title)->first();
        return response()->json($tag);
    }

    public function searchTag(Request $request)
    {
        $tags = StudyTag::where("title", "LIKE", "%{$request->search}%")
            ->get();
        return response()->json($tags);
    }

    public function pdf(Request $request)
    {
        $array = array();
        $request->has('studies_type') ? array_push($array, ['studies_type', '=', $request->studies_type]) : '';
        $request->has('study_design') ? array_push($array, ['study_design', '=', $request->study_design]) : '';
        $request->has('purpose_study') ? array_push($array, ['purpose_study', '=', $request->purpose_study]) : '';
        $request->has('randomization') ? array_push($array, ['randomization', '=', $request->randomization]) : '';
        $request->has('blinding') ? array_push($array, ['blinding', '=', $request->blinding]) : '';
        $request->has('placebo') ? array_push($array, ['placebo', '=', $request->placebo]) : '';

        $studies = Study::where($array)->get();

//        if (auth()->user()->hasPermissionTo('read-studies'))
//            $studies = Study::all();
//        else
//            $studies = auth()->user()->researcher->study;
        foreach ($studies as $study) {
            $study->maximum_age = $study->maximum_age != null ? explode('.', $study->maximum_age) : null;
            $study->minimum_age = $study->minimum_age != null ? explode('.', $study->minimum_age) : null;
            $study->start_date = $study->start_date != null ? Verta($study->start_date)->format('Y/m/d') : '';
            $study->end_date = $study->end_date != null ? Verta($study->end_date)->format('Y/m/d') : '';
        }
        $date = Verta::now()->format('Y/m/d');
        $pdf = PDF::loadView('dashboard/pdf/studies', compact('studies', 'date'));
        return $pdf->stream('طرح ها.pdf');
    }

    public function excel(Request $request)
    {
        $array = array();
        $request->has('studies_type') ? array_push($array, ['studies_type', '=', $request->studies_type]) : '';
        $request->has('study_design') ? array_push($array, ['study_design', '=', $request->study_design]) : '';
        $request->has('purpose_study') ? array_push($array, ['purpose_study', '=', $request->purpose_study]) : '';
        $request->has('randomization') ? array_push($array, ['randomization', '=', $request->randomization]) : '';
        $request->has('blinding') ? array_push($array, ['blinding', '=', $request->blinding]) : '';
        $request->has('placebo') ? array_push($array, ['placebo', '=', $request->placebo]) : '';

        $studies = Study::where($array)->get();

        foreach ($studies as $study) {
            $study->maximum_age = $study->maximum_age != null ? explode('.', $study->maximum_age) : null;
            $study->minimum_age = $study->minimum_age != null ? explode('.', $study->minimum_age) : null;
            $study->start_date = $study->start_date != null ? Verta($study->start_date)->format('Y/m/d') : '';
            $study->end_date = $study->end_date != null ? Verta($study->end_date)->format('Y/m/d') : '';
        }

        return Excel::download(new StudyExport($studies), 'طرح ها.xlsx');
    }

    public function word($id)
    {
        $headers = array(

            "Content-type" => "text/html",

            "Content-Disposition" => "attachment;Filename=گزارش طرح.doc"

        );

        $study = Study::find($id);

        $study->maximum_age = $study->maximum_age != null ? explode('.', $study->maximum_age) : null;
        $study->start_date = Verta($study->start_date)->format('Y/m/d');
        $study->end_date = Verta($study->end_date)->format('Y/m/d');
        $study->start_date_illness = Verta($study->start_date_illness)->format('Y/m/d');
        $study->end_date_illness = Verta($study->end_date_illness)->format('Y/m/d');
        $date = Verta::now()->format('Y/m/d');

        return \Response::make(\Illuminate\Support\Facades\View::make('dashboard/word/study', compact('study', 'date')), 200, $headers);

    }

    public function singlePdf($id)
    {
        $study = Study::find($id);

        $study->maximum_age = $study->maximum_age != null ? explode('.', $study->maximum_age) : null;
        $study->minimum_age = $study->minimum_age != null ? explode('.', $study->minimum_age) : null;
        $study->start_date = Verta($study->start_date)->format('Y/m/d');
        $study->end_date = Verta($study->end_date)->format('Y/m/d');
        $study->start_date_illness = Verta($study->start_date_illness)->format('Y/m/d');
        $study->end_date_illness = Verta($study->end_date_illness)->format('Y/m/d');
        $date = Verta::now()->format('Y/m/d');
        $pdf = PDF::loadView('dashboard/pdf/study', compact('study', 'date'));
        return $pdf->stream('طرح.pdf');
    }


    public function update_status(Request $request)
    {
        if ($request->ajax()) {
            $study = Study::find($request->id);
            $study->status = $request['column_value'] == 'true' ? 1 : 0;
            $study->save();
        }
    }

    public function showstudy($id)
    {
        $study = Study::find($id);
        $gender = Gender::all();
        $spefiction = StudySpecification::where('study_id', $study->id)->get();
        $entry = EntryStudy::where('study_id', $study->id)->get();
        $failur = FailureStudy::where('study_id', $study->id)->get();
        $study->maximum_age = $study->maximum_age != null ? explode('.', $study->maximum_age) : null;
        $study->start_date_illness = Verta($study->start_date_illness)->format('Y/m/d');
        $study->end_date_illness = Verta($study->end_date_illness)->format('Y/m/d');
        $study->start_date = Verta($study->start_date)->format('Y/m/d');
        $study->end_date = Verta($study->end_date)->format('Y/m/d');
        return view('dashboard.content.study.detail-plan', [
            'study' => $study,
            'entry' => $entry, 'gender' => $gender, 'spefiction' => $spefiction, 'failur' => $failur
        ]);
    }

    public function create()
    {
        $startdate = Verta::now();
        $genders = Gender::all();
        return view('dashboard.content.study.create', ['startdate' => $startdate, 'genders' => $genders]);
    }

    public function search(Request $request)
    {
        $search = $request->search;

        if (auth()->user()->hasPermissionTo('read-study')) {
            $studies = Study::where('researcher_id', auth()->user()->researcher->id)->where(function ($query) use ($search) {
                $query->where("title", "LIKE", "%{$search}%")->orWhere("studies_type", "LIKE", "%{$search}%");
            })
                ->get();
        } else {
            $studies = Study::where("title", "LIKE", "%{$search}%")->orWhere("studies_type", "LIKE", "%{$search}%")
                ->get();
        }
        foreach ($studies as $study) {
            $study->number = count($study->patients);
            $study->start_date = $study->start_date != null ? Verta($study->start_date)->format('Y/m/d') : '';
            $study->end_date = $study->end_date != null ? Verta($study->end_date)->format('Y/m/d') : '';
            $study->rand = auth()->user()->hasPermissionTo('edit-study') && !$study->status==2 && $study->sample != null ? true : false ;
            $study->sample = auth()->user()->hasPermissionTo('edit-study') && !$study->status==2 ? true : false ;
            $study->edit = auth()->user()->hasPermissionTo('edit-study')&& !$study->status==2  ? true : false;
            $study->file = auth()->user()->hasPermissionTo('create-study')&& !$study->status==2 ? true : false;

            $study->detail = !$study->status==2 ? true : false ;
        }
        $data['token'] = auth()->user()->token ;

        $data['studies'] = $studies;
        $data['delete'] = auth()->user()->hasAnyPermission(['delete-study', 'delete-studies']) ? true : false;
        $data['status'] = auth()->user()->hasPermissionTo('edit-study-status') ? true : false;
        return response()->json($data);
    }

    public function advancedSearch(Request $request)
    {
        $array = array();
        $request->has('studies_type') ? array_push($array, ['studies_type', '=', $request->studies_type]) : '';
        $request->has('study_design') ? array_push($array, ['study_design', '=', $request->study_design]) : '';
        $request->has('purpose_study') ? array_push($array, ['purpose_study', '=', $request->purpose_study]) : '';
        $request->has('randomization') ? array_push($array, ['randomization', '=', $request->randomization]) : '';
        $request->has('blinding') ? array_push($array, ['blinding', '=', $request->blinding]) : '';
        $request->has('placebo') ? array_push($array, ['placebo', '=', $request->placebo]) : '';

        $studies = Study::where($array)->get();

        foreach ($studies as $study) {
            $study->start_date = $study->start_date != null ? Verta($study->start_date)->format('Y/m/d') : '';
            $study->end_date = $study->end_date != null ? Verta($study->end_date)->format('Y/m/d') : '';
        }
        return response()->json($studies);
    }

    public function getStudy_design($id)
    {

        $study = Study::find($id);
        return $study->study_design == 'موازی' ? 101 : ($study->study_design == 'متقاطع' ? 102 :
            ($study->study_design == 'فاکتوریل' ? 103 : 104));
    }

    public function getSicknesses(){
        return response()->json(Sickness::pluck('name'));
    }

    public function getProvinces(){
        return response()->json(Province::all());
    }

    public function  saveSample($id , $sample){
        $study = Study::find($id) ;
        $study->sample = $sample ;
        $study->save();

        return response()->json(true);
    }

}
