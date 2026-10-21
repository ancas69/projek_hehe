
@section('isi')

<div class="row justify-content-center" style="margin-top: 5%;">
<div class="col-12 container">
	<div class="card p-4 m-1">
	@if($level == 'mahasiswa')
		<H3 class="mb-4">TENTUKAN BOBOT PRIORITAS</H3>
	@endif

	@if(!isset($dps->id_user))
	 <form method="POST" action="{{route('saveBobot')}} ">
	@else
	 <form method="POST" action="{{route('saveEditBobot')}} ">
	@endif
		@csrf
		@if($level == 'mahasiswa')
			<div class="form-group row">
		    	<label for="" class="col-sm-7 col-form-label">SISTEM CERDAS</label>
		    	<div class="col-sm-5">
					<input type="number" name="sc" id="sc" value="{{ $dbobot->sistem_cerdas ?? '' }}" class="col-lg-12 form-control @error('sc') is-invalid @enderror">
				</div>
				@error('sc')
		            <span class="invalid-feedback" role="alert">
		                <strong>{{ $message }}</strong>
		            </span>
		        @enderror
			</div>
			<div class="form-group row">
				<label class="col-sm-7 col-form-label">RELATA</label>
				<div class="col-sm-5">
					<input type="number" name="rpl" id="rpl" value="{{ $dbobot->relata ?? '' }}" class=" col-lg-12 form-control @error('rpl') is-invalid @enderror">
				</div>
				@error('rpl')
		            <span class="invalid-feedback" role="alert">
		                <strong>{{ $message }}</strong>
		            </span>
		        @enderror
			</div>
			<div class="form-group row">
				<label class="col-sm-7 col-form-label">PENDUKUNG</label>
				<div class="col-sm-5">
					<input type="number" name="sup" id="sup" value="{{ $dbobot->pendukung ?? '' }}" class=" col-lg-12 form-control @error('sup') is-invalid @enderror">
				</div>
				@error('sup')
		            <span class="invalid-feedback" role="alert">
		                <strong>{{ $message }}</strong>
		            </span>
		        @enderror
			</div>
			<input type="hidden" name="id_user" id="id_mahasiswa" value="{{$id_}}">
		@endif
	</div>
	<div class="row justify-content-center">
		<div class="col-lg card p-4 m-1">
			<H3 class="mb-4 mt-4">PROFILE SISTEM CERDAS</H3>
			<div class="form-group row">
				<label class="col-sm-7 col-form-label">SISTEM PENDUKUNG KEPUTUSAN</label>
				<div class="col-sm-5">
					<input type="range" min="0" max="4" name="spk" id="spk" value="{{ $dps->spk ?? 0 }}" class=" col-lg-12 form-control @error('spk') is-invalid @enderror" onchange="rangeVal('#spk','#spk-cur')"><input style="border: hidden;" readonly id="spk-cur">
				</div>
				@error('spk')
		            <span class="invalid-feedback" role="alert">
		                <strong>{{ $message }}</strong>
		            </span>
		        @enderror
			</div>
			<div class="form-group row">
				<label class="col-sm-7 col-form-label">SISTEM PAKAR</label>
				<div class="col-sm-5">
					<input type="range" min="0" max="4" name="pakar" id="pakar" value="{{ $dps->pakar ?? 0 }}" class=" col-lg-12 form-control @error('pakar') is-invalid @enderror" onchange="rangeVal('#pakar','#pakar-cur')"><input style="border: hidden;" readonly id="pakar-cur">
				</div>
				@error('pakar')
		            <span class="invalid-feedback" role="alert">
		                <strong>{{ $message }}</strong>
		            </span>
		        @enderror
			</div>
			<div class="form-group row">
				<label class="col-sm-7 col-form-label">DATA MINING</label>
				<div class="col-sm-5">
					<input type="range" min="0" max="4" name="dm" id="dm" value="{{ $dps->dm ?? 0 }}" class=" col-lg-12 form-control @error('dm') is-invalid @enderror" onchange="rangeVal('#dm','#dm-cur')"><input style="border: hidden;" readonly id="dm-cur">
				</div>
				@error('dm')
		            <span class="invalid-feedback" role="alert">
		                <strong>{{ $message }}</strong>
		            </span>
		        @enderror
			</div>
			<div class="form-group row">
				<label class="col-sm-7 col-form-label">SISTEM TEMU BALIK INFORMASI</label>
				<div class="col-sm-5">
					<input type="range" min="0" max="4" name="stbi" id="stbi" value="{{$dps->stbi ?? 0}}" class=" col-lg-12 form-control @error('stbi') is-invalid @enderror" onchange="rangeVal('#stbi','#stbi-cur')"><input style="border: hidden;" readonly id="stbi-cur">
				</div>
				@error('stbi')
		            <span class="invalid-feedback" role="alert">
		                <strong>{{ $message }}</strong>
		            </span>
		        @enderror
			</div>
			<div class="form-group row">
				<label class="col-sm-7 col-form-label">ROBOTIKA</label>
				<div class="col-sm-5">
					<input type="range" min="0" max="4" name="robot" id="robot" value="{{$dps->robot ?? 0 }}" class=" col-lg-12 form-control @error('robot') is-invalid @enderror" onchange="rangeVal('#robot','#robot-cur')"><input style="border: hidden;" readonly id="robot-cur">
				</div>
				@error('robot')
		            <span class="invalid-feedback" role="alert">
		                <strong>{{ $message }}</strong>
		            </span>
		        @enderror
			</div>
			<div class="form-group row">
				<label class="col-sm-7 col-form-label">GRAFIKA KOMPUTER</label>
				<div class="col-sm-5">
					<input type="range" min="0" max="4" name="grafkom" id="grafkom" value="{{$dps->grafkom ?? 0 }}" class=" col-lg-12 form-control @error('grafkom') is-invalid @enderror" onchange="rangeVal('#grafkom','#grafkom-cur')"><input style="border: hidden;" readonly id="grafkom-cur">
				</div>
				@error('grafkom')
		            <span class="invalid-feedback" role="alert">
		                <strong>{{ $message }}</strong>
		            </span>
		        @enderror
			</div>
			<div class="form-group row">
				<label class="col-sm-7 col-form-label">KOMPUTER VISI</label>
				<div class="col-sm-5">
					<input type="range" min="0" max="4" name="kompvisi" id="kompvisi" value="{{$dps->kompvisi ?? 0 }}" class=" col-lg-12 form-control @error('kompvisi') is-invalid @enderror" onchange="rangeVal('#kompvisi','#kompvisi-cur')"><input style="border: hidden;" readonly id="kompvisi-cur">
				</div>
				@error('kompvisi')
		            <span class="invalid-feedback" role="alert">
		                <strong>{{ $message }}</strong>
		            </span>
		        @enderror
			</div>
		</div>
		<div class="col-lg card p-4 m-1">
			<H3 class="mb-4 mt-4">PROFILE RELATA</H3>
			<div class="form-group row">
				<label class="col-sm-7 col-form-label">KEAMANAN KOMPUTER</label>
				<div class="col-sm-5">
					<input type="range" min="0" max="4" name="kk" id="kk" value="{{ $dps->kk ?? 0}}" class=" col-lg-12 form-control @error('kk') is-invalid @enderror" onchange="rangeVal('#kk','#kk-cur')"><input style="border: hidden;" readonly id="kk-cur">
				</div>
				@error('kk')
		            <span class="invalid-feedback" role="alert">
		                <strong>{{ $message }}</strong>
		            </span>
		        @enderror
			</div>
			<div class="form-group row">
				<label class="col-sm-7 col-form-label">DIGITAL FORENSIK</label>
				<div class="col-sm-5">
					<input type="range" min="0" max="4" name="df" id="df" value="{{ $dps->df ?? 0 }}" class=" col-lg-12 form-control @error('df') is-invalid @enderror" onchange="rangeVal('#df','#df-cur')"><input style="border: hidden;" readonly id="df-cur">
				</div>
				@error('df')
		            <span class="invalid-feedback" role="alert">
		                <strong>{{ $message }}</strong>
		            </span>
		        @enderror
			</div>
			<div class="form-group row">
				<label class="col-sm-7 col-form-label">JARINGAN</label>
				<div class="col-sm-5">
					<input type="range" min="0" max="4" name="net" id="net" value="{{$dps->net ?? 0 }}" class=" col-lg-12 form-control @error('net') is-invalid @enderror" onchange="rangeVal('#net','#net-cur')"><input style="border: hidden;" readonly id="net-cur">
				</div>
				@error('net')
		            <span class="invalid-feedback" role="alert">
		                <strong>{{ $message }}</strong>
		            </span>
		        @enderror
			</div>
			<div class="form-group row">
				<label class="col-sm-7 col-form-label">REKAYASA PERANGKAT LUNAK</label>
				<div class="col-sm-5">
					<input type="range" min="0" max="4" name="rpll" id="rpll" value="{{$dps->rpl ?? 0}}" class=" col-lg-12 form-control @error('rpll') is-invalid @enderror" onchange="rangeVal('#rpll','#rpll-cur')"><input style="border: hidden;" readonly id="rpll-cur">
				</div>
				@error('rpll')
		            <span class="invalid-feedback" role="alert">
		                <strong>{{ $message }}</strong>
		            </span>
		        @enderror
			</div>
			<div class="form-group row">
				<label class="col-sm-7 col-form-label">MULTIMEDIA</label>
				<div class="col-sm-5">
					<input type="range" min="0" max="4" name="mm" id="mm" value="{{$dps->mm ?? 0}}" class=" col-lg-12 form-control @error('mm') is-invalid @enderror" onchange="rangeVal('#mm','#mm-cur')"><input style="border: hidden;" readonly id="mm-cur">
				</div>
				@error('mm')
		            <span class="invalid-feedback" role="alert">
		                <strong>{{ $message }}</strong>
		            </span>
		        @enderror
			</div>
		</div>
	</div>
	<div class="card m-1 p-4">
		<H3 class="mb-4 mt-4">PROFILE PENDUKUNG</H3>
		<div class="form-group row">
			<label class="col-sm-7 col-form-label">BIMBINGAN</label>
			<div class="col-sm-5">
				<input type="range" min="1" max="4" name="bimbingan" id="bimbingan" value="{{$dps->bimbingan ?? 0}}" class=" col-lg-12 form-control @error('bimbingan') is-invalid @enderror" onchange="rangeVal('#bimbingan','#bimbingan-cur')"><input style="border: hidden;" readonly id="bimbingan-cur">
			</div>
			@error('bimbingan')
	            <span class="invalid-feedback" role="alert">
	                <strong>{{ $message }}</strong>
	            </span>
	        @enderror
		</div>
		<div class="form-group row">
			<label class="col-sm-7 col-form-label">PROGRES</label>
			<div class="col-sm-5">
				<input type="range" min="1" max="4" name="progres" id="progres" value="{{$dps->progres ?? 0}}" class=" col-lg-12 form-control @error('progres') is-invalid @enderror" onchange="rangeVal('#progres','#progres-cur')"><input style="border: hidden;" readonly id="progres-cur">
			</div>
			@error('progres')
	            <span class="invalid-feedback" role="alert">
	                <strong>{{ $message }}</strong>
	            </span>
	        @enderror
		</div>
		<div class="form mt-4">
			@if($level == 'mahasiswa')
				<input type="submit" name="" value="proses" id="tombol-kirim" class="btn btn-primary"> 
			@else
				<input type="submit" name="" value="simpan" id="tombol-kirim" class="btn btn-primary"> 
			@endif
			
			<input type="reset" name="" id="tombol-reset" class="btn btn-danger">
		</div>
		<p id="puppop" style="color:red;">*pastikan jumlah nilai bobot prioritas = 100 </p>
	</div>
</form>
</div>
</div>

<!-- <script type="text/javascript">
	$(document).ready(function(){
		
	});
</script> -->

@endsection