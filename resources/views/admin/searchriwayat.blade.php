@extends('user.template')
@section('title', 'Dashboard - Pengelola')

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

    .btn-slot {
        background-color: #DDDDDD;
        border: none;
    }
</style>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Transaksi</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css">
</head>

<body>
    <div class="w-10/12 ml-3 bg-white border border-gray-200 rounded-2xl shadow-md max-h-80vh overflow-auto p-4">
        <div class="row">
            <p class="col text-blueDark text-xl" style="font-size: 25px;">Riwayat Transaksi</p>
            <div class="col-sm-12 tablestart">
            <form action="{{ route('admin.searchriwayat') }}" method="post" class="row">
                    @csrf
                    <div class="col-sm-10">
                        <input type="text" id="search" class="form-control" placeholder="Cari Riwayat" name="search">
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" class="focus:outline-none text-blueDark w-full bg-orange hover:bg-orange font-bold rounded-lg text-l mr-2 mb-2 p-2 dark:focus:ring-yellow-900">Cari</button>
                    </div>

                </form>
            </div>
            <div class="col-sm-12 tablestart">
                <table class="table">
                    <thead>
                    </thead>
                    <tbody>
                        @if($riwayat->count() == 0)
                        <div class="text-center" style="padding: 60px;margin-bottom:50px;">
                            <a href=""><i class="fas fa-exclamation-circle" style="font-size: 100px;color:#ffec58"></i></a>
                            <br>
                            <h5 style="margin-top: 20px;">Tidak ada riwayat transaksi</h5>
                        </div>
                        @else

                        @foreach ($riwayat as $tr)
                        @if($tr->info == 'nonaktif' && $tr->status == 'confirmed' )
                        <?php $user_info = App\Models\User::find($tr->user_id); ?>
                        <?php $pengelola_info = App\Models\User::find($tr->parkir_id); ?>
                        <tr id="" class="bg-white border border-gray-200 rounded-2xl shadow-md overflow-auto">
                            <td style="vertical-align: middle; text-align:center"><i class="fa-solid fa-user" style="font-size: 25px;"></i></td>
                            <td style="vertical-align:middle">{{$user_info->name}}
                            </td>
                            <td style="vertical-align:middle">{{$pengelola_info->name}}</td>
                            <td>{{$tr->checkindate}}<br>
                                <p style="font-size:13px;">{{$tr->checkintime}}-{{$tr->checkouttime}} ({{$tr->lamaparkir}} jam)</p>
                            </td>
                            <td style="vertical-align: middle;">Rp {{$tr->biayatotal}}</td>
                        </tr>

                        @endif
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

<script>
    $("#statusEditform").submit(function(e) {

        e.preventDefault();

        let id = $("#id").val();
        // let user_id = $("#user_id").val();
        // let parkir_id = $("#parkir_id").val();
        // let nokendaraan = $("#nokendaraan").val();
        // let tipekendaraan = $("#tipekendaraan").val();
        // let checkindate = $("#checkindate").val();
        // let checkintime = $("#checkintime").val();   
        // let checkoutdate = $("#checkoutdate").val();    
        // let checkouttime = $("#checkouttime").val();   
        let status = $("#status").val();
        let info = $("#info").val();
        // let lamaparkir = $("#lamaparkir").val();
        // let biayatotal = $("#biayatotal").val();
        // let metodebayar = $("#metodebayar").val(); 

        $.ajax({
            url: "{{route('pengelola.update')}}",
            type: "PUT",
            data: {
                id: id,
                // user_id: user_id,
                // parkir_id: parkir_id,
                // nokendaraan: nokendaraan,
                // tipekendaraan: tipekendaraan,
                // checkindate: checkindate,
                // checkintime: checkintime,
                // checkoutdate: checkoutdate,
                // checkouttime: checkouttime,
                status: status
                // lamaparkir: lamaparkir,
                // biayatotal: biayatotal,
                // metodebayar: metodebayar
            },
            success: function(response) {
                console.log(data);
                console.log('Masuk');
            }
        });
    });
</script>

</html>
@endsection