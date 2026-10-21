@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Link Verifikasi telah dikirim, silahkan cek E-mail untuk melanjutkan.') }}
                    {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
                    <br>
                    <!-- <a href="{{route('logout')}}" class="btn btn-warning m-4" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">Kembali</a> -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
