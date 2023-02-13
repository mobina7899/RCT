@extends('dashboard.layout.master')

@section('pagetitle')
    @parent
    مدیریت طرح ها
@endsection

@section('head')
    <link href="{{ asset('/css/dashboard/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard/buttons-datatable.css') }}"/>
    <link href="{{ asset('/css/dashboard/questionnaire.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('/css/dashboard/custom.css') }}"/>
    {{--    <link rel="stylesheet" href="{{ asset('/css/dashboard/bootstrap-rtl.min.css') }}"/>--}}
    {{--    <link rel="stylesheet" href="{{ asset('/css/dashboard/bootstrap.min.css') }}"/>--}}
@endsection

@section('content')

    <section class="content">
        <div class="content-body">

            <div class="container-fluid">

                <div class="row questionnaire-box justify-content-center mx-0 align-items-center card">
                    <!-- ========= summary ========== -->
                    {{--                    @if(Session::get('massage'))--}}
                    {{--                        <div>{{Session::has('message')}}</div>--}}
                    {{--                    @endif--}}
                    <div class="col-12">
                        <div class="summary-header">
                            <h2 class="text-center border-bottom py-2 mb-4">مدیریت طرح</h2>
                        </div>
                        <div class="summary">
                            <div class="row mx-0">
                                <div class="col-12 col-md-6 col-lg-4 my-4">
                                    <div class="summary-item text-center">
                                        <h2 class="pt-5">طرح</h2>
                                        <h1 class="py-3">{{auth()->user()->hasPermissionTo('read-studies') ? \App\Models\Study::all()->count() : \App\Models\Study::where('researcher_id' , auth()->user()->researcher->id)->count()}}</h1>
                                        <div class="summary-title">
                                            <i class="fa-solid fa-box-archive align-middle"></i>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4 my-4">
                                    <div class="summary-item text-center">
                                        <h2 class="pt-5">پرسشنامه</h2>
                                        <h1 class="py-3">{{$surveyCount}}</h1>
                                        <div class="summary-title">
                                            <i class="fa-solid fa-file-lines align-middle"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4 my-4">
                                    <div class="summary-item text-center">
                                        <h2 class="pt-5">بیمار</h2>
                                        <h1 class="py-3">{{$patientCount}}</h1>
                                        <div class="summary-title">
                                            <i class="fa-solid fa-users align-middle"></i>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 datatable-status">
                        <div class="project-list-header">
                            <h2 class="text-center border-bottom py-2 pt-4 mb-3">
                                لیست طرح ها
                            </h2>
                        </div>

                        <div>
                            <div
                                class="row justify-content-between px-4 align-items-center"
                            >
                                <div class="w-auto d-flex flex-nowrap align-items-center">
                    <span class="mr-2">مرتب سازی‌:</span
                    ><select id="study_sort" class="form-select">
                                        <option disabled selected> انتخاب کنید</option>
                                        <option {{isset($type) && $type=='title' ? 'selected' : ''}} value="title">
                                            عنوان
                                        </option>
                                        <option
                                            {{isset($type) && $type=='studies_type' ? 'selected' : ''}}  value="studies_type">
                                            نوع
                                        </option>
                                        <option {{isset($type) && $type=='2' ? 'selected' : ''}} value="2"> تکمیل نشده
                                        </option>
                                        <option {{isset($type) && $type=='0' ? 'selected' : ''}} value="0"> تایید نشده
                                        </option>
                                        <option {{isset($type) && $type=='1' ? 'selected' : ''}} value="1"> تایید شده
                                        </option>
                                    </select>
                                </div>
                                @can('create-study')
                                    <a href="{{route('study.create')}}" class="add-user">
                                        <i class="fa-solid fa-plus align-middle"></i>
                                        ایجاد طرح جدید
                                    </a>
                                @endcan
                            </div>

                            <div>
                                <label class="label-in-search"> جستجو : </label>
                                <input type="search" class="in-search">
                            </div>
                            <div class="table-responsive">
                                <table id="example3" class="display" style="min-width: 845px">
                                    <thead>
                                    <tr>
                                        <th>ردیف</th>
                                        <th>عنوان طرح</th>
                                        <th>نوع طرح</th>
                                        <th>تاریخ شروع کار آزمایی</th>
                                        <th>تاریخ پایان کارآزمایی</th>

                                        <th>تعداد بیماران</th>
                                        <th>وضعیت</th>
                                        <th>عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody class="search-table">
                                    @php($counter = 1 )
                                    @foreach ( $studies as $study )
                                        <tr id="study-{{$study->id}}">
                                            <td>{{$counter++}}</td>
                                            <td>{{$study->title}}</td>
                                            <td>{{$study->studies_type}}</td>
                                            <td>{{$study->start_date}}</td>
                                            <td>{{$study->end_date}}</td>
                                            {{--                                            <td class="answer-number"--}}
                                            {{--                                                data-id="{{$study->id}}">{{count($study->patients) == 0 ? 'تعیین نشده' : count($study->patients)}}</td>--}}
                                            {{--                                            <td>--}}
                                            <td data-id="{{$study->id}}">{{count($study->patients) == 0 ? 'تعیین نشده' : count($study->patients)}}</td>
                                            <td>
                                                @can('edit-study-status')
                                                    @if($study->status==2)
                                                        تکمیل نشده
                                                    @else
                                                        <div
                                                            class="activity-btn {{ $study->status ? 'switch-to-active' : '' }}">
                                                            <span class="activity-switch"></span>
                                                            <input data-id="{{ $study->id }}" type="checkbox"
                                                                   class="d-none"
                                                                {{ $study->status ? 'checked' : '' }} />
                                                        </div>
                                                    @endif
                                                @else
                                                    @if($study->status==2)
                                                        <a class="link-color"
                                                           href="{{route('study.edit' ,['id'=>$study->id])}}">تکمیل
                                                            نشده</a>
                                                    @else
                                                        {{$study->status==1 ? 'تایید شده' :  'تایید نشده'}}
                                                    @endif
                                                @endcan
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    @if(!$study->status==2)
                                                        @can('create-study')

                                                            <a href="{{'http://survey.clitri.ir/'.auth()->user()->token.'/'.$study->id}}"
                                                               class="btn btn-primary text-white shadow btn-xs sharp"><i
                                                                    class="fa-solid fa-solid fa-file"></i></a>

                                                        @endcan
                                                        <a href="{{route('study.show' , ['id'=>$study->id])}}"
                                                           class="view-plan-btn shadow btn-xs sharp">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>

                                                        @if(auth()->user()->hasAnyPermission(['edit-study']) && $study->status != 2)
                                                            <a href="{{route('study.edit' ,['id'=>$study->id])}}"
                                                               class="btn btn-primary text-white shadow btn-xs sharp"><i
                                                                    class="fa-solid fa-pencil"></i></a>
                                                        @endif
                                                    @endif
                                                    @if(auth()->user()->hasAnyPermission(['delete-study','delete-studies']))
                                                        <a onclick="deleterecord({{ $study->id }})"
                                                           class="btn btn-danger text-white shadow btn-xs sharp"><i
                                                                class="fa-solid fa-trash"></i></a>
                                                    @endif
                                                    @if(!$study->status==2)
                                                        @if(auth()->user()->hasAnyPermission(['edit-study']) )
                                                            @if( $study->sample != null)
                                                                <a href="{{route('patient.randomisation' , ['id'=>$study->id])}}"
                                                                   class="analysis-plan-btn shadow btn-xs sharp"><i
                                                                        class="fa-solid fa-compass-drafting"></i></a>
                                                            @endif
                                                            <a data-id="{{$study->id}}"
                                                               href="#"
                                                               class="calc-plan-btn shadow btn-xs sharp"
                                                               data-bs-toggle="modal"
                                                               data-bs-target="#sample_size_modal"
                                                            >
                                                                <i class="fa-solid fa-calculator"></i>
                                                            </a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {{ $studies->links('pagination.default') }}
                            </div>
                        </div>
                    </div>

                    <div class="col-12 datatable-status">
                        <div class="project-list-header">
                            <h2 class="text-center border-bottom py-2 pt-4 mb-3">
                                لیست پرسشنامه ها
                            </h2>
                        </div>

                        <div>
                            {{--                            <a href="" class="add-user">--}}
                            {{--                                <i class="fa-solid fa-plus align-middle"></i>--}}
                            {{--                                ایجاد پرسشنامه جدید--}}
                            {{--                            </a>--}}
                            <div class="table-responsive">
                                <table id="example3" class="display" style="min-width: 845px">
                                    <thead>
                                    <tr>
                                        <th>شماره</th>
                                        <th>عنوان طرح</th>
                                        <th>عنوان پرسش نامه</th>
                                        <th>توضیحات</th>
                                        <th>لینک پاسخ دهی</th>
                                        <th>تعداد پاسخ ها</th>
                                        <th>تاریخ ایجاد</th>
                                        <th>عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($counter=1)
                                    @foreach($surveys as $survey)
                                        <tr>
                                            <td>{{$counter++}}</td>
                                            <td>{{$survey->study->title}}</td>
                                            <td>{{$survey->title}}</td>
                                            <td>{{$survey->description}}</td>
                                            <td>{{$survey->link}}</td>
                                            <td>{{$survey->answer_number}}</td>
                                            <td>{{$survey->create_date}}</td>
                                            {{--                                        <td><a href="{{'http://frontsurvey.clitri.ir/'.auth()->user()->token.'/'.$survey->study_id.'/action'}}"><i class="fa-solid fa-arrow-right"></i></a></td>--}}
                                            <td>
                                                <a href="{{'http://survey.clitri.ir/'.auth()->user()->token.'/'.$survey->study_id.'/action'}}">
                                                    <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                                        <path fill="#05a576"
                                                              d="M12.5,8C9.85,8 7.45,9 5.6,10.6L2,7V16H11L7.38,12.38C8.77,11.22 10.54,10.5 12.5,10.5C16.04,10.5 19.05,12.81 20.1,16L22.47,15.22C21.08,11.03 17.15,8 12.5,8Z"/>
                                                    </svg>
                                                </a></td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                    <div class="col-12 datatable-status">
                        <div class="project-list-header">
                            <h2 class="text-center border-bottom py-2 pt-4 mb-3">
                                لیست بیماران
                            </h2>
                        </div>

                        <div>
                            {{--                            <a href="#" class="add-user">--}}
                            {{--                                <i class="fa-solid fa-plus align-middle"></i>--}}
                            {{--                                ایجاد پرسشگر جدید--}}
                            {{--                            </a>--}}
                            <div class="table-responsive">
                                <table id="example3" class="display" style="min-width: 845px">
                                    <thead>
                                    <tr>
                                        <th>ردیف</th>
                                        <th>نام‌ونام‌خانوادگی</th>
                                        <th>کدملی</th>
                                        <th>تاریخ‌تولد</th>
                                        <th>جنسیت</th>
                                        <th>وضعیت‌تاُهل</th>
                                        <th>قد</th>
                                        <th>وزن</th>
                                        <th>شهر</th>
                                        <th>نوع‌عارضه</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($counter = 1)
                                    @foreach ($patients as $patient)
                                        <tr id="patient-{{ $patient->id }}">

                                            <td>{{ $counter++ }}</td>
                                            <td>{{ $patient->name.' '.$patient->f_name }}</td>
                                            <td>{{ $patient->n_number }}</td>
                                            <td>{{ $patient->birthdate }}</td>
                                            <td>{{ $patient->gender->title }}</td>
                                            <td>{{ $patient->marriage ? 'متاُهل' : 'مجرد' }}</td>
                                            <td>{{ $patient->height }}</td>
                                            <td>{{ $patient->weight }}</td>
                                            <td>{{ $patient->province->name }}</td>
                                            <td>{{ $patient->sickness->name }}</td>

                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="answerNumber" aria-hidden="true" aria-labelledby="exampleModellable">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="answerForm" method="post">
                    @csrf
                    <input type="hidden" id="id" name="id">

                    <div class="modal-header">
                        <h4 class="modal-title"> بیماران</h4>
                        <button type="button" class="close" data-dismiss="modal"
                                data-target='#imgEditModal'><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="col-12 px-2">
                                <label><strong>تعداد بیماران :</strong></label>
                                <input type="number" name="number" id="number" class="form-control"
                                />
                                <div class="errors text-danger"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal"
                                data-target='#imgEditModal'>بستن
                        </button>
                        <button type="submit" class="btn btn-primary">ارسال</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="sample_size_modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">محاسبه حجم</h4>
                    <button
                        type="button"
                        class="close close-btn"
                        data-bs-dismiss="modal"
                    >
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div style="direction: rtl">
                        <div class="copied">کپی شد !!!</div>

                        <h1 id="myInput" class="mb-3">محاسبه نمونه</h1>

                        <div class="box">
                            <ul id="CalList">
                                <span id="list_first"></span>

                            </ul>
                        </div>

                        <label>نام پیامد</label>
                        <div class="d-flex  mb-3">
                            <input class="form-control" id="first_text">

                        </div>


                        <label>نوع طرح مطالعه</label>
                        <select name="scale" id="scale" class="form-control mb-3">
                            <option value="">انتخاب نوع مطالعه</option>
                        </select>


                        <label>نوع مقیاس</label>
                        <select name="hypothesis" id="hypothesis" class="form-control mb-3">
                            <option value="">انتخاب نوع مقیاس</option>
                        </select>


                        <label>نوع فرضیه</label>
                        <select name="designType" id="designType" class="form-control mb-3">
                            <option value="">انتخاب نوع فرضیه</option>
                        </select>


                        <div id="furmules">

                        </div>

                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-danger light close-btn"
                        data-bs-dismiss="modal"
                    >
                        بستن
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <script src="{{asset('/js/dashboard/sweetalert2@9.js')}}"></script>
    <script src="{{asset('/js/dashboard/study.js')}}"></script>
    <script src="{{asset('/js/dashboard/Chart.bundle.min.js')}}"></script>
    <script src="{{asset('/js/dashboard/jquery.peity.min.js')}}"></script>
    <script src="{{asset('/js/dashboard/vivus.min.js')}}"></script>
    <script src="{{asset('/js/dashboard/dataTables.js')}}"></script>
    <script src="{{asset('/js/dashboard/datatables.init.js')}}"></script>
    <script src="{{asset('/js/dashboard/custom-select.js')}}"></script>
    <script src="{{asset('/js/dashboard/manage-plan.js')}}"></script>

    @if (Session::has('message'))
        <script>
            $(document).ready(function () {

                const id = {{Session::get('id')}} + '';
                if (id != 0) {
                    Swal.fire({
                        title: "{{ Session::get('message') }}",
                        icon: "warning",
                        confirmButtonColor: "#DD6B55",
                        showCancelButton: true,
                        confirmButtonText: "محاسبه",
                        cancelButtonText: "بستن",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $(`.calc-plan-btn[data-id="${id}"]`).click();
                            $('#sample_size_modal').modal('toggle');
                        }
                    });
                }else{
                    swal.fire('ثبت شد' , 'اطلاعات با موفقیت ثبت شد !' , 'success');
                }
            })
        </script>
    @endif
    {{--    // Omid--}}
    {{--    <script src="{{asset('/js/dashboard/jquery-3.3.1.min.js')}}"></script>--}}
    {{--    <script src="{{asset('/js/dashboard/jquery-a.min.js')}}"></script>--}}
    <script src="{{asset('/js/dashboard/fns.js')}}"></script>
    <script src="{{asset('/js/dashboard/variables.js')}}"></script>
    <script src="{{asset('/js/dashboard/custom.js')}}"></script>
    <script src="{{asset('/js/dashboard/f_Htmls/f1_html.js')}}"></script>
    <script src="{{asset('/js/dashboard/f_Htmls/f2_html.js')}}"></script>
    <script src="{{asset('/js/dashboard/f_Htmls/f3_html.js')}}"></script>
    <script src="{{asset('/js/dashboard/f_Htmls/f4_html.js')}}"></script>
    <script src="{{asset('/js/dashboard/f_Htmls/f5_html.js')}}"></script>
    <script src="{{asset('/js/dashboard/f_Htmls/f6_html.js')}}"></script>
    <script src="{{asset('/js/dashboard/f_Htmls/f7_html.js')}}"></script>
    <script src="{{asset('/js/dashboard/f_Htmls/f8_html.js')}}"></script>
    <script src="{{asset('/js/dashboard/f_Htmls/f9_html.js')}}"></script>
    <script src="{{asset('/js/dashboard/f_Htmls/f10_html.js')}}"></script>
    <script src="{{asset('/js/dashboard/f_Htmls/f11_html.js')}}"></script>
    <script src="{{asset('/js/dashboard/f_Htmls/f12_html.js')}}"></script>
    <script src="{{asset('/js/dashboard/f_Htmls/f13_html.js')}}"></script>
    <script src="{{asset('/js/dashboard/f_Htmls/f14_html.js')}}"></script>
    <script src="{{asset('/js/dashboard/f_Htmls/f15_html.js')}}"></script>
    <script src="{{asset('/js/dashboard/f_Htmls/f16_html.js')}}"></script>
    <script src="{{asset('/js/dashboard/f_Htmls/f17_html.js')}}"></script>
    <script src="{{asset('/js/dashboard/f_Htmls/f18_html.js')}}"></script>
    <script src="{{asset('/js/dashboard/f_Htmls/f19_html.js')}}"></script>
    <script src="{{asset('/js/dashboard/computed.js')}}"></script>
    <script src="{{asset('/js/dashboard/furmules/furmula1.js')}}"></script>
    <script src="{{asset('/js/dashboard/furmules/furmula2.js')}}"></script>
    <script src="{{asset('/js/dashboard/furmules/furmula3.js')}}"></script>
    <script src="{{asset('/js/dashboard/furmules/furmula4.js')}}"></script>
    <script src="{{asset('/js/dashboard/furmules/furmula5.js')}}"></script>
    <script src="{{asset('/js/dashboard/furmules/furmula6.js')}}"></script>
    <script src="{{asset('/js/dashboard/furmules/furmula7.js')}}"></script>
    <script src="{{asset('/js/dashboard/furmules/furmula8.js')}}"></script>
    <script src="{{asset('/js/dashboard/furmules/furmula9.js')}}"></script>
    <script src="{{asset('/js/dashboard/furmules/furmula10.js')}}"></script>
    <script src="{{asset('/js/dashboard/furmules/furmula11.js')}}"></script>
    <script src="{{asset('/js/dashboard/furmules/furmula12.js')}}"></script>
    <script src="{{asset('/js/dashboard/furmules/furmula13.js')}}"></script>
    <script src="{{asset('/js/dashboard/furmules/furmula14.js')}}"></script>
    <script src="{{asset('/js/dashboard/furmules/furmula15.js')}}"></script>
    <script src="{{asset('/js/dashboard/furmules/furmula16.js')}}"></script>
    <script src="{{asset('/js/dashboard/furmules/furmula17.js')}}"></script>
    <script src="{{asset('/js/dashboard/furmules/furmula18.js')}}"></script>
    <script src="{{asset('/js/dashboard/furmules/furmula19.js')}}"></script>

@endpush
