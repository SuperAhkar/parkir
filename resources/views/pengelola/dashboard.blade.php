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
    <title>Dashboard</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css">
</head>

<body>
    <?php

    use Illuminate\Support\Facades\Auth; ?>
    <!-- Popup modal Filter -->
    <div class="modal fade bd-example-modal-lg" id="topupmodal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <?php
                $user_infos = App\Models\User::find(Auth::user()->id); ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Tarik Saldo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 style="font-weight: medium; font-size:18px;">Masukkan Jumlah Tarik</h4>
                    <input type="hidden" id="saldouser" name="saldouser" value="{{$user_infos->saldo}}">
                    <form action="{{ route('pengelola.topup') }}" method="POST">
                        @csrf
                        <br>

                        <div class="row mb-5" style="margin-top: -20px; padding:10px;">
                            <div class="container-fluid">
                                <div class="form-group">
                                    <div class="mb-3">
                                        <input type="hidden" value="{{ $user_infos->id }}" name="id">
                                        <label for="formGroupExampleInput" class="form-label">Jumlah Tarik</label>
                                        <input type="number" class="form-control" id="saldotarik" name="saldotarik" placeholder="Masukkan Jumlah Saldo">
                                    </div>
                                    <div class="mb-3">
                                        <label for="formGroupExampleInput2" class="form-label">Tujuan Penarikkan</label>
                                        <select name="metode" id="metode" style="width: 100%;border:solid 1px #ACB8C2;border-radius:6px;padding-left:10px;padding-right:10px;">
                                            <option value="dana">Dana</option>
                                            <option value="gopay">Gopay</option>
                                            <option value="ovo">Ovo</option>
                                            <option value="shopeepay">ShopeePay</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="formGroupExampleInput" class="form-label">Nomor Handphone</label>
                                        <input class="form-control" placeholder="Masukkan Nomor Handphone">
                                    </div>
                                </div>
                            </div>
                        </div>

                </div>
                <div class="modal-footer mt-2">
                    <button type="submit" onclick="tes()" class="focus:outline-none text-blueDark w-30 mt-2 bg-orange hover:bg-orange font-bold rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:focus:ring-yellow-900" title="Top Up">Tarik Saldo</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Popup modal end -->
    <div class="w-7/12 ml-3 bg-white border border-gray-200 rounded-2xl shadow-md max-h-80vh overflow-auto p-4">
        <div class="row">
            <p class="col text-blueDark text-xl" style="font-size: 25px;">Pelanggan Pending</p>
            <div class="col-sm-12 tablestart">
                <table class="table">
                    <thead>
                    </thead>
                    <tbody>

                        <?php $pengelola_info = App\Models\User::find(Auth::user()->id); ?>
                        @if($reservasis->count() == 0)
                        <div class="text-center" style="padding: 60px;margin-bottom:50px;">
                            <a href=""><i class="fas fa-exclamation-circle" style="font-size: 100px;color:#ffec58"></i></a>
                            <br>
                            <h5 style="margin-top: 20px;">Tidak ada pelanggan pending</h5>
                        </div>
                        @else
                        @foreach ($reservasis as $pd)

                        @if($pd->status == 'unconfirmed' && $pd->info == 'belummulai' )
                        <div id="aselole" class="aselole">
                            <?php $user_info = App\Models\User::find($pd->user_id); ?>
                            <tr id="sid{{ $pd->id }}" class="bg-white border border-gray-200 rounded-2xl shadow-md overflow-auto">
                                <td><i class="fa-solid fa-user" style="font-size: 30px;padding-left:1px;"></i><br>
                                    <p class="cdwn" style="font-size:small;"></p>
                                </td>
                                <td>{{ $user_info->name }}</td>
                                <td>{{$pd->checkintime}}
                                <!-- <td>{{$pd->checkintime}}-{{$pd->checkouttime}} ({{$pd->lamaparkir}} jam) -->
                                    <!-- <br>Rp {{$pd->biayatotal}} -->
                                </td>
                                <p class="td1" style="display: none;">{{$pd->checkintime}}</p>
                                <p class="date1" style="display: none;">{{$pd->checkindate}}</p>
                                <input type="hidden" class="biayatotal" id="biayatotal" name="biayatotal" value="{{$pd->biayatotal}}">
                                <input type="hidden" class="slotsekarang" id="slotsekarang" name="slotsekarang" value="{{$pd->slot}}">
                                <p class="tdout" style="display: none;">{{$pd->checkindate}}</p>
                                <input type="hidden" class="saldosekarang" id="saldosekarang" name="saldosekarang" value="{{ $pengelola_info->saldo }}"></input>
                                <form action="{{ route('pengelola.update') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" id="id" name="id" value="{{$pd->id}}">
                                    <input type="hidden" id="parkir_id" name="parkir_id" value="{{$pd->parkir_id}}">
                                    <input type="hidden" id="status" name="status" value="confirmed">
                                    <input type="hidden" class="saldo" id="saldo" name="saldo">
                                    <input type="hidden" class="slot" id="slot" name="slot">
                                    <input type="hidden" id="info" name="info" value="aktif">
                                    <td>
                                        <button class="btn btn-edit mx-2" type="submit" href="">Terima</button>
                                    </td>
                                </form>
                            </tr>
                        </div>
                        @endif

                        @endforeach

                        @endif
                    </tbody>
                </table>
            </div>
            <p class="col text-blueDark text-xl" style="font-size: 25px;">Pelanggan Aktif</p>
            <div class="col-sm-12 tablestart">
                <table class="table">
                    <thead>
                    </thead>
                    <tbody>

                        @if($reservasis->count() == 0)
                        <div class="text-center" style="padding: 60px;margin-bottom:50px;">
                            <a href=""><i class="fas fa-exclamation-circle" style="font-size: 100px;color:#ffec58"></i></a>
                            <br>
                            <h5 style="margin-top: 20px;">Tidak ada pelanggan aktif</h5>
                        </div>
                        @else
                        @foreach ($reservasis as $pd)

                        @if($pd->status == 'confirmed' && $pd->info == 'aktif')

                        <?php $user_info = App\Models\User::find($pd->user_id); ?>
                        <tr class="bg-white border border-gray-200 rounded-2xl shadow-md overflow-auto">
                            <td><i class="fa-solid fa-user" style="font-size: 30px;padding-left:1px;"></i><br>
                                <p></p>
                            </td>
                            <td>{{ $user_info->name }}</td>
                            <td style="text-align:center;">{{$pd->checkindate}}<br>
                            <p style="font-size:13px;">{{$pd->checkintime}} - Sekarang</p>
                            <!-- <td>{{$pd->checkintime}}-{{$pd->checkouttime}} ({{$pd->lamaparkir}} jam) -->
                                <!-- <br>Rp {{$pd->biayatotal}} -->
                            </td>
                            <!-- <td>Durasi tersisa<br> -->
                            <td>
                                <p class="durasisisa"></p>
                            </td>
                            <p class="cotime" style="display: none;">{{$pd->checkouttime}}</p>
                            <p class="tdin" style="display: none;">{{$pd->checkintime}}</p>
                            <p class="date2" style="display: none;">{{$pd->checkindate}}</p>
                            <p class="date3" style="display: none;">{{$pd->checkoutdate}}</p>
                        </tr>
                        @endif
                        @endforeach

                        @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div class="w-2/12 ml-3">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-md overflow-auto p-4" style="height: fit-content;">
            <p class="col text-blueDark text-xl" style="font-size:20px;">Spot Parkir</p>

            <?php $parkir_info = App\Models\RegParkir::find(Auth::user()->id); ?>
            @if(!$parkir_info)
            <p class="col text-blueDark text-xl mt-3" style="font-size: 25px;">0/0</p>
            @else
            @foreach($reservasis as $pd)
            @endforeach
            <p class="col text-blueDark text-xl mt-3" style="font-size: 25px;">{{$parkir_info->slot}}/{{$parkir_info->slotmaksimal}}</p>
            @endif
            <!-- <div class="row mt-4" style="justify-content: space-between;">
                <div class="col" style="text-align: center;">
                    <button class="btn btn-slot text-center w-100">+</button>
                </div>
                <div class="col" style="text-align: center;">
                    <button class="btn btn-slot text-center w-100">-</button>
                </div>
            </div> -->
        </div>
        <div class="block mt-4 max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-md">
            <p class="text-gray-700 dark:text-gray-400" style="font-size:20px;">Saldo</p>

            @if($reservasis->count() == 0)

            <p class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Rp 0</p>

            @else

            @foreach ($reservasis as $pd)
            <?php $pengelola_info = App\Models\User::find($pd->parkir_id); ?>
            @endforeach
            <p class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Rp {{$pengelola_info->saldo}}</p>

            @endif

        </div>

        <button class="btn btn-edit w-100 mt-3" data-toggle="modal" data-target="#topupmodal" type="button" href="">Tarik Saldo</button>

    </div>
</body>
<script>
        for (var i = 0; i < document.getElementsByClassName('aselole').length; i++) {
            var y = document.getElementsByClassName('slotsekarang')[i].value;
            var a = document.getElementsByClassName('saldosekarang')[i].value;
            var b = document.getElementsByClassName('biayatotal')[i].value;
            console.log(document.getElementsByClassName('aselole').length);
            console.log(y);
            console.log(a);
            console.log(b);
            var slotnow = parseInt(y);
            var saldoadmin = parseInt(a);
            var totalbiaya = parseInt(b);

            var saldosekarang = saldoadmin + totalbiaya;
            var sisa_slot = (slotnow - 1);
            console.log(saldosekarang);
            console.log(sisa_slot);
            document.getElementsByClassName("slot")[i].value = sisa_slot;
            document.getElementsByClassName("saldo")[i].value = saldosekarang;
    }
</script>
<script>
    function tes() {
        var saldoskrg = document.getElementById('saldouser').value;
        var inputsaldo = document.getElementById('saldotarik').value;

        console.log(saldoskrg);
        console.log(inputsaldo);

        var saldonow = parseInt(saldoskrg);
        var saldoskrg = parseInt(inputsaldo);

        var saldonew = (saldonow - saldoskrg);
        console.log('saldobaru' + saldonew);

        document.querySelector("[name=saldotarik]").value = saldonew;
    }
</script>
<!-- <script>
    var y = document.getElementById('slotsekarang').value;

    var slotnow = parseInt(y);

    var sisa_slot = (slotnow - 1).toString();

    document.querySelector("[name=slot]").value = sisa_slot;

    var total_harga = document.getElementById('biayatotal').value;
    var saldosekarang = document.getElementById('saldosekarang').value;
    console.log("total harga" + total_harga);
    console.log("saldo skrg" + saldosekarang);
    var duitbaru = parseInt(total_harga);
    var saldonow = parseInt(saldosekarang);

    var total_saldo = duitbaru + saldonow;

    document.querySelector("[name=saldo]").value = total_saldo;
    console.log(total_saldo);
</script> -->
<script>
    var days;
    var hours;
    var minutes;
    var seconds;

    class Coba {
        constructor() {

        }

        Call(checkintime, checkindate, i) {
            var x = setInterval(function() {
                //Tabel Atas
                var now = new Date().getTime();
                var countDownDate = new Date(checkindate + " " + checkintime).getTime();

                var distance = countDownDate - now;
                // var distance = now - countDownDate;
                
                days = Math.floor(distance / (1000 * 60 * 60 * 24));
                hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                seconds = Math.floor((distance % (1000 * 60)) / 1000);
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementsByClassName("cdwn")[i].innerHTML = "OVERTIME";
                } else {
                    document.getElementsByClassName("cdwn")[i].innerHTML = hours + ":" +
                        minutes + ":" + seconds;
                }
            }, 1);
        }
    }


    for (var i = 0; i < <?php echo json_encode($reservasis->count()) ?>; i++) {
        var checkintime = document.getElementsByClassName("td1")[i].innerHTML.toString();
        var checkindate = document.getElementsByClassName("date1")[i].innerHTML.toString();


        const coba = new Coba();
        coba.Call(checkintime, checkindate, i);


    }
</script>

<script>
    var days;
    var hours;
    var minutes;
    var seconds;

    class Test {
        constructor() {}

        Call(checkindate, checkouttime, checkintime, checkoutdate, i) {
            var now = new Date(checkindate + " " + checkintime).getTime();
            var countDownDate = new Date(checkoutdate + " " + checkouttime).getTime();
            var waktusekarang = new Date().getTime();

            if (now <= waktusekarang) {
                var x = setInterval(function() {
                    //Tabel Atas
                    var now = new Date().getTime();
                    var sekarangbgt = new Date(checkindate + " " + checkintime).getTime();

                    // now += 4;

                    // var distance = countDownDate - now;
                    var distance = now - sekarangbgt;

                    days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    if (distance < 0) {
                        clearInterval(x);
                        document.getElementsByClassName("durasisisa")[i].innerHTML = "DONE";
                    } else {
                        document.getElementsByClassName("durasisisa")[i].innerHTML = hours + ":" +
                            minutes + ":" + seconds;
                    }
                }, 1);
            } else if (waktusekarang > now) {
                document.getElementsByClassName("durasisisa")[i].innerHTML = "DONE";
            } else if (waktusekarang < now) {
                document.getElementsByClassName("durasisisa")[i].innerHTML = "WAITING";
            }
        }
    }

    for (var i = 0; i < <?php echo json_encode($reservasis->count()) ?>; i++) {
        var checkouttime = document.getElementsByClassName("cotime")[i].innerHTML.toString();
        var checkintime = document.getElementsByClassName("tdin")[i].innerHTML.toString();
        var checkindate = document.getElementsByClassName("date2")[i].innerHTML.toString();
        var checkoutdate = document.getElementsByClassName("date3")[i].innerHTML.toString();

        const coba = new Test();
        coba.Call(checkintime, checkouttime, checkindate, checkoutdate, i);
    }
</script>

</html>
@endsection