@extends('layouts.dosen.main')

@section('judul_halaman')
{{$judul}}
@endsection

@section('keterangan_halaman')
@endsection

@section('isi')
<div class="container">
	<div class="alert alert-success">
		<p style="font-size: 40px;">Bimbingan terbaru : {{$slot_periode_ini[$data->id_dosen]}}/{{$data->slot }} {{$data->periode_pengajuan}} </p>
		<input type="hidden" id="sisa_slot" value="{{$data->slot - $slot_periode_ini[$data->id_dosen]}}" name="">
	</div>
	<hr>
	<div>
		<button class="btn btn-primary color_filter" onclick="filter('')">all periode</button>
		@foreach($array_all_periode as $periode)
		<button onclick="filter('{{$periode}}')" class="btn btn-secondary color_filter m-1">{{$periode}}</button>
		@endforeach
	</div>
	<hr>

	@if( ($data->slot - $slot_periode_ini[$data->id_dosen]) == 0)
	<div class="alert alert-danger">
		Slot bimbingan anda sudah penuh, anda dapat menolak atau menghapus data mahasiswa yang mengajukan penelitian yang tersisa.
	</div>
	<hr>
	@endif

	<a href="javascript:void(0)" class="btn btn-success" id="cek_all">Cek all</a> 
	<a href="javascript:void(0)" class="btn btn-secondary" id="uncek_all">unCek all</a>
	

	<div class="table-responsive mt-2">
	@if(!$calon->isEmpty())

		@if($color_stat == "Terima")
		<table id="cari-disini" class="table  table-primary" style="text-align: center; font-size: 11px;">
		@elseif($color_stat == "Menunggu")
		<table id="cari-disini" class="table  table-secondary" style="text-align: center; font-size: 11px;">
		@elseif($color_stat == "Tolak")
		<table id="cari-disini" class="table  table-danger" style="text-align: center; font-size: 11px;">
		@else
		<table id="cari-disini" class="table cari-disini table-success" style="text-align: center; font-size: 11px;">
		@endif
			<thead class="thead-dark">
				<tr>
					<th style="vertical-align: middle;" rowspan="">Cek</th>
					<th style="vertical-align: middle;" rowspan="">No</th>
					<th style="vertical-align: middle;" rowspan="">Mahasiswa</th>
					<th style="vertical-align: middle;" rowspan="">NIM</th>
					<th style="vertical-align: middle;" rowspan="" colspan="1">Periode</th>
					<th style="vertical-align: middle;" rowspan="" colspan="1">Aksi</th>
				</tr>
			</thead>
			<tbody>
				<form action="{{route('updateCek')}}" id="the_ceformupdt" method="POST">
					@csrf
					<input type="hidden" value="" id="statIdCekCalon" name="stat">
					@foreach($calon as $index => $key)
					<tr class="item-yang-dicari">
						<td style="vertical-align: middle;">								
							<input type="checkbox" class="form-control the_ceboxCal" name="cek_id_calon[]"  value="{{$key->id_calon}}">						<!-- onclick="tambahcekCal('{{$key->id_calon}}')" -->
						</td>
						<td style="vertical-align: middle;" >{{$index+1}}</td>
						<td style="vertical-align: middle;" >{{$key->nama}}</td>
						<td style="vertical-align: middle;" >{{$key->id_mahasiswa}}</td>
						<td>{{$key->periode}}</td>
						<td style="vertical-align: middle;" >
							<a href="{{    route('detailMahasiswa',['id' => $key->id_calon ])    }} " style="background-color: blue; color: #fff; border-radius: 5px; font-size: 10px; padding: 10px;">detail</a>
						</td>
						
					</tr>
					
					@endforeach
				</form>
				<tr>
					<td class="bg-dark" colspan="6"></td>
				</tr>
			</tbody>
		</table>

		<hr>
		<div>
			<button style="font-size: 14px;"class="btn btn-danger the_cebtndel" >Hapus</button>
			@if($color_stat == "Menunggu")
				@if($slot_periode_ini[$data->id_dosen] < 8)
				<button style="font-size: 14px;"class="btn btn-primary the_cebtnter" >Terima</button>
				@else
				<button style="font-size: 14px;"class="btn btn-primary" disabled="" >Sllot Penuh</button>
				@endif
			<button style="font-size: 14px;"class="btn btn-warning the_cebtntol" >Tolak</button>
			@elseif($color_stat == "Terima")
			<button style="font-size: 14px;"class="btn btn-primary the_cebtnsel">Luluskan mahasiswa</button>
			@elseif($color_stat == "Selesai")
			@else
			@endif
		</div>
	
	@else
		<p style="text-align: center;">belum ada calon mahasiswa bimbingan</p>
	@endif
	</div>


</div>
@endsection