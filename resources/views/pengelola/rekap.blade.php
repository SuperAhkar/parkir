@extends('user.template')
@section('title', 'Dashboard - Riwayat')

@section('content')

<style>
    .btn-edit {
        color: #183153;
        font-weight: 400;
        width: 170px;
        font-size: 16px;
        border-radius: 10px;
        background-color: #D98829;
    }
</style>
<div class="w-10/12 ml-3 ">
    <div class="row mb-2 overflow-auto" style="justify-content: space-between;">
        <div class="p-3 col-sm-4">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-md p-4">
                <p class="text-blueDark text-xl">Total Pendapatan</p>

                @if($rekap->count() == 0)
                <p class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Rp 0</p>
                @else
                @foreach ($rekap as $pd)
                <?php $pengelola_info = App\Models\User::find($pd->parkir_id); ?>
                @endforeach
                <p class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Rp {{$pengelola_info->saldo}}</p>
                @endif

            </div>
        </div>
        <div class="p-3 col-sm-4">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-md p-4">
                <p class="text-blueDark text-xl">Pendapatan Bulan Lalu</p>

                @if($rekap->count() == 0)
                <p class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Rp 0</p>
                @else
                @foreach ($rekap as $pd)
                <?php $pengelola_info = App\Models\User::find($pd->parkir_id); ?>
                @endforeach
                <p class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Rp {{$pengelola_info->saldo}}</p>
                @endif

            </div>
        </div>
        <div class="p-3 col-sm-4">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-md p-4">
                <p class="text-blueDark text-xl">Pendapatan Akumulatif</p>

                @if($rekap->count() == 0)
                <p class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Rp 0</p>
                @else
                @foreach ($rekap as $pd)
                <?php $pengelola_info = App\Models\User::find($pd->parkir_id); ?>
                @endforeach
                <p class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Rp {{$pengelola_info->saldo}}</p>
                @endif

            </div>
        </div>
    </div>

    <div class="row p-4">
        <div class="w-8/12 bg-white border border-gray-200 rounded-2xl shadow-md overflow-auto p-4">
            <p class="text-blueDark text-xl">Riwayat Pelanggan</p>
            <div class="block w-full p-3 max-h-80vh mt-4">
                <table class="table">
                    <thead>
                    </thead>
                    <tbody>
                        @if($rekap->count() == 0)
                        <div class="text-center" style="padding: 60px;margin-bottom:50px;">
                            <a href=""><i class="fas fa-exclamation-circle" style="font-size: 100px;color:#ffec58"></i></a>
                            <br>
                            <h5 style="margin-top: 20px;">Tidak ada riwayat</h5>
                        </div>
                        @else

                        @foreach ($rekap as $pd)
                        @if($pd->status == 'confirmed' && $pd->info == 'nonaktif' )
                        <?php $user_info = App\Models\User::find($pd->user_id); ?>
                        <tr id="sid{{ $pd->id }}" class="bg-white border border-gray-200 rounded-2xl shadow-md overflow-auto">
                            <td><i class="fa-solid fa-user" style="font-size: 30px;padding:9px;"></i><br>
                            </td>
                            <td>{{ $user_info->name }} <br>
                                <p style="font-size: 13px;">{{$pd->checkoutdate}}</p>
                            </td>
                            <td>{{$pd->checkintime}}-{{$pd->checkouttime}} ({{$pd->lamaparkir}} jam)
                                <br>Rp {{$pd->biayatotal}}
                            </td>
                        </tr>

                        @endif

                        @endforeach

                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="w-3/12 ml-4">
            <div class="block max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow-md">
                <p class="font-normal text-gray-700 dark:text-gray-400">Saldo</p>

                @if($rekap->count() == 0)
                <p class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Rp 0</p>
                @else
                @foreach ($rekap as $pd)
                <?php $pengelola_info = App\Models\User::find($pd->parkir_id); ?>
                @endforeach
                <p class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Rp {{$pengelola_info->saldo}}</p>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection