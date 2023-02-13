@extends('dashboard.layout.master')

@section('pagetitle')
    @parent
    رندومایزیشن  بیمار
@endsection

@section('head')
    <link href="{{ asset('/css/dashboard/randomization.css') }}" rel="stylesheet"/>
@endsection

@section('content')
    <section class="content">

        <div class="content-body">
            <div class="container-fluid">

                <div
                    class="row questionnaire-box justify-content-center mx-0 align-items-center card"
                >

                    <!-- ========= summary ========== -->

                    <div class="col-12">
                        @if(\App\Models\Study::find($id)->patients()->exists())
                            <div class="alert alert-warning alert-dismissible fade show m-2 fs-5" role="alert">
                                رندومایزیشن برای این طرح قبلا ثبت شده است . در صورت ادامه دادن بیمارهای قبلی حذف میشوند .
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                            </div>
                        @endif
                        <div>
                            <h3 class="p-2 pt-3 border-bottom text-center">ساخت لیست</h3>
                            <div>
                                <form id="randomization" method="post"
                                      action="{{route('patient.randomisation.store' , ['id'=>$id])}}">
                                    @csrf
                                    <div class="row mx-0 mb-4">
                                        <div class="col-12 col-lg-6 px-3 mb-1">
                                            <div class="form-group">
                                                <h3 class="mb-1 mt-3">دانه</h3>
                                                <input value="{{old('seed')}}" name="seed"
                                                       type="number"
                                                       class="form-control"
                                                       placeholder="1234"
                                                />
                                                <span class="d-inline-block mt-1 mr-2"
                                                >دانه برای مولد اعداد تصادفی</span
                                                >
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6 px-3 mb-1">
                                            <div class="form-group">
                                                <h3 class="mb-1 mt-3">گروه های درمانی</h3>
                                                <input value="{{old('groups')}}" name="groups"
                                                       type="text"
                                                       class="form-control"
                                                       placeholder="گروه A, گروه B"
                                                       required
                                                />
                                                <div class="errors text-danger"></div>
                                                <span class="d-inline-block mt-1 mr-2"
                                                >لیستی از درمان ها جدا شده با کاما</span
                                                >
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6 px-3 mb-1">
                                            <div class="form-group">
                                                <h3 class="mb-1 mt-3">اندازه های بلوک</h3>
                                                <input value="{{old('blocks')}}" name="blocks"
                                                       type="text"
                                                       class="form-control"
                                                       placeholder="4, 6, 8"
                                                       required
                                                />
                                                <div class="errors text-danger"></div>
                                                <span class="d-inline-block mt-1 mr-2"
                                                >یک لیست از اندازه بلوک جدا شده با کاما - باید چند
                            برابر تعداد درمان ها باشد</span
                                                >
                                            </div>
                                        </div>

                                        {{--                                        <div class="col-12 col-lg-6 px-3 mb-1">--}}
                                        {{--                                            <div class="form-group">--}}
                                        {{--                                                <h3 class="mb-1 mt-3">طول لیست</h3>--}}
                                        {{--                                                <input value="{{old('number')}}" name="number"--}}
                                        {{--                                                       type="number"--}}
                                        {{--                                                       class="form-control"--}}
                                        {{--                                                       placeholder="50" required--}}
                                        {{--                                                />--}}
                                        {{--                                            </div>--}}
                                        {{--                                        </div>--}}

                                        <div class="col-12 col-lg-6 px-3 mb-1">
                                            <div class="form-group position-relative">
                                                <h3 class="mb-1 mt-3">عوامل طبقه بندی (گزینه)</h3>
                                                <!-- <input
                                                  type="text"
                                                  class="form-control"
                                                  id="stratification_input"
                                                  placeholder="سن گروه :‌زیر 30 ,  30 و بالاتر"
                                                /> -->
                                                <select
                                                    name=""
                                                    id="stratification_input"
                                                    class="form-control"
                                                    multiple
                                                >
                                                    <option value="age">سن</option>
                                                    <option value="city[]">شهر</option>
                                                    <option value="sick">نوع عارضه</option>
                                                    <option value="weight">وزن</option>
                                                    <option value="gender[]">جنسیت</option>
                                                    <option value="height">قد</option>
                                                    <option value="marid[]">وضعیت تاهل</option>
                                                </select>
                                                <div class="errors text-danger"></div>
                                                <!-- <i class="fa-solid fa-plus"></i> -->
                                            </div>
                                        </div>


                                        <div
                                            class="row mx-auto px-0"
                                            id="stratification_box"
                                        ></div>

                                        <div class="mt-2">
                                            <button type="submit" class="btn btn-primary">
                                                ایجاد لیست
                                            </button>
                                            {{--                                        <a class="btn btn-light"> دانلود CSV </a>--}}
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')

    <script src="{{ asset('/js/dashboard/randomization.js') }}"></script>

@endpush
