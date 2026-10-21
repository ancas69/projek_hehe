@extends('layouts.mhs.profile_matching')
@section('bk')
alert-success
@endsection
@section('judul_step')
KRITERIA TUJUAN / PROFILE TUJUAN
@endsection


@section('proses_step')
<div class="container"> 


<div class="alert alert-success p-4" style="text-align: left;" role="alert">
	<p>Mahasiswa dapat memilih kriteria dosen pembimbing yang sesuai dengan pertanyaan form dibawah.</p>
</div>

	
	<form method="POST" id="formSubKriteria">
	@csrf
	<!-- <input type="hidden" id="cek_data_sub_kriteria" name="" value="{{ $id_profile->kosong ?? ''}}"> -->
	<div class="row justify-content-center">
		<div class="col-lg card p-4 m-1">
			<H3 class="mb-4 mt-4">Utama <p style="font-size: 50px;">{{$bobot_kriteria['NCF']}}%</p></H3>
			<div class="p-4 form-group row">
				<div class="col-sm-12">
			        <H3 class="mb-4">BIDANG MINAT YANG DIINGINKAN</H3>
			        <input type="hidden" id="handlerBidMin" value="{{ (($id_profile == null) ? '' : $id_profile->bidang_minat) }}">
					<div class="form-check ">					
					  <input hidden="" class="form-check-input" type="radio" name="bminE" id="bminE1" value="1">
					  <label class="form-check-label m-1 border-success bidangminat_color btn col-12" for="bminE1">
					    SISTEM CERDAS
					  </label>
					</div>
					<div class="form-check ">
					  <input hidden="" class="form-check-input"  type="radio" name="bminE" id="bminE6" value="6">
					  <label class="form-check-label m-1 border-success btn bidangminat_color col-12" for="bminE6">
					    REKAYASA PERANGKAT LUNAK
					  </label>
					</div>
			        <H3 class="mb-4 mt-4">TOPIK PENELITIAN YANG DIINGINKAN</H3>
			        
					    <div class="mt-4">
							<input id="penampung_topik_mhs" type="hidden"value="{{(($id_profile == null) ? '' : $id_profile->topik_penelitian)}}">
							<p >Silahkan Pilih Topik Penelitian</p>
							<div id="peringatan-topik" ></div>
							<hr>	
						</div>
						<div class="mt-4" style="height: 240px; overflow-y: scroll;">
							<div id="" style="">
								@foreach($daftar_topik as $tpk)
								<div class="custom-control custom-checkbox">
								  <input type="checkbox" name="topik[]" class="custom-control-input  checktp" value="{{$tpk->id}}" id="{{$tpk->id}}">
								  <label class="custom-control-label"  for="{{$tpk->id}}">{{$tpk->topik}}</label>
								</div>
								@endforeach
								<!-- <div class="custom-control custom-checkbox">
								  <input type="checkbox" name="topik[]" class="custom-control-input  checktp" value="SISTEM PENDUKUNG KEPUTUSAN" id="1">
								  <label class="custom-control-label"  for="1">SISTEM PENDUKUNG KEPUTUSAN</label>
								</div>
								<div class="custom-control custom-checkbox">
								  <input type="checkbox" name="topik[]" class="custom-control-input  checktp" value="SISTEM PAKAR" id="2">
								  <label class="custom-control-label"  for="2">SISTEM PAKAR</label>
								</div>
								<div class="custom-control custom-checkbox">
								  <input type="checkbox" name="topik[]" class="custom-control-input  checktp" value="PEMBELAJARAN MESIN" id="3">
								  <label class="custom-control-label"  for="3">PEMBELAJARAN MESIN</label>
								</div>
								<div class="custom-control custom-checkbox">
								  <input type="checkbox" name="topik[]" class="custom-control-input  checktp" value="ROBOTIKA" id="4">
								  <label class="custom-control-label"  for="4">ROBOTIKA</label>
								</div>
								<div class="custom-control custom-checkbox">
								  <input type="checkbox" name="topik[]" class="custom-control-input  checktp" value="PENGENALAN POLA" id="5">
								  <label class="custom-control-label"  for="5">PENGENALAN POLA</label>
								</div>
								<div class="custom-control custom-checkbox">
								  <input type="checkbox" name="topik[]" class="custom-control-input  checktp" value="PEMROSESAN BAHASA ALAMI" id="6">
								  <label class="custom-control-label"  for="6">PEMROSESAN BAHASA ALAMI</label>
								</div>
								<div class="custom-control custom-checkbox">
								  <input type="checkbox" name="topik[]" class="custom-control-input  checktp" value="PENGEMBANGAN GAME" id="7">
								  <label class="custom-control-label"  for="7">PENGEMBANGAN GAME</label>
								</div>
								<div class="custom-control custom-checkbox">
								  <input type="checkbox" name="topik[]" class="custom-control-input  checktp" value="KOMPUTER VISI" id="8">
								  <label class="custom-control-label"  for="8">KOMPUTER VISI</label>
								</div>
								<div class="custom-control custom-checkbox">
								  <input type="checkbox" name="topik[]" class="custom-control-input  checktp" value="DATA MINING" id="9">
								  <label class="custom-control-label"  for="9">DATA MINING</label>
								</div>
								<div class="custom-control custom-checkbox">
								  <input type="checkbox" name="topik[]" class="custom-control-input  checktp" value="GRAFIKA LANJUT" id="0">
								  <label class="custom-control-label"  for="0">GRAFIKA LANJUT</label>
								</div>
								<div class="custom-control custom-checkbox">
								  <input type="checkbox" name="topik[]" class="custom-control-input checktp" value="SISTEM TEMUBALIK INFORMASI" id="11">
								  <label class="custom-control-label"  for="11">SISTEM TEMUBALIK INFORMASI</label>
								</div>
								<div class="custom-control custom-checkbox">
								  <input type="checkbox" name="topik[]" class="custom-control-input checktp" value="PEMROGRAMAN PARAREL" id="12">
								  <label class="custom-control-label"  for="12">PEMROGRAMAN PARAREL</label>
								</div>



								<div class="custom-control custom-checkbox">
								  <input type="checkbox" name="topik[]" class="custom-control-input checktp" value="JARINGAN KOMPUTER" id="0rel">
								  <label class="custom-control-label"  for="0rel">JARINGAN</label>
								</div>
								<div class="custom-control custom-checkbox">
								  <input type="checkbox" name="topik[]" class="custom-control-input checktp" value="MULTIMEDIA" id="1rel">
								  <label class="custom-control-label"  for="1rel">MULTIMEDIA</label>
								</div>
								<div class="custom-control custom-checkbox">
								  <input type="checkbox" name="topik[]" class="custom-control-input checktp" value="KRIPTOGRAFI" id="2rel">
								  <label class="custom-control-label"  for="2rel">KRIPTOGRAFI</label>
								</div>
								<div class="custom-control custom-checkbox">
								  <input type="checkbox" name="topik[]" class="custom-control-input checktp" value="REKAYASA WEB" id="3rel">
								  <label class="custom-control-label"  for="3rel">REKAYASA WEB</label>
								</div>
								<div class="custom-control custom-checkbox">
								  <input type="checkbox" name="topik[]" class="custom-control-input checktp" value="FORENSIK DIGITAL" id="4rel">
								  <label class="custom-control-label"  for="4rel">FORENSIK DIGITAL</label>
								</div>
								<div class="custom-control custom-checkbox">
								  <input type="checkbox" name="topik[]" class="custom-control-input checktp" value="PENJAMIN KUALITAS PERANGKAT LUNAK" id="5rel">
								  <label class="custom-control-label"  for="5rel">PENJAMIN KUALITAS PERANGKAT LUNAK</label>
								</div>
								<div class="custom-control custom-checkbox">
								  <input type="checkbox" name="topik[]" class="custom-control-input checktp" value="PEMROGRAMAN WEB DINAMIS" id="6rel">
								  <label class="custom-control-label"  for="6rel">PEMROGRAMAN WEB DINAMIS</label>
								</div>
								<div class="custom-control custom-checkbox">
								  <input type="checkbox" name="topik[]" class="custom-control-input checktp" value="PEMROGRAMAN MOBILE" id="9rel">
								  <label class="custom-control-label"  for="9rel">PEMROGRAMAN MOBILE</label>
								</div>

								<div class="custom-control custom-checkbox">
								  <input type="checkbox" name="topik[]" class="custom-control-input checktp" value="ENTERPRISE SYSTEM" id="10rel">
								  <label class="custom-control-label"  for="10rel">ENTERPRISE SYSTEM</label>
								</div>

								<div class="custom-control custom-checkbox">
								  <input type="checkbox" name="topik[]" class="custom-control-input checktp" value="INTERAKSI MANUSIA KOMPUTER" id="11rel">
								  <label class="custom-control-label"  for="11rel">INTERAKSI MANUSIA KOMPUTER</label>
								</div> -->
							
								
							</div>

						</div>


			        
				</div>
			</div>
		</div>




		<div class="col card p-4 m-1">
			<H3 class="mb-4 mt-4">Pendukung <p style="font-size: 50px;">{{$bobot_kriteria['NSF']}}%</p></H3>
			<div class="p-4 form-group row">
				<div class="col-sm-12">
					<H3 class="mb-4">FREKUENSI BIMBINGAN YANG DIINGINKAN</H3>
			        <input type="hidden" id="handlerProgBim" value="{{(($id_profile == null) ? '' : $id_profile->progres_bimbingan)}}">
			        <div class="form-check">
			          <input hidden="" class="form-check-input" type="radio" name="prgbimE" id="prgbimE1" value="1">
			          <label class="bimbingan_color btn col-12 m-1 border-success form-check-label" for="prgbimE1">
			            tidak ada aturan
			          </label>
			        </div>
			        <div class="form-check">
			          <input hidden="" class="form-check-input" type="radio" name="prgbimE" id="prgbimE2" value="2">
			          <label class="bimbingan_color btn col-12 m-1 border-success form-check-label" for="prgbimE2">
			            sebulan minimal 2 kali
			          </label>
			        </div>
			        <div class="form-check">
			          <input hidden="" class="form-check-input" type="radio" name="prgbimE" id="prgbimE3" value="3">
			          <label class="bimbingan_color btn col-12 m-1 border-success form-check-label" for="prgbimE3">
			            seminggu minimal 1 kali
			          </label>
			        </div>
			        <div class="form-check">
			          <input hidden="" class="form-check-input" type="radio" name="prgbimE" id="prgbimE4" value="4">
			          <label class="bimbingan_color btn col-12 m-1 border-success form-check-label" for="prgbimE4">
			            seminggu minimal 2 kali
			          </label>
			        </div>
			        <div class="form-check">
			          <input hidden="" class="form-check-input" type="radio" name="prgbimE" id="prgbimE5" value="5">
			          <label class="bimbingan_color btn col-12 m-1 border-success form-check-label" for="prgbimE5">
			            seminggu lebih dari 3 kali
			          </label>
			        </div>

			        <H3 class="mb-4 mt-4">PENGERJAAN SKRIPSI YANG DIINGINKAN</H3>
			        <input type="hidden" id="handlerProgPen" value="{{(($id_profile == null) ? '' : $id_profile->progres_penelitian)}}">
			        <div class="form-check">
			          <input hidden="" class="form-check-input" type="radio" name="prgpenE" id="prgpenE1" value="1">
			          <label class="penelitian_color border-success btn  col-12 m-1 form-check-label" for="prgpenE1">
			            tidak ada target tertentu
			          </label>
			        </div>
			        <div class="form-check">
			          <input hidden="" class="form-check-input" type="radio" name="prgpenE" id="prgpenE2" value="2">
			          <label class="penelitian_color border-success btn col-12 m-1 form-check-label" for="prgpenE2">
			          	pengerjaan kurang 3 bulan
			            
			          </label>
			        </div>
			        <div class="form-check">
			          <input hidden=""  class="form-check-input" type="radio" name="prgpenE" id="prgpenE3" value="3">
			          <label class="penelitian_color border-success btn col-12 m-1 form-check-label" for="prgpenE3">
			            
			            perngerjaan kurang dari 4 bulan
			          </label>
			        </div>
			        <div class="form-check">
			          <input hidden=""  class="form-check-input" type="radio" name="prgpenE" id="prgpenE4" value="4">
			          <label class="penelitian_color border-success btn col-12 m-1 form-check-label" for="prgpenE4">

			            pengerjaan kurang dari 6 bulan
			          </label>
			        </div>
			        <div class="form-check">
			          <input hidden=""  class="form-check-input" type="radio" name="prgpenE" id="prgpenE5" value="5">
			          <label class="penelitian_color border-success btn col-12 m-1 form-check-label" for="prgpenE5">
			            perngerjaan kurang dari 8 bulan
			          </label>
			        </div>

			        <H3 class="mb-4 mt-4">PENDIDIKAN TERAKHIR</H3>
			        <input type="hidden" id="handlerPend" value="{{(($id_profile == null) ? '' : $id_profile->pendidikan)}}">
			        <div class="form-check">
			          <input hidden="" class="form-check-input" type="radio" name="pendE" id="pendE1" value="1">
			          <label class="form-check-label m-1 border-success pendidikan_color btn col-12" for="pendE1">
			            S2
			          </label>
			        </div>
			        <div class="form-check">
			          <input hidden="" class="form-check-input" type="radio" name="pendE" id="pendE2" value="2">
			          <label class="form-check-label m-1 border-success pendidikan_color btn col-12" for="pendE2">
			            S3
			          </label>
			        </div>
			        
				</div>
			</div>
		</div>
		
	</form>
	<div class="col-12 form mt-4">
		@if($cek_pengajuan_->periode_pengajuan == "tutup")
			<div class="col-12 btn btn-warning">Pengajuan belum dibuka</div>
		@else
			<a id="tombol-kirim-subKriteria" class="col-12 btn btn-primary" href="javascript:void(0)">Proses</a>	
		@endif	
	</div>





</div>	

<hr>
  <div class="">
    <!-- <div  role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:40%"> -->
    <progress id="progres_profile_matching" style="background: #eee; width: 100%;" value="0" max="100">
  </div>
<hr>

<div class="accordion " id="accordionExample">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h2 class="mb-0">
        <button class="btn dropdown-toggle text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Pemetaan Gap <span class="caret"></span>
        </button>
      </h2>
    </div>

    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body" >
        <div id="gap_pm_alert" class="alert alert-primary" role="alert"></div>
        <div id="gap_pm"></div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h2 class="mb-0">
        <button class="btn dropdown-toggle text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Total NCF dan NSF
        </button>
      </h2>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">
        <div id="total_pm_alert" class="alert alert-primary" role="alert"></div>
        <div  id="total_pm"></div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingThree">
      <h2 class="mb-0">
        <button class="btn dropdown-toggle text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Hasil Rekomendasi
        </button>
      </h2>
    </div>
    <div id="collapseThree" class="collapse show" aria-labelledby="headingThree" data-parent="#accordionExample">
      <div class="card-body">
      	<div id="hasil_pm_alert" class="alert alert-primary" role="alert"></div>
      	<div  id="hasil_pm"></div>
        
      </div>
    </div>
  </div>
</div>
@endsection