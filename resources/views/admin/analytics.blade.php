@extends('user.template')
@section('title', 'Analytics - Admin')

@section('content')
<!doctype html>
<html>

<style>
    .tablestart {
        padding: 20px;
    }

    .btn-edit {
        color: #183153;
        font-weight: 400;
        width: 170px;
        font-size: 16px;
        border-radius: 10px;
        background-color: #D98829;
    }
</style>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css">
</head>

<body>
    <?php
        use App\Models\reservasi;
        use App\Models\User;
        use Illuminate\Support\Facades\Auth;
    ?>
    <div class="w-10/12 ml-3 ">
        <div class="row mb-2 overflow-auto">
            <div class="p-3 col-sm-4">
                <div class="bg-white border border-gray-200 rounded-2xl shadow-md p-4">
                    <p class="text-blueDark text-xl" style="font-size: 16px;">Pendapatan Total</p>
                    <p class="text-blueDark text-xl">Rp {{$saldoadmin}}</p>
                </div>
            </div>
            <div class="p-3 col-sm-8">
                <form action="{{route('admin.filter')}}" method="post" enctype="multipart/form-data">
                @csrf
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-md p-4">
                        <p class="text-blueDark text-xl" style="font-size: 16px;">Pendapatan per Bulan</p>
                        <div class="row">
                            <div class="col-sm-4">
                                <input type="month" class="form-control" name="bulanawal" id="bulanawal" value="{{$bulanawal}}">
                            </div>
                            <div class="col-sm-1">
                                <p>-</p>
                            </div>
                            <div class="col-sm-4">
                                <input type="month" class="form-control" name="bulanakhir" id="bulanakhir" value="{{$bulanakhir}}">
                            </div>
                            <div class="col-sm-3">
                                <button class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="p-3 col-sm-4">
                <div class="bg-white border border-gray-200 rounded-2xl shadow-md p-4">
                    <p class="text-blueDark text-xl" style="font-size: 16px;">Total User</p>
                    <p class="text-blueDark text-xl">{{$jumlahuser}}</p>
                </div>
            </div>
            <div class="pb-4 col-sm-8">
                <div class="bg-white border border-gray-200 rounded-2xl shadow-md p-4" id="graph"> 
                </div>
            </div>
            <div class="p-3 col-sm-4" style="bottom:300px;">
                <div class="bg-white border border-gray-200 rounded-2xl shadow-md p-4">
                    <p class="text-blueDark text-xl" style="font-size: 16px;">Total Perusahaan</p>
                    <p class="text-blueDark text-xl">{{$jumlahperusahaan}}</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
    var pendapatan = <?php echo json_encode($pendapatan); ?>;
    var months = <?php echo json_encode($months); ?>;
    // var bulan = <?php //echo json_encode(date('M Y', strtotime($months))); ?>;
    Highcharts.chart('graph', {
        title: {
            text: 'Grafik Pendapatan'
        },
        yAxis: {
            title:{
                text: 'Nominal Pendapatan'
            }
        },
        xAxis: {
            categories: months
        },
        plotOptions: {
            series: {
                allowPointSelect: true
            }
        },
        series: [{
            name: 'Pendapatan',
            // data: [20000, 75000]
            data: pendapatan
        }
        ]
    });
</script>
@endsection