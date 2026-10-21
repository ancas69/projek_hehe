@extends('layouts.mhs.main')
@section('judul_halaman')
UPLOAD DATA RESUME 
@endsection
@section('isi')
<div class="container">
	<hr>
	<hr>
	<h3>Dosen yang diajukan</h3>
	<hr>
	<table cellpadding="10">
		<tr>
			<td><strong>Nama Dosen</strong></td>
			<td><strong>:</strong></td>
			<td>{{$data_calon->nama}}</td>
		</tr>
		<tr>
			<td><strong>ID Dosen</strong></td>
			<td><strong>:</strong></td>
			<td>{{$data_calon->id_dosen}}</td>
		</tr>
		<tr>
			<td><strong>Topik penelitian</strong></td>
			<td><strong>:</strong></td>
			<td>
				@foreach($nama_tpk as $topik)
					<span class="m-1  alert-success">{{$topik}}</span>
				@endforeach

				
			</td>
		</tr>
		<tr>
			<td><strong>Periode Pengajuan</strong></td>
			<td><strong>:</strong></td>
			<td>{{$data_calon->periode}}</td>
			@if($data_calon->periode != $data_calon->periode_pengajuan && $data_calon->status == 'menunggu' && $data_calon->periode_pengajuan != 'tutup' )
			<td>
				<a href="javascript:void(0)" id="btnPerbaharuiPengajuan" class="btn btn-success">Perbaharui Pengajuan</a>
			</td>
			@endif
		</tr>
	</table>
	<hr>
	<<hr>
	<h3>Data Penelitian</h3>
	<hr>
	<!-- <form enctype="multipart/form-data" id="upload_resume_form" name="upload_resume_form"> -->
	<form enctype="multipart/form-data" action="{{route('tambahFile')}}"  method="post">
	 <!-- class="auto_sub" -->
		@csrf
		<table cellpadding="10">
			<tr>
				<td><strong>Judul Penelitian yang diajukan</strong></td>
				<td><strong>:</strong></td>
				<td colspan="2">
					<input type="text" required="" class="form-control @error('judul_penelitian') is-invalid @enderror" name="judul_penelitian" value="{{$data_calon->judul_penelitian ?? ''}}" placeholder="Masukkan Judul Penelitian disini . . .">
					@error('judul_penelitian')
			            <span class="invalid-feedback" role="alert">
			                <strong>{{ $message }}</strong>
			            </span>
			        @enderror
				</td>
			</tr>
			<tr>
				<td><strong>Topik Penelitian</strong></td>
				<td><strong>:</strong></td>
				<td colspan="2" class="pilihan_topik">
					<input type="hidden" id="topik_mhs" value="{{$data_calon->topik}}">
					
					<select required="" name="topik" class="form-control">
						<option value=""></option>
					@foreach($nama_tpk as $topik)
						<option value="{{$topik}}">{{$topik}}</option>
					@endforeach
					</select>
				</td>
			</tr>
			<tr>
				<td><strong>File Penelitian (pdf, size < 1Mb)</strong></td>
				<td><strong>:</strong></td>
				<td>

					@if(isset($data_calon->file_syarat))

					<a style="font-size: 9px;" href="javascript:void(0)" id="btndownloadFileCalonMhs" class=" btn btn-success m-1" onclick="alert('server akan membutuhkan sedikit waktu untuk menyiapkan file, harap sabar')">Download File</a>
						@if($data_calon->status == "menunggu")
						<a style="font-size: 9px;" href="javascript:void(0)" id="btnhapusCalonMhs" class=" btn btn-danger m-1" >Hapus File</a>
						@endif
					@endif 
				</td>
				<td>
					@if(isset($data_calon->file_syarat))
						<input type="file" id="form_input_file" name="file_data" class="form-control @error('file_data') is-invalid @enderror" accept="application/pdf">  
					@else
						<input type="file" required="" id="form_input_file" name="file_data" class="form-control @error('file_data') is-invalid @enderror" accept="application/pdf">  
					@endif
					@error('file_data')
			            <span class="invalid-feedback " role="alert">
			                <strong>{{ $message }}</strong>
			            </span>
			        @enderror
					<input  type="hidden" value="{{$data_calon->id_calon}}" name="id_calon">
				</td>
			</tr>
			<tr>
				<td>
					<progress style="display: none;" value="0" max="100" id="progres_donlot_file">
				</td>
			</tr>
		</table>
		<hr>
		<div>
			<input type="submit" class="btn btn-primary" value="Simpan" onclick="alert('server akan membutuhkan sedikit waktu untuk mengupload file, harap sabar')">
			<input type="reset" class="btn btn-danger">
		</div>
	</form>

</div>

<form  action="{{route('donlot')}}" method="post" id="formdownloadFileCalonMhs">
	@csrf
	<input type="hidden" value="{{base64_encode($data_calon->file_syarat)}}" name="unduh">
	<!-- <input type="hidden" value="{{$data_calon->file_syarat}}" name="unduh"> -->
	<input type="hidden" value="{{$data_calon->id_mahasiswa}}.{{$data_calon->ext_file}}" name="id_m">
</form>	

<form  action="{{route('hapus')}}" method="POST" id="formhapusCalonMhs">
	@csrf
	<input type="hidden" value="{{$data_calon->id_calon}}" name="id_calon">		
</form>		

<form  action="{{route('perbaharuiPengajuan')}}" method="POST" id="formPerbaharuiPengajuan">
	@csrf
	<input type="hidden" value="{{$data_calon->id_calon}}" name="id_calon">		
	<input type="hidden" value="{{$data_calon->periode_pengajuan}}" name="periode">	
</form>			
@endsection