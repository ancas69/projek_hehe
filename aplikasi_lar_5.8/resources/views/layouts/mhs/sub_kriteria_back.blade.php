@extends('layouts.mhs.profile_matching')
@section('bk')
alert-success
@endsection
@section('judul_step')
KRITERIA TUJUAN / PROFILE TUJUAN
@endsection


@section('proses_step')
<div class="alert alert-success p-4" style="text-align: left;">
	<p>pada tahap ini user akan memilih nilai dari sub kriteria, user akan memilih sesuai dengan pilihan yang diinginkan</p>
</div>

	@if(!isset($id_profile->id_user))
	 <form method="POST" id="formSubKriteria" action="{{route('simpanBobotsubKriteria')}} ">
	@else
	 <form method="POST" id="formSubKriteria" action="{{route('updateBobotsubKriteria')}} ">
	@endif
	@csrf
	<div class="row justify-content-center">
		<div class="col-lg card p-4 m-1">
			<H3 class="mb-4 mt-4">AKADEMIK <p style="font-size: 50px;">{{$bobot_kriteria['akademik']}}%</p></H3>
			<div class="p-4 form-group row">
				<label class="col-sm-12 col-form-label border-bottom">PENDIDIKAN TERAKHIR DOSEN YANG DIINGINKAN</label>
				<div class="   p-2 col-12">
				@if(isset($id_profile))
					@if($id_profile['pendidikan'] == 1)
					<div class="form-check">
					  <input hidden class="form-check-input" checked="" type="radio" name="pend" id="pend1" value="1">
					  <label class="form-check-label m-1 border-success alert-primary pendidikan_color btn col-12" for="pend1">
					    S2
					  </label>
					</div>
					<div class="form-check">
					  <input hidden class="form-check-input" type="radio" name="pend" id="pend2" value="2">
					  <label class="form-check-label m-1 border-success pendidikan_color btn col-12" for="pend2">
					    S3
					  </label>
					</div>
					@elseif($id_profile->pendidikan == 2)
					<div class="form-check">
					  <input hidden class="form-check-input"  type="radio" name="pend" id="pend1" value="1">
					  <label class="form-check-label m-1 border-success pendidikan_color btn col-12" for="pend1">
					    S2
					  </label>
					</div>
					<div class="form-check">
					  <input hidden class="form-check-input" checked="" type="radio" name="pend" id="pend2" value="2">
					  <label class="form-check-label m-1 alert-primary border-success pendidikan_color btn col-12" for="pend2">
					    S3
					  </label>
					</div>
					@endif
				@else
				<div class="form-check">
				  <input hidden class="form-check-input"  type="radio" name="pend" id="pend1" value="1">
				  <label class="form-check-label m-1 border-success pendidikan_color btn col-12" for="pend1">
				    S2
				  </label>
				</div>
				<div class="form-check">
				  <input hidden class="form-check-input"  type="radio" name="pend" id="pend2" value="2">
				  <label class="form-check-label m-1  border-success pendidikan_color btn col-12" for="pend2">
				    S3
				  </label>
				</div>
				@endif

				</div>

				

				
			</div>
			<div class="p-4 form-group row">
				<label class="col-sm-12 col-form-label border-bottom">BIDANG MINAT YANG DIINGINKAN</label>
				<div class="    p-2 col-12">
				@if(isset($id_profile))
					@if($id_profile->bidang_minat == 0)
					<div class="form-check ">					
					  <input hidden class="form-check-input" checked=""  type="radio" name="bmin" id="bmin1" value="0">
					  <label class="form-check-label m-1 border-success bidangminat_color alert-primary btn col-12" for="bmin1">
					    SISTEM CERDAS
					  </label>
					</div>
					<div class="form-check ">
					  <input hidden class="form-check-input"  type="radio" name="bmin" id="bmin2" value="5">
					  <label class="form-check-label m-1 border-success btn bidangminat_color col-12" for="bmin2">
					    REKAYASA PERANGKAT LUNAK
					  </label>
					</div>


					@elseif($id_profile->bidang_minat == 5)
					<div class="form-check ">					
					  <input hidden class="form-check-input"  type="radio" name="bmin" id="bmin1" value="0">
					  <label class="form-check-label m-1 border-success bidangminat_color btn col-12" for="bmin1">
					    SISTEM CERDAS
					  </label>
					</div>
					<div class="form-check ">
					  <input hidden class="form-check-input" checked="" type="radio" name="bmin" id="bmin2" value="5">
					  <label class="form-check-label m-1 border-success alert-primary btn bidangminat_color col-12" for="bmin2">
					    REKAYASA PERANGKAT LUNAK
					  </label>
					</div>

					@endif
				@else
				<div class="form-check ">					
				  <input hidden class="form-check-input"  type="radio" name="bmin" id="bmin1" value="0">
				  <label class="form-check-label m-1 border-success bidangminat_color btn col-12" for="bmin1">
				    SISTEM CERDAS
				  </label>
				</div>
				<div class="form-check ">
				  <input hidden class="form-check-input"  type="radio" name="bmin" id="bmin2" value="5">
				  <label class="form-check-label m-1 border-success  btn bidangminat_color col-12" for="bmin2">
				    REKAYASA PERANGKAT LUNAK
				  </label>
				</div>
				@endif
					<div class="mt-4">
						<input id="penampung_topik_mhs" type="hidden"value="{{$id_profile->topik_penelitian ?? ''}}">
						<p >Silahkan Pilih Topik Penelitian</p>
						<div id="peringatan-topik" ></div>
						<hr>	
					</div>
					<div class="mt-4" style="height: 240px; overflow-y: scroll;">
						<div id="formTopikSistemCerdas" style="display: none;">
							<div class="custom-control custom-checkbox">
							  <input type="checkbox" name="tpsc[]" class="custom-control-input  checksc" value="SISTEM PENDUKUNG KEPUTUSAN" id="1">
							  <label class="custom-control-label"  for="1">SISTEM PENDUKUNG KEPUTUSAN</label>
							</div>
							<div class="custom-control custom-checkbox">
							  <input type="checkbox" name="tpsc[]" class="custom-control-input  checksc" value="SISTEM PAKAR" id="2">
							  <label class="custom-control-label"  for="2">SISTEM PAKAR</label>
							</div>
							<div class="custom-control custom-checkbox">
							  <input type="checkbox" name="tpsc[]" class="custom-control-input  checksc" value="PEMBELAJARAN MESIN" id="3">
							  <label class="custom-control-label"  for="3">PEMBELAJARAN MESIN</label>
							</div>
							<div class="custom-control custom-checkbox">
							  <input type="checkbox" name="tpsc[]" class="custom-control-input  checksc" value="ROBOTIKA" id="4">
							  <label class="custom-control-label"  for="4">ROBOTIKA</label>
							</div>
							<div class="custom-control custom-checkbox">
							  <input type="checkbox" name="tpsc[]" class="custom-control-input  checksc" value="PENGENALAN POLA" id="5">
							  <label class="custom-control-label"  for="5">PENGENALAN POLA</label>
							</div>
							<div class="custom-control custom-checkbox">
							  <input type="checkbox" name="tpsc[]" class="custom-control-input  checksc" value="PEMROSESAN BAHASA ALAMI" id="6">
							  <label class="custom-control-label"  for="6">PEMROSESAN BAHASA ALAMI</label>
							</div>
							<div class="custom-control custom-checkbox">
							  <input type="checkbox" name="tpsc[]" class="custom-control-input  checksc" value="PENGEMBANGAN GAME" id="7">
							  <label class="custom-control-label"  for="7">PENGEMBANGAN GAME</label>
							</div>
							<div class="custom-control custom-checkbox">
							  <input type="checkbox" name="tpsc[]" class="custom-control-input  checksc" value="KOMPUTER VISI" id="8">
							  <label class="custom-control-label"  for="8">KOMPUTER VISI</label>
							</div>
							<div class="custom-control custom-checkbox">
							  <input type="checkbox" name="tpsc[]" class="custom-control-input  checksc" value="DATA MINING" id="9">
							  <label class="custom-control-label"  for="9">DATA MINING</label>
							</div>
							<div class="custom-control custom-checkbox">
							  <input type="checkbox" name="tpsc[]" class="custom-control-input  checksc" value="GRAFIKA LANJUT" id="0">
							  <label class="custom-control-label"  for="0">GRAFIKA LANJUT</label>
							</div>
							<div class="custom-control custom-checkbox">
							  <input type="checkbox" name="tpsc[]" class="custom-control-input checksc" value="SISTEM TEMUBALIK INFORMASI" id="11">
							  <label class="custom-control-label"  for="11">SISTEM TEMUBALIK INFORMASI</label>
							</div>
							<div class="custom-control custom-checkbox">
							  <input type="checkbox" name="tpsc[]" class="custom-control-input checksc" value="PEMROGRAMAN PARAREL" id="12">
							  <label class="custom-control-label"  for="12">PEMROGRAMAN PARAREL</label>
							</div>
						</div>

						<div id="formTopikRelata" style="display: none;">
							<div class="custom-control custom-checkbox">
							  <input type="checkbox" name="tprl[]" class="custom-control-input checkrl" value="JARINGAN KOMPUTER" id="0rel">
							  <label class="custom-control-label"  for="0rel">JARINGAN</label>
							</div>
							<div class="custom-control custom-checkbox">
							  <input type="checkbox" name="tprl[]" class="custom-control-input checkrl" value="MULTIMEDIA" id="1rel">
							  <label class="custom-control-label"  for="1rel">MULTIMEDIA</label>
							</div>
							<div class="custom-control custom-checkbox">
							  <input type="checkbox" name="tprl[]" class="custom-control-input checkrl" value="KRIPTOGRAFI" id="2rel">
							  <label class="custom-control-label"  for="2rel">KRIPTOGRAFI</label>
							</div>
							<div class="custom-control custom-checkbox">
							  <input type="checkbox" name="tprl[]" class="custom-control-input checkrl" value="REKAYASA WEB" id="3rel">
							  <label class="custom-control-label"  for="3rel">REKAYASA WEB</label>
							</div>
							<div class="custom-control custom-checkbox">
							  <input type="checkbox" name="tprl[]" class="custom-control-input checkrl" value="FORENSIK DIGITAL" id="4rel">
							  <label class="custom-control-label"  for="4rel">FORENSIK DIGITAL</label>
							</div>
							<div class="custom-control custom-checkbox">
							  <input type="checkbox" name="tprl[]" class="custom-control-input checkrl" value="PENJAMIN KUALITAS PERANGKAT LUNAK" id="5rel">
							  <label class="custom-control-label"  for="5rel">PENJAMIN KUALITAS PERANGKAT LUNAK</label>
							</div>
							<hr>
						</div>

					</div>	
				</div>
			</div>
		</div>




		<div class="col card p-4 m-1">
			<H3 class="mb-4 mt-4">PENELITIAN <p style="font-size: 50px;">{{$bobot_kriteria['proses_bimbingan']}}%</p></H3>
			<div class="p-4 form-group row">
				<label class="col-sm-12 col-form-label border-bottom">PROSES BIMBINGAN YANG DIINGINKAN</label>
				<div class="    p-2 col-12">
				@if(isset($id_profile))	
					@if($id_profile['progres_bimbingan'] == 1)
					<div class="form-check">
					  <input hidden class="form-check-input" checked="" type="radio" name="prgbim" id="prgbim1" value="1">
					  <label class="bimbingan_color alert-primary btn col-12 m-1 border-success form-check-label" for="prgbim1">
					    SANTAI (bimbingan terserah mahasiswa)
					  </label>
					</div>
					<div class="form-check">
					  <input hidden class="form-check-input" type="radio" name="prgbim" id="prgbim2" value="2">
					  <label class="bimbingan_color btn col-12 m-1 border-success form-check-label" for="prgbim2">
					    NORMAL (bimbingan sesuai arahan dosen)
					  </label>
					</div>
					<div class="form-check">
					  <input hidden class="form-check-input" type="radio" name="prgbim" id="prgbim3" value="3">
					  <label class="bimbingan_color btn col-12 m-1 border-success form-check-label" for="prgbim3">
					    KETAT (bimbingan seminggu lebih atau sama dengan 3x)
					  </label>
					</div>
					@elseif($id_profile['progres_bimbingan'] == 2)
					<div class="form-check">
					  <input hidden class="form-check-input" type="radio" name="prgbim" id="prgbim1" value="1">
					  <label class="bimbingan_color  btn col-12 m-1 border-success form-check-label" for="prgbim1">
					    SANTAI (bimbingan terserah mahasiswa)
					  </label>
					</div>
					<div class="form-check">
					  <input hidden class="form-check-input" checked=""  type="radio" name="prgbim" id="prgbim2" value="2">
					  <label class="bimbingan_color btn alert-primary col-12 m-1 border-success form-check-label" for="prgbim2">
					    NORMAL (bimbingan sesuai arahan dosen)
					  </label>
					</div>
					<div class="form-check">
					  <input hidden class="form-check-input" type="radio" name="prgbim" id="prgbim3" value="3">
					  <label class="bimbingan_color btn col-12 m-1 border-success form-check-label" for="prgbim3">
					    KETAT (bimbingan seminggu lebih atau sama dengan 3x)
					  </label>
					</div>
					@elseif($id_profile['progres_bimbingan'] == 3)
					<div class="form-check">
					  <input hidden class="form-check-input" type="radio" name="prgbim" id="prgbim1" value="1">
					  <label class="bimbingan_color  btn col-12 m-1 border-success form-check-label" for="prgbim1">
					    SANTAI (bimbingan terserah mahasiswa)
					  </label>
					</div>
					<div class="form-check">
					  <input hidden class="form-check-input"  type="radio" name="prgbim" id="prgbim2" value="2">
					  <label class="bimbingan_color btn  col-12 m-1 border-success form-check-label" for="prgbim2">
					    NORMAL (bimbingan sesuai arahan dosen)
					  </label>
					</div>
					<div class="form-check">
					  <input hidden class="form-check-input" checked=""  type="radio" name="prgbim" id="prgbim3" value="3">
					  <label class="bimbingan_color btn col-12 alert-primary m-1 border-success form-check-label" for="prgbim3">
					    KETAT (bimbingan seminggu lebih atau sama dengan 3x)
					  </label>
					</div>
					@endif
				@else
				<div class="form-check">
				  <input hidden class="form-check-input" type="radio" name="prgbim" id="prgbim1" value="1">
				  <label class="bimbingan_color  btn col-12 m-1 border-success form-check-label" for="prgbim1">
				    SANTAI (bimbingan terserah mahasiswa)
				  </label>
				</div>
				<div class="form-check">
				  <input hidden class="form-check-input"  type="radio" name="prgbim" id="prgbim2" value="2">
				  <label class="bimbingan_color btn  col-12 m-1 border-success form-check-label" for="prgbim2">
				    NORMAL (bimbingan sesuai arahan dosen)
				  </label>
				</div>
				<div class="form-check">
				  <input hidden class="form-check-input"   type="radio" name="prgbim" id="prgbim3" value="3">
				  <label class="bimbingan_color btn col-12  m-1 border-success form-check-label" for="prgbim3">
				    KETAT (bimbingan seminggu lebih atau sama dengan 3x)
				  </label>
				</div>
				@endif
				</div>
			</div>
			<div class="p-4 form-group row">
				<label class="col-sm-12 col-form-label border-bottom">PROGRES PENELITIAN YANG DIINGINKAN</label>
				<div class="    p-2 col-12">
				@if(isset($id_profile))
					@if($id_profile->progres_penelitian == 1)
					<div class="form-check">
					  <input hidden class="form-check-input" checked="" type="radio" name="prgpen" id="prgpen1" value="1">
					  <label class="penelitian_color border-success btn alert-primary col-12 m-1 form-check-label" for="prgpen1">
					    SANTAI (laporan penelitian terserah mahasiswa)
					  </label>
					</div>
					<div class="form-check">
					  <input hidden class="form-check-input" type="radio" name="prgpen" id="prgpen2" value="2">
					  <label class="penelitian_color border-success btn col-12 m-1 form-check-label" for="prgpen2">
					    NORMAL (laporan penelitian sesuai arahan dosen)
					  </label>
					</div>
					<div class="form-check">
					  <input hidden  class="form-check-input" type="radio" name="prgpen" id="prgpen3" value="3">
					  <label class="penelitian_color border-success btn col-12 m-1 form-check-label" for="prgpen3">
					    KETAT (laporan penelitian seminggu lebih atau sama dengan 3x)
					  </label>
					</div>
					@elseif($id_profile->progres_penelitian == 2)
					<div class="form-check">
					  <input hidden class="form-check-input" type="radio" name="prgpen" id="prgpen1" value="1">
					  <label class="penelitian_color border-success btn col-12 m-1 form-check-label" for="prgpen1">
					    SANTAI (laporan penelitian terserah mahasiswa)
					  </label>
					</div>
					<div class="form-check">
					  <input hidden class="form-check-input" checked="" type="radio" name="prgpen" id="prgpen2" value="2">
					  <label class="penelitian_color border-success btn alert-primary col-12 m-1 form-check-label" for="prgpen2">
					    NORMAL (laporan penelitian sesuai arahan dosen)
					  </label>
					</div>
					<div class="form-check">
					  <input hidden  class="form-check-input" type="radio" name="prgpen" id="prgpen3" value="3">
					  <label class="penelitian_color border-success btn col-12 m-1 form-check-label" for="prgpen3">
					    KETAT (laporan penelitian seminggu lebih atau sama dengan 3x)
					  </label>
					</div>
					@elseif($id_profile->progres_penelitian == 3)
					<div class="form-check">
					  <input hidden class="form-check-input" type="radio" name="prgpen" id="prgpen1" value="1">
					  <label class="penelitian_color border-success btn col-12 m-1 form-check-label" for="prgpen1">
					    SANTAI (laporan penelitian terserah mahasiswa)
					  </label>
					</div>
					<div class="form-check">
					  <input hidden class="form-check-input" type="radio" name="prgpen" id="prgpen2" value="2">
					  <label class="penelitian_color border-success btn col-12 m-1 form-check-label" for="prgpen2">
					    NORMAL (laporan penelitian sesuai arahan dosen)
					  </label>
					</div>
					<div class="form-check">
					  <input hidden  class="form-check-input" checked="" type="radio" name="prgpen" id="prgpen3" value="3">
					  <label class="penelitian_color border-success alert-primary btn col-12 m-1 form-check-label" for="prgpen3">
					    KETAT (laporan penelitian seminggu lebih atau sama dengan 3x)
					  </label>
					</div>
					@endif
				@else
				<div class="form-check">
				  <input hidden class="form-check-input" type="radio" name="prgpen" id="prgpen1" value="1">
				  <label class="penelitian_color border-success btn col-12 m-1 form-check-label" for="prgpen1">
				    SANTAI (laporan penelitian terserah mahasiswa)
				  </label>
				</div>
				<div class="form-check">
				  <input hidden class="form-check-input" type="radio" name="prgpen" id="prgpen2" value="2">
				  <label class="penelitian_color border-success btn col-12 m-1 form-check-label" for="prgpen2">
				    NORMAL (laporan penelitian sesuai arahan dosen)
				  </label>
				</div>
				<div class="form-check">
				  <input hidden  class="form-check-input" type="radio" name="prgpen" id="prgpen3" value="3">
				  <label class="penelitian_color border-success btn col-12 m-1 form-check-label" for="prgpen3">
				    KETAT (laporan penelitian seminggu lebih atau sama dengan 3x)
				  </label>
				</div>
				@endif
				</div>
			</div>
		</div>
		
	</form>
	<div class="col-12 form mt-4">
		<a id="tombol-kirim-subKriteria" class="btn btn-primary" href="javascript:void(0)">Next</a>
	</div>
@endsection