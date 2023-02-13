@extends('dashboard.layout.master')

@section('pagetitle')
    @parent
    مدیریت  رندومایزیشن ها
@endsection
@section('head')
    <link href="{{ asset('/css/dashboard/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard/buttons-datatable.css') }}"/>
@endsection
@section('content')

    <section class="content">
        <div class="content-body">
            <div class="container-fluid">


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">جدول داده های طرح ها</h4>
                                @if(request()->routeIs('report.*'))
                                    @php($flag=false)
                                @else
                                    @php($flag=true)
                                @endif
                            </div>
                            {{--                            @if($flag)--}}
                            {{--                                <div>--}}
                            {{--                                    <label class="label-in-search"> جستجو : </label>--}}
                            {{--                                    <input type="search" class="in-search">--}}
                            {{--                                </div>--}}
                            {{--                            @endif--}}
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="{{$flag ? '' : "example3"}}"
                                           class="display{{$flag ? " posts-table" : ''}}">
                                        <thead>
                                        <tr>

                                            <th>ردیف</th>
                                            <th>عنوان طرح</th>
                                            <th>گروه ها</th>
                                            <th>تعداد بیماران</th>
                                            <th>عملیات</th>

                                        </tr>
                                        </thead>
                                        <tbody class="search-table">
                                        @php($counter = 1)
                                        @foreach ($studies as $study)
                                            <tr id="rand-{{$study->id}}">
                                                <td>{{$counter++}}</td>
                                                <td>{{$study->title}}</td>
                                                <td>{{$study->groups}}</td>
                                                <td>{{$study->patients()->count()}}</td>
                                                <td>
                                                    <a href="{{route('patient.randomisation.excel' , ['id'=>$study->id])}}"
                                                       class="btn bg-success text-white shadow btn-xs sharp">
                                                        <i class="fa-solid fa-file-excel"></i>
                                                    </a>

                                                    <a onclick="deleterecord({{$study->id}})"
                                                       class="btn btn-danger text-white shadow btn-xs sharp"><i
                                                            class="fa-solid fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    {{ $studies->links('pagination.default') }}
                                </div>


                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')

    <script src="{{ asset('/js/dashboard/rand.js') }}"></script>
    {{--    <script data-cfasync="false" src="{{ asset('/js/dashboard/email-decode.min.js') }}"></script>
    --}}
    <script src="{{ asset('/js/dashboard/custom-select.js') }}"></script>
    <script src="{{ asset('/js/dashboard/dataTables.js') }}"></script>
    <script src="{{ asset('js/dashboard/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/dashboard/buttons-html5.js') }}"></script>
    <script src="{{ asset('js/dashboard/buttons-print.js') }}"></script>
    <script src="{{ asset('js/dashboard/buttons.js') }}"></script>
    <script src="{{ asset('/js/dashboard/datatables.init.js') }}"></script>

@endpush
