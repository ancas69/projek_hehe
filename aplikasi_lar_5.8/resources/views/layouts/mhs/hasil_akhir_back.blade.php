
@if(isset($warning))
<div class="alert alert-danger">
	<strong>Maaf</strong>, tidak ada dosen dengan topik penelitian yang anda pilih.
</div>
@else
<div class="table-responsive">
<table class="table table-secondary" style="text-align: center;">
					<thead class="thead-dark" style="text-align: center;">
						<tr>
							<th style="vertical-align: middle;" rowspan="2">NO</th>
							<th style="vertical-align: middle;" rowspan="2">NAMA</th>
							<th style="vertical-align: middle;" colspan="2">Nilai Total (NCF + NSF)</th>
							
							<th style="vertical-align: middle;" rowspan="2">Antri/Sisa Slot</th>
							<th style="vertical-align: middle;" rowspan="2">Beban Bimbingan Dosen</th>

							<th style="vertical-align: middle;" rowspan="2">Skor Akhir</th>
							<th style="vertical-align: middle;" rowspan="2">Aksi</th>
						</tr>
						<tr>
							<th>AKADEMIK</th>
							<th>PENELITIAN</th>
						
						</tr>
					</thead>
					<tbody id="">
						@foreach($akhir as $index => $key)

								<tr class="">
									<td scope="row">{{$index+1}}</td>
									<td>
										<a href="dosen{{$key->id_dosen}}">{{$key->nama}}</a>
									</td>
									
									<td>{{
										(number_format((((($key->bidang_minat+$key->topik_penelitian)/2)*80)/100),2)+number_format((((($key->pendidikan))*20)/100),2))
									}}</td>
									
									<td>
										{{
											(number_format(((($key->progres_bimbingan+$key->progres_penelitian+$key->beban_bimbingan)/3)*20)/100,2)+number_format(((($key->slot_prof+$key->antri)/2)*80)/100,2))
										}}
									</td>

									
									<td>
										<strong>{{$key->peminat}}/{{$key->slot-$slot_taken_this_semester[$key->id_dosen]}}</strong>
									</td>
									<td>{{$key->slot_taken}} mhs</td>

									<td>{{$key->tot}}</td>

									<td>										
										@if($helper[$index]!="kosong")
											<form action="{{route('hapusCalon')}}" method="post">
												@csrf
												<input type="hidden" value="{{$helper[$index]}}" name="id_calon">
												<button type="submit" class="btn btn-danger">
													<!-- <i class="material-icons">indeterminate_check_box</i> -->
												<span class="material-icons">delete</span>
												</button>
											</form>
										@else
											@if($key->periode_pengajuan != 'tutup')
											<form action="{{route('tambahCalon')}}" id="formtambahcalonhasilakhir" method="post">
												@csrf
												<input type="hidden" value="{{$key->id_dosen}}" name="id_dosen">
												<input type="hidden" value="{{$data['id_mahasiswa']}}" name="id_mahasiswa">
												<input type="hidden" name="periode" value="{{$key->periode_pengajuan}}">
												@if($key->slot-$key->slot_taken <= 0)
													<div class="alert alert-danger" style="font-size: 9px;">Penuh</div>

												@else
													@if($key->peminat < 8)
													<button type="submit" class="btn btn-success">
														<i class="material-icons">person_add</i>
													</button>
											</form>
													@else
													<a href="javascript:void(0)" class="btn btn-warning" onclick="
													var cek = confirm('antrian terlalu banyak, anda yakin ingin mengajukan ?');
													if(cek){
														document.getElementById('formtambahcalonhasilakhir').submit();
													}
													">
														<i class="material-icons">person_add</i>
													</a>
													@endif
												@endif
											
											@else
											<div class="alert alert-danger" style="font-size: 9px;">Pendaftaran masih tutup</div>
											@endif				
										@endif
									</td>
								</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		
	      	<div class="alert alert-warning" style="text-align: center;">
	      		hasil pililhan dapat dilihat pada menu home
	      	</div>
@endif
