@extends('layouts.dosen.main')

@section('judul_halaman')
DETAIL MAHASISWA
@endsection

@section('keterangan_halaman')
@endsection

@section('isi')
<div class="container">
	<div class="card p-4">
		<table cellpadding="5">
			<tr>
				<td><strong>Nama Mahasiswa</strong></td><td ><strong>:</strong></td><td>{{$calon->nama}}</td>
			</tr>
			<tr>
				<td><strong>Nomer Induk Mahasiswa</strong></td><td ><strong>:</strong></td><td>{{$calon->id_mahasiswa}}</td>
			</tr>
			<tr>
				<td><strong>Judul Penelitian</strong></td><td ><strong>:</strong></td><td>{{$calon->judul_penelitian}}</td>
			</tr>
			<tr>
				<td><strong>Topik Penelitian</strong></td><td ><strong>:</strong></td><td>{{$calon->topik}}</td>
			</tr>
			<tr>
				<td><strong>Periode pengajuan</strong></td><td ><strong>:</strong></td><td>{{$calon->periode}}</td>
			</tr>
			<tr>
				<td><strong>File Resume</strong></td><td ><strong>:</strong></td>
				
				@if(isset($calon->file_syarat))
				<td>
					<div class="alert alert-success">{{$calon->nama_file}}</div>
					<form action="{{route('donlot')}}" method="POST" id="lakukanDownload">
						@csrf
						<input type="hidden" value="{{base64_encode($calon->file_syarat)}}" name="unduh">
						<input type="hidden" value="Resume Penelitian {{$calon->id_mahasiswa}}.{{$calon->ext_file}}" name="id_m">
					</form>
				</td>
				<td>
					<button style="font-size: 9px;" class="btn btn-success" id="btnDownload">Download</button>
				</td>
				@else
				<td>
					<div class="alert alert-warning alert-dismissible fade show" role="alert">
						<strong>Hasil Resume!</strong> Mahasiswa belum mengirim hasil resume.
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>		
				</td>				
				@endif
			</tr>
			<tr>
				<td><strong>Status</strong></td><td ><strong>:</strong></td>
				<td>
					@if($calon->status == 'menunggu')
					<div style="font-size: 9px;" class="alert-warning alert">Calon Mahasiswa Bimbingan</div>
					@elseif($calon->status == 'terima')
					<div style="font-size: 9px;" class="alert-success alert">Sedang Melakukan Penelitian</div>
					@elseif($calon->status == 'tolak')
					<div style="font-size: 9px;" class="alert-danger alert">Tertolak</div>
					@else
					<div style="font-size: 9px;" class="alert-primary alert">Telah Selesai Melakukan Penelitian</div>
					@endif
				</td>
			</tr>
		</table>
		<hr>
		<div>
			@if($calon->status == 'menunggu')
			<button class="btn btn-success" id="terimaDetailCalonBtn">Terima</button>
			<button class="btn btn-danger" id="tolakDetailCalonBtn" >Tolak</button>
			@elseif($calon->status == 'terima')
			<button class="btn btn-primary" id="selesaiDetailCalonBtn">Luluskan mahasiswa</button>
			@elseif($calon->status == 'tolak')
			<div style="font-size: 9px;" class="alert-primary alert">Tertolak</div>
			@else
			<div style="font-size: 9px;" class="alert-primary alert">Mahasiswa Telah Selesai Melakukan Penelitian</div>
			@endif
			
		</div>
		
	</div>
</div>


<form action="{{route('updateStatusCalon')}}" method="POST" id="subDetailCalon">
	@csrf	
	<input class="form-control" type="hidden" value="{{$calon->id_calon}}" name="id_calon">
	<!-- <input type="hidden" name="id_dosenn" value="{{$data->id_dosen}}"> -->
	<input type="hidden" name="stat" id="statIdDetailCalon" value="">	
</form>

@endsection