@extends('user.template')
@section('title', 'Dashboard - Profile')

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
</style>

<div class="w-10/12 ml-3 bg-white border border-gray-200 rounded-2xl shadow-md max-h-80vh overflow-auto p-4">
    <div class="mt-4">
        <form action="{{ route('admin.updateprofile') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div>
                <input type="hidden" value="{{ $user->id }}" name="id">
            </div>
            <div class="form-group mb-3 row">
                <label class="col-sm-2 col-form-label" for="exampleFormControlInput1">Nama</label>
                <div class="col-sm-10">
                    <input value="{{ $user->name }}" type="text" class="form-control" placeholder="Name" name="name">
                </div>
            </div>

            <div class="form-group mb-3 row">
                <label class="col-sm-2 col-form-label" for="exampleFormControlInput1">E-mail</label>
                <div class="col-sm-10">
                    <input value="{{ $user->email }}" type="text" class="form-control" placeholder="Email" name="email">
                </div>
            </div>

            <div class="form-group mb-3 row">
                <label class="col-sm-2 col-form-label" for="exampleFormControlInput1">Password</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" placeholder="Password" name="password">
                </div>
            </div>

            <div class="form-group mt-5" style="float: right;">
                <button type="submit" class="btn btn-edit mx-2">Modifikasi</button>
            </div>

        </form>
    </div>

</div>
@endsection