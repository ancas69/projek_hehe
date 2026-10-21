

<div class="container">
	<!-- <div class="alert alert-success p-4" style="text-align: left;">
		<p><strong>Pemetaan gap</strong></p>
		<p>Berikut ini adalah data selisih nilai antara data dosen dengan data mahasiswa yang tadi diinputkan</p>
		<p>proses pemetaan nilai dilakukan berdasarkan selisih yang telah ditetapkan nilainya, berikut ini tabel pemetaan nilai</p>
		<table border="1" cellpadding="4">
			<tr>
				<td>selisih</td><td>nilai</td><td>keterangan</td>
			</tr>
			<tr>
				<td>0</td>
				<td>5</td>
				<td>cocok</td>
			</tr>
			<tr>
				<td>-1</td>
				<td>4</td>
				<td>kekurangan 1 tingkat</td>
			</tr>
			<tr>
				<td>1</td>
				<td>4.5</td>
				<td>kelebihan 1 tingkat</td>
			</tr>
			<tr>
				<td>-2</td>
				<td>3</td>
				<td>kekurangan 2 tingkat</td>
			</tr>
			<tr>
				<td>2</td>
				<td>3.5</td>
				<td>kelebihan 2 tingkat</td>
			</tr>

		</table>
		<p>slisih nilai akan terus berkurang hingga selisih lebih dari atau sama dengan 5 atau kurang dari samadengan -5</p>
	</div> -->

	@if(isset($warning))
	<div class="alert alert-danger">
		<strong>Maaf</strong>, tidak ada dosen yang tersedia coba ganti pilihan anda.
	</div>
	@else
	<div class="table-responsive">
		<table class="table table-secondary" style="text-align: center;">
			<thead class="thead-dark" style="text-align: center; vertical-align: middle;">
				<tr>
					<th rowspan="2">NO</th>
					<th rowspan="2">DOSEN</th>
					<th colspan="8">KRITERIA</th>
				</tr>
				<tr>
					<th>PENDIDIKAN TERAKHIR</th>
					<th>BIDANG MINAT</th>
					<th>TOPIK PENELITIAN</th>
					<th>FREKUENSI BIMBINGAN</th>
					<th>TARGET LULUS PENELITIAN</th>
					<th>SEDANG ANTRI</th>
					<th>BEBAN BIMBINGAN</th>
					<th>SLOT</th>
				</tr>
			</thead>
			<tbody>
				@foreach($profile as $index => $key)
					@if($jangan_keluar[$key->id_dosen] == "boleh")
					<tr>
						<td>{{$nomer[$key->id_dosen]}}</td>
						<td>{{$key->nama}}</td>
						<td>@if($key->pendidikan == null)
								{{0}}
							@else
								{{$key->pendidikan}}
							@endif 
						</td>
						<td>@if($key->bidang_minat == null)
								{{0}}
							@else
								{{$key->bidang_minat}}
							@endif 
						</td>
						<td>@if($matching_to_dosen[$key->id_dosen] == null)
								{{0}}
							@else
								{{$matching_to_dosen[$key->id_dosen]}}
							@endif 
						</td>
						<td>@if($key->progres_bimbingan == null)
								{{0}}
							@else
								{{$key->progres_bimbingan}}
							@endif 
						</td>
						<td>@if($key->progres_penelitian == null)
								{{0}}
							@else
								{{$key->progres_penelitian}}
							@endif 
						</td>
						<TD>@if($key->antri == null)
								{{0}}
							@else
								{{$key->antri}}
							@endif 
						</TD>
						<TD>@if($key->beban_bimbingan == null)
								{{0}}
							@else
								{{$key->beban_bimbingan}}
							@endif 
						</TD>
						<td>@if($key->slot_prof == null)
								{{0}}
							@else
							@endif {{$key->slot_prof}}</td>
					</tr>
					@endif
				@endforeach	

				<tr class="bg-warning">
					<td colspan="2">Profile Sasaran</td>
					<td>{{$profmhs['pendidikan']}}</td>
					<td>{{$profmhs['bidang_minat']}}</td>
					<td>{{count(explode('#',$profmhs['topik_penelitian']))  }}</td>
					<td>{{$profmhs['progres_bimbingan']}}</td>
					<td>{{$profmhs['progres_penelitian']}}</td>
					<td>{{$profmhs['antri']}}</td>
					<td>{{$profmhs['beban_bimbingan']}}</td>
					<td>{{$profmhs['slot']}}</td>
				</tr>	


				@foreach($hasil as $index => $key)
					@if($jangan_keluar[$key->id_dosen] == "boleh")
					<tr>
						<td>{{$nomer[$key->id_dosen]}}</td>
						<td>{{$key->nama}}</td>
						<td>{{$key->pendidikan}}</td>
						<td>{{$key->bidang_minat}}</td>
						<td>{{$key->topik_penelitian}}</td>
						<td>{{$key->progres_bimbingan}}</td>
						<td>{{$key->progres_penelitian}}</td>
						<TD>{{$key->antri}}</TD>
						<TD>{{$key->beban_bimbingan}}</TD>
						<td>{{$key->slot}}</td>
					</tr>	
					@endif
				@endforeach	
			</tbody>
		</table>
	</div>



<!-- 	<a href="{{route('perhitunganTotal')}}" class="btn btn-primary">next</a> -->
	@endif
</div>
