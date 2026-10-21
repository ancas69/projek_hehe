@extends('layouts.mhs.profile_matching')
@section('pb')
alert-success
@endsection
@section('judul_step')
PEMBOBOTAN KRITERIA
@endsection

@section('proses_step')
	<div class=" p-1 m-1">

	<div class="alert alert-success p-4" style="text-align: left;">
		<p><strong>Pembobotan Kriteria Prioritas</strong></p>
		<p>Kriteria Utama : bidang minat, topik penelitian, jumlah antrian pengajuan, slot bimbingan tersisa.</p>
		<p>Kriteria Pendukung : frekuensi bimbingan, target selesai penelitian, pendidikan terakhir dosen, beban bimbingan.</p>
	</div>
	@if(!isset($dbobot->id_user))
	 <form method="POST" action="{{route('simpanPersentaseKriteria')}} ">
	@else
	 <form method="POST" action="{{route('updatePersentaseKriteria')}} ">
	@endif
		@csrf
		<input class="slider" type="range" min="0" max="100" value="{{ $dbobot->NSF ?? 50}}" id="persentase">
		<div class="justify-content-between row">
			
			<div class="col-5" style="font-size: 50px;">NCF (Kriteria Utama) : <p  id="NCF"></p></div>
			<div style="text-align: right; font-size: 50px;" class="col-5">NSF (Kriteria Pendukung) : <p id="NSF"></p></div>
			
		</div>
		<input type="hidden" name="id_user" id="id_mahasiswa" value="{{$data->id_user}}">

		<input type="hidden" name="NCF" id="id_NCF">
		<input type="hidden" name="NSF" id="id_NSF">

		<input type="submit" class="btn btn-primary" name="sub" value="Next">
	</form>
	</div>
@endsection
