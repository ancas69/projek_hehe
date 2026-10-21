<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ URL::asset('template/css/bootstrap.min.css') }}">
	<title>SPK DOSBING</title>
</head>
<body>

<div class="row container justify-content-center" style="margin-top: 5%;">
<div class="col-lg-6">
<H3 class="mb-4">BUAT IDENTITAS</H3>
<form method="POST" action="/mhs/save">
	@csrf
	<div class="form">
		<label class="">Nomer Induk Mahasiswa</label>
		<input type="number" required="" maxlength="10" name="nim" class="col-lg-12 form-control @error('nim') is-invalid @enderror">
		@error('nim')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
	</div>
	<div class="form mt-2">
		<label class="">Nama Mahasiswa</label>
		<input type="text" required="" name="nama" class=" col-lg-12 form-control @error('nama') is-invalid @enderror">
		@error('nama')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
	</div>
	<div class="form mt-4">
		<input type="submit" name="" class="btn btn-primary"> 
		<input type="reset" name="" class="btn btn-danger">
	</div>
</form>
</div>
</div>
</body>
</html>



