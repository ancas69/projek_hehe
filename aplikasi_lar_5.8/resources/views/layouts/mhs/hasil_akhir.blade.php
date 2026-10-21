

@if(isset($warning))
<div class="alert alert-danger">
	<strong>Maaf</strong>, tidak ada dosen yang tersedia coba ganti pilihan anda.
</div>
@else
@if($memilih == "tidak")
	<div class="alert alert-warning">
		<strong>Peringatan</strong>, Anda memiliki pengajuan penelitian yang sedang diproses. Selama masa ini anda tidak dapat mengajukan penelitian kepada dosen lain, tunggu hingga penelitian dikonfirmasi oleh dosen, atau batalkan pengajuan.
	</div>
@endif
<div class="table-responsive">
	<table class="table table-secondary table-bordered" style="text-align: center;">
		<thead class="thead-dark" style="text-align: center;">
			<tr>
				<th style="vertical-align: middle;" rowspan="">NO</th>
				<th style="vertical-align: middle;" rowspan="">NAMA</th>
				
				<th style="vertical-align: middle;" rowspan="">Antri/Sisa Slot</th>
				<th style="vertical-align: middle;" rowspan="">Beban Bimbingan Dosen</th>

				<th style="vertical-align: middle;" rowspan="">Skor Akhir</th>
				<th style="vertical-align: middle;" rowspan="">Aksi</th>
			</tr>
		</thead>
		<tbody id="">
			@foreach($akhir as $index => $key)
				@if( $jangan_keluar[$key->id_dosen] == "boleh" )
					@if($key->tot >= 4)
					<tr style="background-color: rgba(0,255,0,0.2);">
					@elseif ($key->tot >= 3.5)
					<tr style="background-color: rgba(225,235,0,0.2);">
					@else
					<tr style="background-color: rgba(255,0,0,0.2);">
					@endif
						<td scope="row" class="bg-dark" style="color: #fff;">
							{{$nomer[$key->id_dosen]}}
						</td>
						<td align="left">
							<a href="dosen{{$key->id_dosen}}" style="color: #000;" target="_blank" rel="noopener noreferrer">{{$key->nama}}</a>
						</td>
								
						<td>
							<strong>{{$key->peminat}}/{{$key->slot-$slot_taken_this_semester[$key->id_dosen]}}</strong>
						</td>
						<td>{{$key->slot_taken}} mhs</td>

						<td>{{$key->tot}}</td>

						<td>@if($memilih == "boleh")										
								@if($slot_taken_this_semester[$key->id_dosen]==8) 
									
									<button disabled="" class="btn btn-danger" style="font-size: 9px;">
										penuh
									</button>	
								@else
									<button data-id="{{$key->id_dosen}}" type="button" class="tambah_c btn btn-primary" data-toggle="modal" data-target="#exampleModalScrollable" style="font-size: 9px;">
										form pengajuan
									</button>			
								@endif
							@else
								???
							@endif
						</td>
					</tr>
				@endif
			@endforeach
		</tbody>
	</table>
</div>
		
<div class="alert alert-warning" style="text-align: center;">
	hasil pililhan dapat dilihat pada menu home
</div>





<!-- Modal -->
<div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalScrollableTitle">FORM PENGAJUAN PENELITIAN</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">      	
      	<div class="alert alert-danger" id="peringatan_antri" role="alert">Antrian penelitian pada dosen ini terlalu banyak</div>
        <form enctype="multipart/form-data" method="post" action="{{route('tambahCalon')}}" id="formPengajuanPenelitian">
        	@csrf
        	<input type="hidden" id="topik_pilihan_mhs" value="{{ $profmhs->topik_penelitian }}">
        	<input type="hidden" name="id_mahasiswa" value="{{$data->id_mahasiswa}}">
        	<input type="hidden" id="M_input_id_dosen" name="id_dosen">
        	<input type="hidden" id="M_input_periode_pengajuan" name="periode">
        	<table>
        		<tr>
        			<td>NIY / NID</td>
        			<td> : </td>
        			<td id="M_id_dosen"></td>
        		</tr>
    			<tr>
    				<td>Nama Dosen</td>
    				<td> : </td>
    				<td id="M_nama_dosen"></td>
    			</tr>
    			<tr>
    				<td>Topik Bimbingan</td>
    				<td> : </td>
    				<td id="M_topik_dosen"></td>
    			</tr>
        	</table>
        	<div id="hasil_seleksi" class="mt-2">
        		<!-- topik penelitian yang sama -->...
        	</div>
        	<div id="seleksi">
	        	@foreach($tp_form as $tp_form)
	        		<div class="alert alert-primary" id="fff{{$tp_form->id}}">
						{{$tp_form->topik}}
					</div>
				@endforeach
			</div>
        	<hr>
        	<div class="form mt-2">
				<label class="">Judul Penelitian</label>
				<input type="text" required="" name="judul_penelitian" class="form-control">
				
			</div>
			<div class="form mt-2">
				<label class="">Topik</label>
				<input type="text" required="" name="topik" class="form-control">

				
			</div>
			<div class="form mt-2">
				<label class="">File Resume <p style="color: green;">*File resume harus berformat pdf < 1Mb</p></label>
				<input type="file" required="" id="form_input_file" name="file_resume" class="form-control @error('file_data') is-invalid @enderror" accept="application/pdf">  
				@error('file_data')
		            <span class="invalid-feedback " role="alert">
		                <strong>{{ $message }}</strong>
		            </span>
		        @enderror
				
			</div>

			<div class="row justify-content-center m-4">
				<input type="submit" name="" value="Ajukan Penelitian" class="col btn btn-success">
			</div>
        </form>
      
    </div>
  </div>
</div>
@endif

<script type="text/javascript">
$(document).ready(function(){
	$('#seleksi').hide();

	$('#form_input_file').bind('change', function() {
    //this.files[0].size gets the size of your file.
    if(this.files[0].size > 1048576){
      alert("ukuran file terlalu besar");
      $('#form_input_file').val(null);
    }      
  });

  $('#form_input_file').bind('change', function() {
    fileName = document.querySelector('#form_input_file').value;
    extension = fileName.split('.').pop();
    if(extension != "pdf"){
    	alert("file tidak berformat pdf");
    	$('#form_input_file').val(null);
    }
  });

    $('.tambah_c').click(function(){
      $("#hasil_seleksi").empty();
      var id = $(this).data('id');
      console.log(id);
      $('#M_id_dosen').text(id);
      $('#M_input_id_dosen').val(id);

      $.ajax({
      	headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
      	url:'{{ route("getOneDataDosenJson") }}',
      	data:{id_dosen:id},
      	type:'POST',
      	success: function(data){
      		//console.log(data);
      		$('#M_nama_dosen').text(data.nama);

      		var str = data.topik_bimbingan;
      			str = str.toLowerCase();
      		var topics_dsn = str.split("#");

      		var str = $('#topik_pilihan_mhs').val();
      			str = str.toLowerCase();
      		var topics_mhs = str.split("#");

      		var filteredArray = topics_dsn.filter(function(n) { return topics_mhs.indexOf(n) !== -1; } ); // mengeluarkan topik penelitian hanya yang sama dengan pilihan mhs

      		if(data.antri > 0){
      			$("#peringatan_antri").hide();
      		}else{
      			$("#peringatan_antri").show();
      		}




      		$('#M_topik_dosen').text("");

					for (let i = 0; i < filteredArray.length; i++) {
						$('#fff'+filteredArray[i]).clone().appendTo('#hasil_seleksi');
					}

      		

      		$('#M_input_periode_pengajuan').val(data.periode_pengajuan);

      	}
      })
    });
})
</script>