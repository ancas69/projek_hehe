@extends('layouts.mhs.main')
@section('judul_halaman')
HALAMAN UTAMA
@endsection

@section('keterangan_halaman')
<div class="alert alert-warning">
	Selamat datang <strong>{{$data->nama}}</strong>.
	SPK DOSBING adalah aplikasi pendukung keputusan yang membantu anda untuk menentukan dosen pembimbing penelitian.
	lakukan proses pencarian pada menu <a href="{{route('pembobotan')}}">SPK Profile Matcing</a>
</div>
@endsection
@section('isi')
<div data-spy="scroll" data-target=".targett" data-offset="50">

<!-- 	<div class="targett p-4 row">
		<div class="col m-1 card p-4">
			<div class="p-4 bg-outline-secondary card" style="transform: translateY(-90%); text-align: center;">Petunjuk Penggunaan Aplikasi</div>
			<ol>
				<li>Pergi ke menu SPK Dosbing</li>
				<li>Pilih menu Pembobotan , dan isikan bobot prioritas dan kriteria anda</li>
				<li>Kemudian klik tombol "proses" untuk memulai</li>
				<li>Setelah meng-klik tombol "proses" akan tampil halaman menu pemetaan gap, skroll kebawah dan klik tombol "hasil akir" untuk melihat hasil</li>
				<li>Setelah meng-klik tombol "perhitungan akhir" akan muncul halaman perhitungan akhir , klik tombol "lihat rekomendasi" untuk melihat hasil rekomendasi</li>
				<li>Setelah meng-klik tombol "lihat rekomendasi" akan muncul pop up modal yang berisikan list rekomendasi dosen pembimbing, klik tombol tambah pada kolom aksi untuk menambah list dosen</li>
			</ol>
		</div>
		<div class="col m-1 p-4">
			<a href="#daftar_dosen" class="">lihat daftar pilihan</a>
		</div>
		
	</div> -->


	<div id="daftar_dosen">
	@if(!$calon->isEmpty())
	<div class="card p-4 mt-4">
		<p>Daftar list calon dosen pembimbing pilihan</p>

		<table class="table table-secondary" style="margin-bottom: 100px; text-align: center;">
			<thead class="thead-dark">
				<tr>
					<th style="vertical-align: middle;" rowspan="2">No</th>
					<th style="vertical-align: middle;" rowspan="2">Dosen</th>
					<th style="vertical-align: middle;" rowspan="2">NIY</th>
					<th style="vertical-align: middle;" rowspan="2">Status</th>
					<th style="vertical-align: middle;" rowspan="2" colspan="2">Aksi</th>
				</tr>
			</thead>
			<tbody>
				@foreach($calon as $index => $key)
				
				<tr>
					<td style="vertical-align: middle;" >{{$index+1}}</td>
					<td style="vertical-align: middle;" >{{$key->nama}}</td>
					<td style="vertical-align: middle;" >{{$key->id_dosen}}</td>
					<td style="vertical-align: middle;" >
						@if($key->status == "menunggu")
							<div style="background-color: grey; color: #fff; border-radius: 5px; padding: 9px; font-size: 11px;">Menunggu</div>
						@elseif($key->status == "tolak")
							<div style="background-color: red; color: #fff; border-radius: 5px; padding: 9px; font-size: 11px;">Tertolak</div>
						@else
							<div style="background-color: green; color: #fff; border-radius: 5px; padding: 9px; font-size: 11px;">Diterima</div>
						@endif
					</td>

					@if($key->status == "terima")
					<td style="vertical-align: middle;" >
						<!-- @if(isset($key->link_group))
							<div class="" style=""><a href="{{$key->link_group}}">link grup, silahkan bergabung</a></div>
						@else -->
						<div class="" style="background-color: green; color: #fff; border-radius: 5px; padding: 9px; font-size: 11px;">
							silahkan menghubungi dosen untuk proses lebih lanjut
						</div>
						<!-- @endif -->
					</td>
					@endif

					
					@if($key->status != "terima")
					<td style="vertical-align: middle;" >
						@if(isset($key->file_syarat))
						<a type="button" style="color: #000; font-size: 10px;"  class="btn btn-warning" href="{{route('uploadData' , ['id' => $key->id_calon ]) }}">
						  Update Data
						</a>
						@else
						<a type="button" style="color: #000; font-size: 10px;"  class="btn btn-primary" href="{{route('uploadData' , ['id' => $key->id_calon ]) }}">
						  Upload Data
						</a>
						@endif
						<!-- Modal -->
							
					</td>
					@endif
					
					<td style="vertical-align: middle; " >
						@if($key->status != "menunggu")@endif
						<form action="{{route('hapusCalon')}}" method="post">
							@csrf
							<input type="hidden" value="{{$key->id_calon}}" name="id_calon">
							<button type="submit" class="btn btn-danger">
								<i class="fas fa-user-minus"></i>
							</button>
						</form>
					</td>
				</tr>
				
				@if(!isset($key->file_syarat))
				<tr>
					<td colspan="6">
						<div class="alert-danger alert">Anda belum mengirimkan file resume!!, kirimkan file resune agar dosen dapat melakukan seleksi</div>
					</td>
				</tr>
				@endif

				@if( $key->status == 'menunggu' && $key->periode != $key->periode_pengajuan && $key->periode_pengajuan != 'tutup')
				<tr>
					<td colspan="6">
						<div class="alert-danger alert">Dosen pilihan melakukan pembaharuan pada periode pendaftaran, silahkan update periode pengajuan pada upload data untuk mendaftar ulang.</div>
						
					</td>
				</tr>
				@endif
		
				@endforeach
			</tbody>
		</table>

		
	</div>
	@else
	<DIV class="card mt-4 p-4">
		<p style="text-align: center;">belum ada calon dosen pembimbing yang ditambahkan</p>
	</DIV>

		


	@endif
	</div>
</div>




@endsection