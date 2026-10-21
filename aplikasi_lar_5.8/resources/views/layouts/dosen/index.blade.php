@extends('layouts.dosen.main')
@section('judul_halaman')
HALAMAN UTAMA
@endsection

@section('keterangan_halaman')
<div class="alert alert-success">
	Selamat datang <strong>{{$data->nama}}</strong>.
	SPK DOSBING adalah aplikasi pendukung keputusan yang membantu mahasiswa untuk menentukan dosen pembimbing penelitian.
	Lakukan pengisian data profile pada menu <a href="{{route('dsnUpdate')}}">Pengaturan Profile</a> agar sistem dapat melakukan perhitungan terhadap profile dosen.
</div>

@if($data->periode_pengajuan == "tutup")
<div class="alert alert-danger">
	Status pengajuan penelitian anda sedang ditutup. hubungi admin untuk membuka pengajuan penelitian.
</div>
@endif
@endsection
@section('isi')

<div class="row justify-content-center">
	<a href="{{route('daftarMahasiswa',['id' => 'Terima' ])}}" class="col-sm-4 m-1 p-4 bg-light " style="color: #000; border-radius: 5px; height: 170px;">
		<div><i style="font-size: 100px;" class="tombol_home fas fa-file-signature"></i></div>
		<div style="transform: translate(101,100%);"><strong>Sedang Penelitian</strong></div>
		<p style="font-size: 50px; transform: translate(80%,-180%);">{{$count_stat['terima']}}</p>
	</a>
	<a href="{{route('daftarMahasiswa',['id' => 'Tolak' ])}}" class="col-sm-4  m-1 p-4 bg-light " style="color: #000; border-radius: 5px; height: 170px;">
		<div><i style="font-size: 100px;" class="tombol_home fas fa-ban"></i></div>
		<div><strong>Tertolak</strong></div>
		<p style="font-size: 50px; transform: translate(80%,-180%)">{{$count_stat['tolak']}}</p>
	</a>
	<a href="{{route('daftarMahasiswa',['id' => 'Selesai' ])}}" class="col-sm-4  m-1 p-4 bg-light " style="color: #000; border-radius: 5px; height: 170px;">
		<div><i style="font-size: 100px;" class="tombol_home fas fa-trophy"></i></div>
		<div><strong>Selesai Penelitian</strong></div>
		<p style="font-size: 50px; transform: translate(80%,-180%)">{{$count_stat['selesai']}}</p>
	</a>
	<a href="{{route('daftarMahasiswa',['id' => 'Menunggu' ])}}" class="col-sm-4  m-1 p-4 bg-light " style="color: #000; border-radius: 5px; height: 170px;">
		<div><i style="font-size: 100px;" class="tombol_home fab fa-slideshare"></i></div>
		<div><strong>Mengajukan Penelitian</strong></div>
		<p style="font-size: 50px; transform: translate(80%,-180%)">{{$count_stat['menunggu']}}</p>
	</a>
</div>


<div class="row justify-content-center">
</div>
@endsection