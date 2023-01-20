@extends('user.template')
@section('title', 'Transaksi')

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
    <?php

    use Illuminate\Support\Facades\Auth; ?>
    <div class="w-10/12 ml-3 bg-white border border-gray-200 rounded-2xl shadow-md max-h-80vh overflow-auto p-4">
        <p class="col text-blueDark text-xl mt-4" style="font-size: 25px;">Transaksi Aktif</p>
        <div class="col-sm-12 tablestart">
            <form action="{{ route('admin.searchtransaksi') }}" method="post" class="row">
                @csrf
                <div class="col-sm-10">
                    <input type="text" id="search" class="form-control" placeholder="Cari User" name="search">
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
                    <?php
                    $admin_info = App\Models\User::find(Auth::user()->id); ?>
                    @if($transaksi->count() == 0)
                    <div class="text-center" style="padding: 60px;margin-bottom:50px;">
                        <a href=""><i class="fas fa-exclamation-circle" style="font-size: 100px;color:#ffec58"></i></a>
                        <br>
                        <h5 style="margin-top: 20px;">Tidak ada transaksi aktif</h5>
                    </div>
                    @else

                    @foreach ($transaksi as $tr)
                    @if($tr->info == 'aktif' && $tr->status == 'confirmed' )
                    <?php $user_info = App\Models\User::find($tr->user_id); ?>
                    <?php $pengelola_info = App\Models\User::find($tr->parkir_id); ?>
                    <tr id="" class="bg-white border border-gray-200 rounded-2xl shadow-md overflow-auto">
                        <td style="vertical-align: middle;text-align:center"><i class="fa-solid fa-user" style="font-size: 25px;"></i></td>
                        <td style="vertical-align:middle">{{$user_info->name}}
                        </td>
                        <td style="vertical-align:middle">{{$pengelola_info->name}}</td>
                        <td style="vertical-align: middle;text-align:center">{{$tr->checkindate}}<br>
                            <p style="font-size:13px;">{{$tr->checkintime}} - Sekarang</p>
                            <!-- <p style="font-size:13px;">{{$tr->checkintime}}-{{$tr->checkouttime}} ({{$tr->lamaparkir}} jam)</p> -->
                        </td>
                        <!-- <td style="vertical-align: middle;">Rp {{$tr->biayatotal}}</td> -->
                        <td>
                            <input type="hidden" id="slotsekarang" name="slotsekarang" value="{{$tr->slot}}">
                            <form action="{{ route('admin.selesai') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="id" name="id" value="{{$tr->id}}">
                                <input type="hidden" id="parkir_id" name="parkir_id" value="{{$tr->parkir_id}}">
                                <input type="hidden" id="info" name="info" value="nonaktif">
                                <input type="hidden" id="slot" name="slot">
                                <button type="submit" class="bg-red text-center py-3 rounded-lg" style="cursor: pointer;width:130px;">
                                    Batalkan
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endif
                    @endforeach

                    @endif
                </tbody>
            </table>
        </div>
    </div>
</body>
<script>
    var b = document.getElementById('slotsekarang').value;
    var slotsekarang = parseInt(b);

    var total_slot = (slotsekarang + 1).toString();
    console.log(total_slot);
    document.querySelector("[name=slot]").value = total_slot;
</script>
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