@extends('layouts.main')

@section('container')
<div class="container shadow rounded-3 col-lg-4 p-3 mt-5">
    @if (session()->has('loginError'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <small>{{ session('loginError') }}</small>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="justify-content-start text-center">
        <img src={{ asset('assets/logo-dsdm.png') }} width="150" class="" alt="">
        <div class="d-flex flex-column my-3 gap-1">
            <h1 class="card-title display-6">NADINE</h1>
            <p class="m-0">Perencanaan Dosen dan Tendik<br> Universitas Diponegoro</p>
            {{-- <p class="m-0">Silakan masuk untuk melanjutkan</p> --}}
        </div>
        
    </div>
    

    <form action="" method="POST" class="d-flex flex-column mb-1">
        @csrf
        <div class="form-floating mb-3">
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="name@example.com" name="email" value="{{ old('email') }}">
            <label for="email" >Email address</label>
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-floating mb-3">
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password" name="password" >
            <label for="password">Password</label>
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary header1 ">Masuk</button>
    </form>
    {{-- <small class="text-center">Belum Memiliki Akun? <a href="">Daftar disini</a>.</small> --}}
</div>
    {{-- <div class="bg-light"> --}}
        {{-- <div class="p-5"> --}}
        </div>

    </div>
    
@endsection
