@extends('user.template')
@section('title', 'Dashboard - Cari')

@section('content')
<div class="w-3/12 px-4">
    <a href="{{ route('map.map') }}">
        <button type="button" class="focus:outline-none text-blueDark w-full mt-2 bg-orange hover:bg-orange font-bold rounded-lg text-xl px-5 py-6 mr-2 mb-2 dark:focus:ring-yellow-900">Cari
            Parkir</button>
    </a>
    <?php

    use Illuminate\Support\Facades\Auth;
    ?>

    <!-- Popup modal Filter -->
    <div class="modal fade bd-example-modal-lg" id="topupmodal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <?php
                $user_infos = App\Models\User::find(Auth::user()->id); ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Top Up Saldo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 style="font-weight: medium; font-size:18px;">Masukkan Jumlah Top Up</h4>
                    <input type="hidden" id="saldouser" name="saldouser" value="{{$user_infos->saldo}}">
                    <form action="{{ route('user.topup') }}" method="POST">
                        @csrf
                        <br>

                        <div class="row mb-5" style="margin-top: -20px; padding:10px;">
                            <div class="container-fluid">
                                <div class="form-group">
                                    <div class="mb-3">
                                        <input type="hidden" value="{{ $user_infos->id }}" name="id">
                                        <label for="formGroupExampleInput" class="form-label">Jumlah Top Up</label>
                                        <input type="number" class="form-control" id="saldo" name="saldo" placeholder="Masukkan Jumlah Saldo">
                                    </div>
                                    <div class="mb-3">
                                        <label for="formGroupExampleInput2" class="form-label">Metode Pembayaran</label>
                                        <select name="metode" id="metode" style="width: 100%;border:solid 1px #ACB8C2;border-radius:6px;padding-left:10px;padding-right:10px;">
                                            <option value="dana">Dana</option>
                                            <option value="gopay">Gopay</option>
                                            <option value="ovo">Ovo</option>
                                            <option value="shopeepay">ShopeePay</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                </div>
                <div class="modal-footer mt-2">
                    <button type="submit" onclick="tes()" class="focus:outline-none text-blueDark w-30 mt-2 bg-orange hover:bg-orange font-bold rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:focus:ring-yellow-900" title="Top Up">Top Up Now</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Popup modal end -->

    <div class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-md">
        <h5 class="mb-2 text-xl font-medium tracking-tight text-gray-900 dark:text-white">Cash YukParkir</h5>
        <p class="font-normal text-gray-700 dark:text-gray-400">Saldo</p>
        <?php
        $user_info = App\Models\User::find(Auth::user()->id); ?>
        <?php
        $admin_saldo = App\Models\User::where('role', '=', 'admin')->value('saldo'); ?>
        <?php
        $admin_id = App\Models\User::where('role', '=', 'admin')->value('id'); ?>

        <p class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Rp {{$user_info->saldo}}</p>
    </div>
    <button type="button" data-toggle="modal" data-target="#topupmodal" class="focus:outline-none text-blueDark w-full mt-2 bg-orange hover:bg-orange font-bold rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:focus:ring-yellow-900">Top
        Up</button>

</div>
<div class="w-7/12 ml-3 bg-white border border-gray-200 rounded-2xl shadow-md max-h-70vh overflow-auto p-4">
    <p class="text-blueDark text-xl">Reservasi Aktif Parkir</p>

    @if($reservasis->count() == 0)
    <div class="text-center" style="padding: 60px;margin-bottom:50px;">
        <a href=""><i class="fas fa-exclamation-circle" style="font-size: 100px;color:#ffec58"></i></a>
        <br>
        <h5 style="margin-top: 20px;">Tidak ada reservasi aktif</h5>
    </div>
    @else

    @foreach ($reservasis as $pd)
    @if($pd->info == 'aktif' && $pd->status == 'confirmed')
    <div class="aselole block w-full p-4 bg-white border border-gray-200 rounded-lg shadow-md mt-4">
        <div class="w-full flex justify-center">
            <img src="/storage/{{ $pd->image }}" class="rounded-xl w-1/5" alt="tempat parkir">
            <div class="w-4/5 px-2">
                <p class="truncate text-lg font-medium">{{$pd->name}}</p>
                <p class="truncate">{{$pd->lokasi}}</p>
                <p class="cidate">{{$pd->checkindate}}</p>
                <p class="truncate">{{$pd->checkintime}}</p>
                <!-- <p class="truncate">{{$pd->checkintime}} - {{$pd->checkouttime}} ({{$pd->lamaparkir}} jam)</p> -->
                <p class="cotime" style="display: none;">{{$pd->checkouttime}}</p>
                <p class="citime" style="display: none;">{{$pd->checkintime}}</p>
                <p class="codate" style="display: none;">{{$pd->checkoutdate}}</p>
                <p class="truncate">Rp. {{$pd->biaya}}</p>
            </div>
        </div>
        <div class="mt-2 flex justify-between items-start">
            <div class="contain flex justify-between items-center w-100 px-20 py-3 bg-green rounded-lg mr-3">
                <p>Durasi Aktif</p>
                <p class="durasisisa" id="durasisisa"></p>
            </div>
            <input class="slotsekarang" type="hidden" id="slotsekarang" name="slotsekarang" value="{{$pd->slot}}">
            <input class="adminsaldo" type="hidden" id="adminsaldo" name="adminsaldo" value="{{$admin_saldo}}">
            <!-- <input class="total_biaya" type="ff" id="total_biaya" name="total_biaya" value="{{$pd->biaya}}"> -->
            <form action="{{ route('user.update') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="id" name="id" value="{{$pd->id}}">
                <input class="adminid" type="hidden" id="adminid" name="adminid" value="{{$admin_id}}">
                <input type="hidden" id="parkir_id" name="parkir_id" value="{{$pd->parkir_id}}">
                <input class="slot" type="hidden" id="slot" name="slot">
                <input class="saldo" type="hidden" id="saldo" name="saldo" value="{{$pd->biaya}}">
                <input class="jam" type="hidden" id="jam" name="jam">
                <input type="hidden" id="info" name="info" value="nonaktif">
                <div class="tombolselesai" style="display: block;">
                    <button type="submit" class="bg-orange text-center py-4 rounded-lg" style="cursor: pointer;width:130px">
                        Check Out
                    </button>
                </div>
                <div class="tombolbatal" style="display: none;">
                    <button type="submit" class="bg-red text-center py-3 rounded-lg" style="cursor: pointer;width:130px">
                        Batalkan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
    @endforeach
    @endif
    <script>
        function tes(){
        var saldoskrg = document.getElementById('saldouser').value;
        var inputsaldo = document.getElementById('saldo').value;

        console.log(saldoskrg);
        console.log(inputsaldo);

        var saldonow = parseInt(saldoskrg);
        var saldoskrg = parseInt(inputsaldo);

        var saldonew = (saldonow + saldoskrg);
        console.log('saldobaru'+saldonew);

        document.querySelector("[name=saldo]").value = saldonew;
    }
    </script>
    <script>
        for (var i = 0; i < document.getElementsByClassName('aselole').length; i++) {
            var y = document.getElementsByClassName('slotsekarang')[i].value;
            // var a = document.getElementsByClassName('adminsaldo')[i].value;
            // var b = document.getElementsByClassName('total_biaya')[i].value;

            var slotnow = parseInt(y);
            // var saldoadmin = parseInt(a);
            // var totalbiaya = parseInt(b);

            // var saldosekarang = saldoadmin + totalbiaya;
            var sisa_slot = (slotnow + 1);
            document.getElementsByClassName("slot")[i].value = sisa_slot;
            // document.getElementsByClassName("saldo")[i].value = saldosekarang;
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
                var waktusekarang = new Date().getTime();
                var now = new Date(checkindate + " " + checkintime).getTime();
                // var countDownDate = new Date(checkoutdate + " " + checkouttime).getTime();
                if (now <= waktusekarang) {
                    var x = setInterval(function() {
                        //Tabel Atas
                        var now = new Date().getTime();
                        var sekarangbgt = new Date(checkindate + " " + checkintime).getTime();
                        //now += 4;
                        var distance = now - sekarangbgt;
                        days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        seconds = Math.floor((distance % (1000 * 60)) / 1000);
                        if (distance < 0) {
                            clearInterval(x);
                            document.getElementsByClassName("durasisisa")[i].innerHTML = "DONE";
                            document.getElementsByClassName("tombolselesai")[i].style.display = "block";
                        } else {
                            document.getElementsByClassName("durasisisa")[i].innerHTML = hours + ":" +
                                minutes + ":" + seconds;
                        }
                        document.getElementsByClassName("jam")[i].value = hours;

                    }, 1);
                    // setInterval(function () {
                    //     var a = document.getElementsByClassName("saldo")[i].value;
                    //     var b = document.getElementsByClassName('total_biaya')[i].value;
                    //     var saldoadmin = parseInt(a);
                    //     var totalbiaya = parseInt(b);
                    //     var saldosekarang = saldoadmin + totalbiaya;
                    //     document.getElementsByClassName("saldo")[i].value = saldosekarang;
                    // }, 3600000);
                } else if (waktusekarang > now) {
                    document.getElementsByClassName("durasisisa")[i].innerHTML = "DONE";
                    document.getElementsByClassName("tombolselesai")[i].style.display = "block";
                } else if (waktusekarang < now) {
                    document.getElementsByClassName("contain")[i].style.background = "#DDDDDD";
                    document.getElementsByClassName("durasisisa")[i].innerHTML = "WAITING";
                    document.getElementsByClassName("tombolbatal")[i].style.display = "block";
                }
            }
        }

        for (var i = 0; i < <?php echo json_encode($reservasis->count()) ?>; i++) {
            var checkouttime = document.getElementsByClassName("cotime")[i].innerHTML.toString();
            var checkintime = document.getElementsByClassName("citime")[i].innerHTML.toString();
            var checkindate = document.getElementsByClassName("cidate")[i].innerHTML.toString();
            var checkoutdate = document.getElementsByClassName("codate")[i].innerHTML.toString();
            const coba = new Test();
            coba.Call(checkintime, checkouttime, checkindate, checkoutdate, i);
        }
    </script>

    @endsection