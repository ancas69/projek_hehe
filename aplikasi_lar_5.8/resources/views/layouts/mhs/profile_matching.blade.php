@extends('layouts.mhs.main')
@section('judul_halaman')
SPK PROFILE MATCHING
@endsection

@section('keterangan_halaman')
<div class="alert alert-warning">
	Pada halaman ini user dapat melakukan pencarian dosen pembimbing penelitian dengan menggunakan algoritma pendukung keputusan profile matching.
</div>
@endsection
@section('isi')

<!-- <div class="row justify-content-center">
	<a style=" text-align: center;" href="{{route('pembobotan')}}" class="@yield('pb')  col-lg-2 s1 ">
		<p >prioritas kriteria</p> 
	</a>
	<i class="material-icons" style="font-size:36px; transform: translateY(20%); z-index: 1;">arrow_forward</i>
	<a style=" text-align: center;" href="{{route('bobotsubKriteria')}}" class="@yield('bk')  col-lg-2 s2 ">
		<p >profile tujuan</p> 
	</a>
	<i class="material-icons" style="font-size:36px; transform: translateY(20%); z-index: 1;">arrow_forward</i>
	<a style=" text-align: center;" href="{{route('peta')}}" class="@yield('pt')  col-lg-2 s3 ">
		<p >pemetaan gap</p> 
	</a>
	<i class="material-icons" style="font-size:36px; transform: translateY(20%); z-index: 1;">arrow_forward</i>
	<a style=" text-align: center;" href="{{route('perhitunganTotal')}}" class="@yield('tot')  col-lg-2 s4 ">
		<p >perhitungan NCF & NSF</p> 
	</a>
	<i class="material-icons" style="font-size:36px; transform: translateY(20%); z-index: 1;">arrow_forward</i>
	<a style=" text-align: center;" href="{{route('hasilAkhir')}}" id="hasil_akhir" class="col-lg-2 @yield('ha')  s5 ">
		<p >hasil</p>
	</a>
</div>
<HR> -->
<div class="col justify-content-center card p-4">
	<H3 class="col-12">@yield('judul_step')</H3>
	<hr>
	<div>@yield('proses_step')</div>
</div>





@endsection