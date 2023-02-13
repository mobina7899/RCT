<?php

namespace App\Http\Controllers\actor;

use App\Exports\PatientExport;
use App\Exports\RandExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\actor\patient\CreatePatientRequest;
use App\Http\Requests\dashboard\actor\patient\UpdatePatientRequest;
use App\Imports\SicknessImport;
use App\Models\actor\Patient;
use App\Models\content\Sickness;
use App\Models\PatientCat;
use App\Models\PatientHasCat;
use App\Models\Study;
use App\Models\StudyPatient;
use App\Models\User;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use mysql_xdevapi\Collection;
use Spatie\Permission\Models\Role;
use PDF;
use Excel;


class PatientController extends Controller
{

    public function index()
    {
        if (auth()->user()->hasRole('Agent'))
            $patients = Patient::where('agent_id', auth()->id())->paginate(10);
        elseif (auth()->user()->hasRole('Researcher') || auth()->user()->hasRole('Assistant'))
            $patients = Patient::where('researcher_id', auth()->user()->researcher->id)->paginate(10);
        else
            $patients = Patient::paginate(10);
        foreach ($patients as $patient)
            $patient->birthdate = Verta($patient->birthdate)->format('Y/m/d');

        return view('dashboard.actor.patient.index', ['patients' => $patients]);

    }

    public function create()
    {
        $now = Verta::now();
        return view('dashboard.actor.patient.create', ['now' => $now]);
    }

    public function store(CreatePatientRequest $request)
    {
        $user_input = $request->only(['username', 'email', 'password']);
        $user_input['password'] = Hash::make($request->password);
        $user_input['enabled'] = 1;
        $user = User::create($user_input);

        $patient_input = $request->except(['username', 'email', 'password']);
        $patient_input['sickness_id'] = Sickness::where('name', $request->sickness_id)->first()->id;
        auth()->user()->hasRole('Agent') ? $patient_input['agent_id'] = auth()->id() : $patient_input['researcher_id'] = auth()->user()->researcher->id;
        $patient_input['birthdate'] = $this->jalaliToGregorian($request->birthdate);
        if (Patient::onlyTrashed()->where('n_number', $request->n_number)->exists()) {
            $patient_input['user_id'] = $user->id;
            $patient = Patient::onlyTrashed()->where('n_number', $request->n_number)->get()[0];
            $patient->restore();
            $patient->update($patient_input);
        } else
            $user->patient()->create($patient_input);

        $user->syncRoles('Patient');
        alert()->success('بیمار با موفقیت ثبت شد');
        return redirect()->route('patient');
    }

    private function jalaliToGregorian($input)
    {
        list($year, $month, $day) = explode('/', $input);
        return Verta(implode('-', Verta::getGregorian($year, $month, $day)))->formatGregorian('Y-m-d');
    }

    public function edit($id)
    {
        $now = Verta::now();
        $patient = Patient::find($id);
        $patient->birthdate = Verta($patient->birthdate)->format('Y/m/d');
        return view('dashboard.actor.patient.edit', ['patient' => $patient, 'now' => $now]);
    }

    public function update(UpdatePatientRequest $request, $id)
    {

        $patient = Patient::find($id);
        $user = $patient->user;
        $patient_input = $request->except(['username', 'email']);
        $patient_input['sickness_id'] = Sickness::where('name', $request->sickness_id)->first()->id;

        $patient_input['birthdate'] = $this->jalaliToGregorian($request->birthdate);

        $patient->update($patient_input);
        $user->update($request->only(['username', 'email']));

        alert()->success('بیمار ویرایش شد');

        return redirect()->route('patient');
    }

    public function destroy($id)
    {
        $patient = Patient::find($id);
        $patient->user->syncRoles('User');
        $patient->delete();
        return response()->json([]);
    }

    public function pdf()
    {
        $patients = Patient::all();
        foreach ($patients as $patient)
            $patient->birthdate = Verta($patient->birthdate)->format('Y/m/d');

        $date = Verta::now()->format('Y/m/d');
        $pdf = PDF::loadView('dashboard/pdf/patient', compact('patients', 'date'));
        return $pdf->stream('بیماران.pdf');
    }

    public function excel()
    {
        return Excel::download(new PatientExport(), 'بیماران.xlsx');
//        return redirect()->route('patient');
    }

    public function sicknessSearch(Request $request)
    {
        $search = $request->search;
        $sicknesses = Sickness::where("name", "LIKE", "%{$search}%")->get();
        return response()->json($sicknesses);

    }

    public function search(Request $request)
    {
        $search = $request->search;
        if (auth()->user()->hasRole('Agent')) {
            $patients = Patient::where('agent_id', auth()->id())->where(function ($query) use ($search) {
                $query->where("name", "LIKE", "%{$search}%")->orWhere("f_name", "LIKE", "%{$search}%")
                    ->orWhere("n_number", "LIKE", "%{$search}%");
            })
                ->get();
        } elseif (auth()->user()->hasRole('Researcher') || auth()->user()->hasRole('Assistant')) {
            $patients = Patient::where('researcher_id', auth()->user()->researcher->id)->where(function ($query) use ($search) {
                $query->where("name", "LIKE", "%{$search}%")->orWhere("f_name", "LIKE", "%{$search}%")
                    ->orWhere("n_number", "LIKE", "%{$search}%");
            })
                ->get();
        } else {
            $patients = Patient::where("name", "LIKE", "%{$search}%")->orWhere("f_name", "LIKE", "%{$search}%")
                ->orWhere("n_number", "LIKE", "%{$search}%")->get();
        }

        foreach ($patients as $patient) {
            $patient->birthdate = Verta($patient->birthdate)->format('Y/m/d');
            $patient->gender_id = $patient->gender->title;
            $patient->marriage_id = $patient->marriage ? 'متاُهل' : 'مجرد';
            $patient->province_id = $patient->province->name;
            $patient->sickness_id = $patient->sickness->name;

        }

        $data['patients'] = $patients;
        $data['delete'] = auth()->user()->hasPermissionTo('delete-patient') ? true : false;
        $data['edit'] = auth()->user()->hasPermissionTo('edit-patient') ? true : false;
        return response()->json($data);
    }

    public function randomisation($id)
    {
        return view('dashboard.actor.patient.randomisation', ['id' => $id]);
    }

    public function pc_array_power_set($array)
    {
        // initialize by adding the empty set
        $results = array(array());

        foreach ($array as $element) {
            foreach ($results as $combination) {
                array_push($results, array_merge(array($element), $combination));
            }
        }
        return $results;
    }

    public function combinations($arrays, $i = 0)
    {
        if (!isset($arrays[$i])) {
            return array();
        }
        if ($i == count($arrays) - 1) {
            return $arrays[$i];
        }

        // get combinations from subsequent arrays
        $tmp = $this->combinations($arrays, $i + 1);

        $result = array();

        // concat each array from tmp with each element from $arrays[$i]
        foreach ($arrays[$i] as $v) {
            foreach ($tmp as $t) {
                $result[] = is_array($t) ?
                    array_merge(array($v), $t) :
                    array($v, $t);
            }
        }

        return $result;
    }

    public function ranStore(Request $request, $studyIid)
    {
//        dd($request->all());
        $study = Study::find($studyIid);
        if ((int)$study->sample > Patient::count()) {
            alert()->warning('حجم نمونه نمیتواند بیشتر از تعداد کل بیماران باشد !');
            return back()->withInput($request->all());
        }


        $blocks = explode(',', $request->blocks);
        $groups = explode(',', $request->groups);
        $cats = json_decode($request->cats);

        $counter = 0;
        $result = array();
        $block_counter = 1;


        while (count($result) < (int)$study->sample) {
            foreach ($blocks as $block) {
                for ($i = 0; $i < $block; $i++) {
                    if (count($result) == (int)$study->sample)
                        break;
                    array_push($result, ['block' => $block_counter]);
                }
                $block_counter++;
            }

        }
        for ($temp = 0; $temp < count($groups); $temp++) {
            for ($i = 0; $i < intval((int)$study->sample / count($groups)); $i++) {
                $result[$counter]['group'] = $groups[$temp];
                $counter++;
            }
        }

        while ($counter != (int)$study->sample) {
            $result[$counter]['group'] = $groups[0];
            $counter++;
        }

//        $input = $request->except('_token', 'seed', 'groups', 'blocks', 'number', 'cats');

        $conditions = array();
        $request->has('age') ? array_push($conditions, json_decode($request->age)) : array_push($conditions, [null]);
        $request->has('height') ? array_push($conditions, json_decode($request->height)) : array_push($conditions, [null]);
        $request->has('weight') ? array_push($conditions, json_decode($request->weight)) : array_push($conditions, [null]);
        $request->has('marid') ? array_push($conditions, $request->marid) : array_push($conditions, [null]);
        $request->has('gender') ? array_push($conditions, $request->gender) : array_push($conditions, [null]);
        $request->has('city') ? array_push($conditions, $request->city) : array_push($conditions, [null]);
        if ($request->has('sick')) {
            $sicks = array();
            foreach (json_decode($request->sick) as $sick) {

                if (!Sickness::where('name', $sick)->exists())
                    return back()->withInput($request->all())->with('message', 'نوع عارضه باید برابر یکی از آیتم های لیست باشد !');

                array_push($sicks, Sickness::where('name', $sick)->first()->id);
            }
            array_push($conditions, $sicks);
        } else
            array_push($conditions, [null]);

        $conditions = $this->combinations($conditions);

        $patientIds = array();
        foreach ($conditions as $item) {
            $query = Patient::select('*');
            for ($i = 0; $i < count($item); $i++) {
                switch ($i) {
                    case 0 :
                        if ($item[$i] != null) {
                            if (Str::contains($item[$i], '-')) {
                                $ages = explode('-', $item[$i]);
                                $min = (now()->format('Y') - $ages[1]) . '/01/01';
                                $max = (now()->format('Y') - $ages[0]) . '/12/30';
                                $query->whereBetween('birthdate', [$min, $max]);
                            } else {
                                $age = (now()->format('Y') - $item[$i]);
                                $query->where('birthdate', 'like', $age . '%');
                            }
                        }
                        break;
                    case 1 :
                        if ($item[$i] != null) {
                            if (Str::contains($item[$i], '-')) {
                                $heights = explode('-', $item[$i]);
                                $query->whereBetween('height', [$heights[0], $heights[1]]);
                            } else {
                                $query->where('height', 'like', $item[$i] . '%');
                            }
                        }
                        break;
                    case 2 :
                        if ($item[$i] != null) {
                            if (Str::contains($item[$i], '-')) {
                                $weights = explode('-', $item[$i]);
                                $query->whereBetween('weight', [$weights[0], $weights[1]]);
                            } else {
                                $query->where('weight', 'like', $item[$i] . '%');
                            }
                        }
                        break;
                    case 3 :
                        if ($item[$i] != null) {
                            $query->where('marriage', $item[$i]);

                        }
                        break;
                    case 4 :
                        if ($item[$i] != null) {
                            $query->where('gender_id', $item[$i]);
                        }
                        break;
                    case 5 :
                        if ($item[$i] != null) {

                            $query->where('province_id', $item[$i]);
                        }
                        break;
                    case 6 :
                        if ($item[$i] != null) {
                            $query->where('sickness_id', $item[$i]);
                        }
                        break;
                }

            }
            foreach ($query->inRandomOrder()->pluck('id')->toArray() as $id)
                array_push($patientIds, $id);
        }
        if ((int)$study->sample > count($patientIds)) {
            alert()->warning('تعداد بیماران با این شرایط کمتر از مقدار حجم نمونه است !');
            return back()->withInput($request->all());
        }
        $study->patients()->detach();

        $counter = 0;
        foreach ($result as $item) {
            $temp = StudyPatient::create(['study_id' => $studyIid, 'patient_id' => $patientIds[$counter],
                'block' => $item['block'], 'group'
                => $item['group']]);
            $counter++;

        }
        alert()->success('رندومایزیشن انجام شد');
        return redirect()->route('study');

    }

    public function rands()
    {
        if (auth()->user()->hasPermissionTo('read-studies'))
            $studies = Study::whereHas('patients')->paginate(10);
        else {
            $studies = Study::where('researcher_id', auth()->user()->researcher->id)->whereHas('patients')->paginate(10);

        }


        foreach ($studies as $study) {

            $groups = StudyPatient::where('study_id', $study->id)->get()->groupby('group');
            $studyGroups = array();
            foreach ($groups as $key => $value)
                array_push($studyGroups, $key);
            $study->groups = implode(',', $studyGroups);
        }

        return view('dashboard.actor.patient.rands', ['studies' => $studies]);

    }

    public function randExcel($id)
    {

        return Excel::download(new RandExport($id), 'بیماران.xlsx');
    }


    public function destroyRand($id)
    {

        $study = Study::find($id);
        $study->patients()->detach();

        return response()->json(true);
    }
}
