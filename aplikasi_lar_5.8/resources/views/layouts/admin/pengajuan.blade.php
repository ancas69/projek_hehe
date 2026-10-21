@extends('layouts.admin.main')
@section('judul_halaman')
PENGATURAN BUKA / TUTUP PENGAJUAN PENELITIAN
@endsection

@section('keterangan_halaman')
<div class="alert alert-warning">
	Admin dapat mengatur status pengajuan, sedang menerima pengajuan penelitian atau tidak. nantinya mahasiswa yang mengajukan penelitian akan dikelompokkan berdasarkan semester dan tahun ajaran yang diinputkan oleh dosen. Setiap penerimaan semester baru slot bimbingan akan selalu ter-reset kembali ke 0, akan tetapi data mahasiswa yang semester lalu akan selalu ada hingga Dosen menyatakan bahwa mahasiswa telah selesai dalam penelitian dan menghapus nya . 
</div>
@endsection
@section('isi')
<div>
	<div class="card p-4">
		@if($data->periode_pengajuan == "tutup")
		<div class="alert alert-danger">Pengajuan penelitian ditutup, buka dengan mengatur semester pada form <strong>buka pendaftaran</strong> dibawah</div>
		@else
			@if(substr($data->periode_pengajuan, 0,-9) == 'ganjil')
			<div class="alert alert-primary">Periode bimbingan semester <strong>{{$data->periode_pengajuan}}</strong> dibuka</div>
			@else
			<div class="alert alert-success">Periode bimbingan semester <strong>{{$data->periode_pengajuan}}</strong> dibuka</div>
			@endif
		@endif

	</div>
</div>
<hr>
<div>
	<div class="card p-4">
		buka pendaftaran bimbingan semester
		<hr>
		<form method="POST" action="{{  route('thePengajuan')  }}" id="subformPendaftaran"> 
			@csrf
            <div class="form">

                <label class="">Masukkan periode pendaftaran</label>
                <div class="row container">

		        @if($data->periode_pengajuan == "tutup")
					<div class="col-2">
		                 <input type="number" name="tahun_ajaran" value="{{         date('Y')     }}" id="theTahunAjaran" class=" form-control @error('tahun_ajaran') is-invalid @enderror">
		                 @error('tahun_ajaran')
		                    <span class="invalid-feedback" role="alert">
		                        <strong>{{ $message }}</strong>
		                    </span>
		                @enderror
		            </div>
	                <div class="col-1" style="text-align: center;">-</div>
			        <div class="col-2">
			            <input type="number" readonly id="futureY" name="tahun_ajaran2" value="{{      date('Y')+1    }}" class=" form-control @error('tahun_ajaran2') is-invalid @enderror">
			            @error('tahun_ajaran2')
			                <span class="invalid-feedback" role="alert">
			                    <strong>{{ $message }}</strong>
			                </span>
			            @enderror
			        </div>
				@else
					<div class="col-2">
		                 <input type="number" name="tahun_ajaran" value="{{       substr(strtr($data->periode_pengajuan,$array_genjil_genap),0,-5 )     }}" id="theTahunAjaran" class=" form-control @error('tahun_ajaran') is-invalid @enderror">
		                 @error('tahun_ajaran')
		                    <span class="invalid-feedback" role="alert">
		                        <strong>{{ $message }}</strong>
		                    </span>
		                @enderror
		            </div>
	                <div class="col-1" style="text-align: center;">-</div>
			        <div class="col-2">
			            <input type="number" readonly id="futureY" name="tahun_ajaran2" value="{{        substr(strtr($data->periode_pengajuan,$array_genjil_genap),5, 9 )    }}" class=" form-control @error('tahun_ajaran2') is-invalid @enderror">
			            @error('tahun_ajaran2')
			                <span class="invalid-feedback" role="alert">
			                    <strong>{{ $message }}</strong>
			                </span>
			            @enderror
			        </div>
				@endif

               	
           	 	
           	 	</div>
                <input type="hidden" name="stat" value="" id="statIdPendaftaran">
                

            </div>
        </form>
		<hr>
		<div>
			<button class="btn btn-primary" id="openGanjil" >Buka pengajuan penelitian semester ganjil</button>
			<button class="btn btn-success" id="openGenap" >Buka pengajuan penelitian semester genap</button>
			<button  class="btn btn-danger" id="closePenelitian">Tutup Pengajua Semester ini</button>
			
		</div>		
	</div>

</div>
<hr>
<!-- bobot sudah pindah ke pengaturan bersama identitas dll -->
@endsection