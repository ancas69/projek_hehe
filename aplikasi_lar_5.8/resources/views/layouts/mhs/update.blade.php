@extends('layouts.mhs.main')
@section('judul_halaman')
UPDATE IDENTITAS
@endsection

@section('keterangan_halaman')
<div class="alert alert-warning">
	Anda dapat melakukan update terhadap nama atau nim anda.
</div>
@endsection
@section('isi')
<div class="row">
<div class="col-lg-6 card p-4">
<form method="POST" action="/mhs/updateSave">
	@csrf
	<div class="form">
		<label class="">NIM Mahasiswa</label>
		@if($cek_data==0)
		<input type="number" required="" name="nim" value="{{$data->id_mahasiswa}}" class="col-lg-12 form-control @error('nim') is-invalid @enderror">
		@else
		<input readonly="" type="number" name="nim" value="{{$data->id_mahasiswa}}" class="col-lg-12 form-control @error('nim') is-invalid @enderror">
		<span class="" role="alert">
            <strong style="">hapus data relasi dosen untuk mengubah</strong>
        </span>
		@endif
		@error('nim')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
	</div>
	<div class="form mt-2">
		<label class="">Nama Mahasiswa</label>
		<input type="text" required="" name="nama" value="{{$data->nama}}" class=" col-lg-12 form-control @error('nama') is-invalid @enderror">
		@error('nama')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
	</div>
	<div class="form mt-4">
		<input type="submit" name="" value="simpan" class="btn btn-primary"> 
		<input type="reset" name="" class="btn btn-danger">
	</div>
</form>
</div>
</div>
@endsection
