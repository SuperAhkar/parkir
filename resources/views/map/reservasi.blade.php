@extends('user.template')
@section('title', 'Reservasi Parkir')

@section('content')

<style>
    .btn-linkacc {
        color: #838383;
        font-weight: 400;
        width: 180px;
        font-size: 16px;
        border: solid 1px #ACB8C2;
        border-radius: 10px;
    }

    .payment-logo {
        justify-content: center;
        text-align: center;
        display: block;
        align-items: center;
        object-fit: fill;
    }

    .btn-edit {
        color: #183153;
        font-weight: 400;
        width: 230px;
        font-size: 16px;
        border-radius: 10px;
        background-color: #D98829;
    }

    .label {
        color: #41445E;
    }
</style>
<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth; ?>
<div class="w-10/12 ml-3 bg-white border border-gray-200 rounded-2xl shadow-md max-h-80vh overflow-auto p-4">
    <i style="cursor:pointer;width:50px;height:50px;padding:10px;" class="text-center col-sm-1 fas fa-chevron-left self-start text-xl bg-white border border-gray-200 rounded-lg shadow-md mb-2" onclick="history.back()"></i>
    <a href="{{ route('user.homepage') }}"><i style="cursor:pointer;color:black;width:50px;height:50px;padding:10px;" class="text-center col-sm-1 fas fa-home text-xl self-start bg-white border border-gray-200 rounded-lg shadow-md mb-2"></i></a>

    <div class="row mt-3">
        <div class="col-sm-6">
            <img class="rounded-t-lg" src="/storage/{{ $data->image }}" alt="" />
            <h5 class="text-2xl mt-4 font-regular tracking-tight text-gray-900 dark:text-white">{{$data['name']}}</h5>
            <p class="my-2 text-base text-blueDarker">{{$data['lokasi']}}</p>
        </div>
        <div class="col-sm-6">
            @if ($errors->any())
            <div class="alert-danger" style="margin-bottom:30px;">
                {{ $errors->first() }}
            </div>
            @endif
            <?php
            $user_info = User::find(Auth::user()->id);
            ?>
            <input type="hidden" name="saldo_sekarang" id="saldo_sekarang" value="{{$user_info->saldo}}">
            <input type="hidden" id="harga" name="harga">

            <form action="/reservasi/{{$data['id']}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3 row">
                    <label class="col-sm-4 col-form-label" for="exampleFormControlInput1">Nomor Kendaraan<span style="color:#FF0000;">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Name" name="nokendaraan" id="nokendaraan">
                    </div>
                </div>
                <input type="hidden" name="saldo" id="saldo">
                <div class="form-group mb-3 row">
                    <label class="col-sm-4 col-form-label" for="exampleFormControlInput1">Tipe Kendaraan<span style="color:#FF0000;">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Tipe Kendaraan" name="tipekendaraan" id="tipekendaraan">
                    </div>
                </div>
                <div class="form-group mb-3 row">
                    <label class="col-sm-4 col-form-label" for="exampleFormControlInput1">Check-in<span style="color:#FF0000;">*</span></label>
                    <div class="col-sm-4">
                        <input type="date" class="form-control" placeholder="Tanggal Checkin" name="checkindate" id="checkindate" required>
                    </div>
                    <div class="col-sm-4">
                        <input type="time" class="form-control" placeholder="Waktu Checkin" name="checkintime" id="checkintime">
                    </div>
                </div>
                <!-- <div class="form-group mb-3 row">
                    <label class="col-sm-4 col-form-label" for="exampleFormControlInput1">Check-out<span style="color:#FF0000;">*</span></label>
                    <div class="col-sm-4">
                        <input type="date" class="form-control" placeholder="Tanggal Checkout" name="checkoutdate" id="checkoutdate" required>
                    </div>
                    <div class="col-sm-4">
                        <input type="time" class="form-control" placeholder="Waktu Checkout" name="checkouttime" id="checkouttime">
                    </div>
                </div> -->
                <hr size="1" color="#000" style="width: 100%;">
                <!-- <div class="form-group mb-3 row mt-2">
                    <label class="col-sm-4" for="exampleFormControlInput1">Lama parkir</label>
                    <input type="hidden" class="form-control" id="lamaparkir" name="lamaparkir">
                    <div class="col-sm-8">
                        <p id="durasiparkir">0 Jam</p>
                    </div>
                </div> -->
                <div class="form-group mb-3 row">
                    <label class="col-sm-4" for="exampleFormControlInput1">Biaya per jam</label>
                    <div class="col-sm-8">
                        <p>Rp {{$data['biaya']}}</p>
                    </div>
                </div>
                <!-- <hr size="1" color="#000" style="width: 100%;">
                <div class="form-group mb-3 row mt-2">
                    <label class="col-sm-4" for="exampleFormControlInput1">Biaya total</label>
                    <input type="hidden" class="form-control" id="biayatotal" name="biayatotal">
                    <div class="col-sm-8 totalbiaya">
                        <p id="totalbiaya">Rp 0</p>
                    </div>
                </div> -->
                <hr size="1" color="#000" style="width: 100%;">

                <div class="form-group mb-3 row mt-3">
                    <label class="col-sm-4" for="exampleFormControlInput1">Metode Pembayaran<span style="color:#FF0000;">*</span></label>
                    <div class="col-sm-8 d-flex">
                        <select name="metodebayar" style="width: 100%;border:solid 1px #ACB8C2;border-radius:6px;padding-left:10px;padding-right:10px;">
                            <option value="dana">Dana</option>
                            <option value="gopay">Gopay</option>
                            <option value="ovo">Ovo</option>
                            <option value="shopeepay">ShopeePay</option>
                        </select>
                    </div>
                </div>
                <div class="form-group mb-3 row">
                    <div class="col-sm-12 d-flex">
                        <button type="submit" class="btn btn-edit w-100">Reservasi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var diff = 0;
    var totalbiaya = 0;

    $("#checkintime").change(function() {
        if (document.getElementById('checkouttime').value == undefined) {
            document.getElementById("lamaparkir").value = null;
        }
        var date1 = document.getElementById("checkintime").value;
        var date2 = document.getElementById("checkouttime").value;
        var latInput = document.querySelector("[name=lamaparkir]");

        var myArray1 = date1.split(':');
        var hr1 = parseInt(myArray1[0]);

        var myArray2 = date2.split(':');
        var hr2 = parseInt(myArray2[0]);

        diff = (hr2 - hr1).toString();

        document.getElementById("lamaparkir").value = diff;
    });

    $("#checkouttime").change(function() {
        if (document.getElementById('checkintime').value == undefined) {
            document.getElementById("lamaparkir").value = null;
        }
        var date1 = document.getElementById("checkintime").value;
        var date2 = document.getElementById("checkouttime").value;
        var latInput = document.querySelector("[name=lamaparkir]");

        var myArray1 = date1.split(':');
        var hr1 = parseInt(myArray1[0]);

        var myArray2 = date2.split(':');
        var hr2 = parseInt(myArray2[0]);

        diff = (hr2 - hr1).toString();

        document.getElementById("lamaparkir").value = diff;

        // Total Biaya 
        var jsvar = <?php echo json_encode($data['biaya']) ?>;
        totalbiaya = (jsvar * diff).toString();
        document.getElementById("biayatotal").value = totalbiaya;
        document.getElementById("harga").value = totalbiaya;
        console.log(totalbiaya);
        console.log(typeof totalbiaya);

        document.getElementById("durasiparkir").innerHTML = diff + ' Jam';
        document.getElementById("totalbiaya").innerHTML = 'Rp ' + totalbiaya;

        var saldosekarang = document.getElementById('saldo_sekarang').value;
        var harga = document.getElementById('harga').value;

        var saldonow = parseInt(saldosekarang);
        var duitbaru = parseInt(harga);

        var total_saldo = (saldonow - duitbaru).toString();

        document.getElementById("saldo").value = total_saldo;
        console.log(document.getElementById("saldo").value);

    });
</script>

@endsection