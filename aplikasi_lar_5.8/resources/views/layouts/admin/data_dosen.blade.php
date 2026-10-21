@extends('layouts.admin.main')

@section('judul_halaman')
@endsection

@section('keterangan_halaman')
@endsection


@section('isi')
<div><strong>Atur tahun pengajuan</strong></div>
<div class="row container">
	<div class="col-2">
         <input type="number" name="tahun_ajaran" value="{{         date('Y')     }}" id="theTahunAjaran" class="form-control">
        
    </div>
    <div class="col-1" style="text-align: center;">-</div>
    <div class="col-2">
        <input type="number" readonly id="futureY" name="tahun_ajaran2" value="{{      date('Y')+1    }}" class="form-control">
    </div>
</div>
<hr>
<div><strong>Atur semester</strong></div>
<input type="radio" class="semester" name="semester" value="ganjil"> Ganjil
<input type="radio" class="semester" name="semester" value="genap"> Genap
<input type="hidden" id="temp_semester" name="" value="belum_di_atur" readonly>
<hr>
<div>
	<table class="table ">
		<thead class="table-dark">
			<tr>
				<th>No</th>
				<th>ID</th>
				<th>Nama</th>
				<th>Mahasiswa Bimbingan</th>
				<th colspan="2">Pengajuan</th>
			</tr>
		</thead>

		<tbody class="table-secondary">
			@foreach( $data_dosen as $index => $key)
			<tr>
				<td>{{$index+1}}</td>
				<td>{{$key->id_dosen}}</td>
				<td>{{$key->nama}}</td>
				<td>{{$data_beban_bimbingan[$key->id_dosen]}} mahasiswa</td>
				<td>
					
					<label class="switch">
						@if($key->periode_pengajuan == "tutup")
							<input class="switch-input pengajuan_cb" data-id="{{$key->id_dosen}}" id="{{$key->id_dosen}}" type="checkbox" />
						@else
							<input class="switch-input pengajuan_cb" checked data-id="{{$key->id_dosen}}" id="{{$key->id_dosen}}" type="checkbox" />
						@endif
						<span class="switch-label" data-on="On" data-off="Off"></span> 
						<span class="switch-handle"></span> 
					</label>
					

				</td>
				<td>
					<div class="btn btn-success " id="stat_loading{{$key->id_dosen}}">ok</div>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
@endsection