<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            direction: rtl;
            font-size: 0.9em;
            font-family: 'examplefont', sans-serif;
            text-align: center;
        }

        table {
            width: 100%;
            text-align: center;
        }


        table thead th {
            color: white !important;
            background-color: #55a5c5;
            padding: 10px 0 !important;
            border-bottom: 1px solid #2091bd;
        }

        table tbody td {
            padding: 7px 0;
            border-bottom: 1px solid #a6d1e2;
        }

        .mb-3 {
            margin-bottom: 20px;
        }


        .pagination {
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            background-color: #2091bd;
            color: white;
            font-size: 1.3em;
        }

        .border-bottom {
            border-bottom: 1px solid #ccc;
        }


        .date {
            float: left;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="pdf-content">

        <h2>اطلاعات طراحان</h2>
        <div class="date">{{ $date }}</div>
        <table class="box ">
            <thead>
                <tr class="header">
                    <th>شماره</th>
                    <th>نام و نام خانوادگی</th>
                    <th>کدملی</th>
                    <th>مرتبه علمی</th>
                    <th>مدرک تحصیلی</th>
{{--                    <th>تخصص</th>--}}
                    <th>دانشگاه</th>
                    <th>سازمان</th>
                </tr>
            </thead>
            <tbody>
                @php($counter = 1)
                @foreach ($researchers as $researcher)
                    <tr class="row">
                        <td class="col-lg-4 ">
                            <p>{{ $counter++ }}</p>
                        </td>
                        <td class="col-lg-4 ">
                            <p>{{ $researcher->name . ' ' . $researcher->f_name }}</p>
                        </td>
                        <td class=" col-lg-4 ">
                            <p>{{ $researcher->n_number }}</p>
                        </td>
                        <td class=" col-lg-4 ">
                            <p>{{ $researcher->range }}</p>
                        </td>
                        <td class=" col-lg-4 ">
                            <p>{{ $researcher->major }}</p>
                        </td>
{{--                        <td class=" col-lg-4 ">--}}
{{--                            <p>{{ $researcher->proficiency }}</p>--}}
{{--                        </td>--}}
                        <td class=" col-lg-4 ">
                            <p>{{ $researcher->university }}</p>
                        </td>
                        <td class=" col-lg-4 ">
                            <p>{{ $researcher->organization }}</p>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</body>

</html>
