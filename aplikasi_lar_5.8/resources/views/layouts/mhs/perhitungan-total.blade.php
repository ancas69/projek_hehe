
<div class="container ">
	@if(isset($warning))
	<div class="alert alert-danger">
		<strong>Maaf</strong>, tidak ada dosen yang tersedia coba ganti pilihan anda.
	</div>
	@else	
	<div class="table-responsive">
 	<table class="table table-secondary table-bordered" style="text-align: center;">
		<thead class="thead-dark" style="text-align: center; vertical-align: middle;">
			<tr>
				<th rowspan="3">NO</th>
				<th rowspan="3">DOSEN</th>
				<th colspan="4">UTAMA</th>
				<th colspan="4">PENDUKUNG</th>
				<th rowspan="3">NCF</th>
				<th rowspan="3">NSF</th>
				<th rowspan="3">NCF + NSF</th>
			</tr>
			<tr>
				
				<th>BIDANG MINAT</th>
				<th>TOPIK PENELITIAN</th>
				<th>SEDANG ANTRI</th>
				<th>SLOT BIMBINGAN</th>

				<th>PENDIDIKAN TERAKHIR</th>
				<th>FREKUENSI BIMBINGAN</th>
				<th>TARGET SELESAI PENELITIAN</th>				
				<th>BEBAN BIMBINGAN</th>
				
				<!-- <th>KEAMANAN KOMPUTER</th>
				<th>DIGITAL FORENSIK</th>
				<th>JARINGAN</th>
				<th>RPL</th>
				<th>MULTIMEDIA</th> -->
			</tr>
		</thead>
		<tbody>
			@foreach($hasil as $index => $key)
				@if( $jangan_keluar[$key->id_dosen] == "boleh" )
				<tr>
					<td>{{$nomer[$key->id_dosen]}}</td>
					<td>{{$key->nama}}</td>
					
					<td class="warna_biru">{{$key->bidang_minat}}</td>
					<td class="warna_biru">{{$key->topik_penelitian}}</td>
					<td class="warna_biru">{{$key->antri}}</td>
					<td class="warna_biru">{{$key->slot}}</td>

					<td class="warna_hijau">{{$key->pendidikan}}</td>
					<td class="warna_hijau">{{$key->progres_bimbingan}}</td>
					<td class="warna_hijau">{{$key->progres_penelitian}}</td>					
					<td class="warna_hijau">{{$key->beban_bimbingan}}</td>
					
					<td>
						{{
							number_format((((($key->bidang_minat+$key->topik_penelitian+$key->antri+$key->slot)/4)* $persentase['NCF'] )/100),2)
						}}
					</td>
					<td>
						{{
							number_format((((($key->pendidikan+$key->progres_bimbingan+$key->progres_penelitian+$key->beban_bimbingan)/4)* $persentase['NSF'] )/100),2)
						}}
					</td>
					<td>
						{{
							(number_format((((($key->bidang_minat+$key->topik_penelitian+$key->antri+$key->slot)/4)* $persentase['NCF'] )/100),2)+number_format((((($key->pendidikan+$key->progres_bimbingan+$key->progres_penelitian+$key->beban_bimbingan)/4)* $persentase['NSF'] )/100),2))
						}}
					</td>
				
				</tr>
				@endif	
			@endforeach	
		</tbody>
	</table>
	</div>

	

		
<!-- 		<a class="btn btn-warning" href="{{route('hasilAkhir')}}">
		  Lihat Rekomendasi
		</a> -->
			@endif
</div>
