<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SPK DOSBING') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- ckeditor text area -->
    <script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>

    
</head>
<body class="container">
<form method="POST" action="{{route('dsnSave')}}" id="formcreateidentitas" enctype="multipart/form-data">
<div class="row justify-content-center p-4">
    <div class="col-lg-6">
        <H3 class="mb-4">BUAT IDENTITAS</H3>
        
        	@csrf
          <div class="form">
              <label class="">Foto </label>
              <input type="file" name="foto" id="foto_dosen_cek" class="form-control col-lg-12 @error('foto') is-invalid @enderror">
              @error('foto')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
          </div>
        	<div class="form">
        		<label class="">NIY Dosen</label>
        		<input type="number" required="" name="niy" class="col-lg-12 form-control @error('niy') is-invalid @enderror">
        		@error('niy')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
        	</div>
        	<div class="form mt-2">
        		<label class="">Nama Dosen </label>
        		<input type="text" required="" name="nama" class=" col-lg-12 form-control @error('nama') is-invalid @enderror">
        		@error('nama')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
        	</div>
        	<div class="form mt-2">
        		<label class="">Jabatan <p style="color: red;">(tidak harus)</p></label>
        		<input type="text" name="job" class=" col-lg-12 form-control @error('job') is-invalid @enderror">
        		@error('job')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
        	</div>
        	<div class="form mt-2">
        		<label class="">Nomer Telpon <p style="color: red;">(tidak harus)</p></label>
        		<input type="number" name="hp" class=" col-lg-12 form-control @error('hp') is-invalid @enderror">
        		@error('hp')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
        	</div>
        	<div class="form mt-2">
        		<label class="">Slot</label>
        		<input type="text" name="slot" value="8" readonly="" class=" col-lg-12 form-control @error('slot') is-invalid @enderror">
        		@error('slot')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
        	</div>
                <div class="form mt-2">
                <label class="">Tentang penelitian <p style="color: red;">(tidak harus)</p></label>
                <textarea name="content" id="editor"></textarea>
                
            </div>
        	
        
    </div>
    <div class="col-lg-6 mt-4">
        <H3 class="mb-4">BIDANG MINAT DOSEN</H3>
        <!-- <input type="hidden" id="handlerBidMin" value="{{$profileDosen->bidang_minat ?? ''}}"> -->
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
        <H3 class="mb-4 mt-4">TOPIK SKRIPSI YANG INGIN DIBIMBING</H3>
        <div class="mt-4">
            <!-- <input id="penampung_topik_mhs" type="hidden" value="{{$profileDosen->topik_penelitian ?? ''}}"> -->
            <p >Silahkan Pilih Topik Penelitian </p>
            <p style="color: red;" id="peringatan_topik"></p>
            <div id="peringatan-topik" ></div>
            <hr>    
        </div>

        <div class="mt-4" style="height: 240px; overflow-y: scroll;">
          @foreach($daftar_topik as $tpk)
          <div>
            <div class=" custom-control custom-checkbox ">
              <input type="checkbox" name="topik[]" class="custom-control-input  checktp" value="{{$tpk->id}}" id="{{$tpk->id}}">
              <label class="custom-control-label"  for="{{$tpk->id}}">{{$tpk->topik}}</label>            
            </div>
          </div>	
          @endforeach
        </div>


        <H3 class="mb-4 mt-4">PENDIDIKAN TERAKHIR</H3>
        <!-- <input type="hidden" id="handlerPend" value="{{$profileDosen->pendidikan ?? ''}}"> -->
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

        <H3 class="mb-4 mt-4">FREKUENSI BIMBINGAN YANG DITERAPKAN</H3>
        <!-- <input type="hidden" id="handlerProgBim" value="{{$profileDosen->progres_bimbingan ?? ''}}"> -->
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

        <H3 class="mb-4 mt-4">TARGET IDEAL WAKTU PENELITIAN YANG DITERAPKAN</H3>
       <!--  <input type="hidden" id="handlerProgPen" value="{{$profileDosen->progres_penelitian ?? ''}}"> -->
        <div class="form-check">
          <input hidden="" class="form-check-input" type="radio" name="prgpenE" id="prgpenE1" value="1">
          <label class="penelitian_color border-success btn  col-12 m-1 form-check-label" for="prgpenE1">
            tidak ada target tertentu
          </label>
        </div>
        <div class="form-check">
          <input hidden="" class="form-check-input" type="radio" name="prgpenE" id="prgpenE2" value="2">
          <label class="penelitian_color border-success btn col-12 m-1 form-check-label" for="prgpenE2">
           
            pengerjaan kurang 6 bulan
          </label>
        </div>
        <div class="form-check">
          <input hidden=""  class="form-check-input" type="radio" name="prgpenE" id="prgpenE3" value="3">
          <label class="penelitian_color border-success btn col-12 m-1 form-check-label" for="prgpenE3">
             perngerjaan kurang dari 8 bulan
          </label>
        </div>
        <div class="form-check">
          <input hidden=""  class="form-check-input" type="radio" name="prgpenE" id="prgpenE4" value="4">
          <label class="penelitian_color border-success btn col-12 m-1 form-check-label" for="prgpenE4">
            pengerjaan kurang dari 10 bulan
          </label>
        </div>
        <div class="form-check">
          <input hidden=""  class="form-check-input" type="radio" name="prgpenE" id="prgpenE5" value="5">
          <label class="penelitian_color border-success btn col-12 m-1 form-check-label" for="prgpenE5">
            
            perngerjaan kurang dari 15 bulan
          </label>
        </div>
    </div>
  </form>
    <div class="form mt-4">
        <a href="javascript:void(0)" id="tombol_simpan_create_identitas" class="btn btn-primary">Simpan</a>
        
    </div>
</div>

<script src="{{ URL::asset('template/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ URL::asset('template/js/popper.min.js') }}"></script>
<script src="{{ URL::asset('template/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('template/js/main.js') }}"></script>
<script src="{{ URL::asset('template/chart/dist/Chart.min.js')}}"></script>
<script type="text/javascript">

  // text area editor ckeditor
  ClassicEditor
      .create( document.querySelector( '#editor' ))
      .catch( error => {
          console.error( error );
      } );
    // BalloonEditor
    //     .create( document.querySelector( '#editor' ) )
    //     .catch( error => {
    //         console.error( error );
    //     } );
  //end

$(document).ready(function(){
      $('#foto_dosen_cek').bind('change', function() {

        //this.files[0].size gets the size of your file.
        if(this.files[0].size > 1048576){
          alert("ukuran file terlalu besar");
          $('#foto_dosen_cek').val(null);
        }
        

      });

    $('#tombol_simpan_create_identitas').click(function(){ 
        var cek_topik = ($('.checktp').filter(':checked').length);
        var cek_bmin = $('input[name=bminE]').filter(':checked').length;
        var cek_pend = $('input[name=pendE]').filter(':checked').length;
        var cek_prgbim = $('input[name=prgbimE]').filter(':checked').length;
        var cek_prgpen = $('input[name=prgpenE]').filter(':checked').length;
        if(cek_topik>0 && cek_bmin > 0 && cek_pend > 0 && cek_prgbim > 0 && cek_prgpen > 0)
        {
            document.getElementById("formcreateidentitas").submit();
        }
        else if(cek_topik == 0 || cek_bmin == 0  ||cek_pend == 0  || cek_prgbim == 0  || cek_prgpen == 0){
            var text = "";

            if(cek_topik == 0)
            {
              text = text + " topik penelitian";
            }

            if(cek_bmin == 0){
              text = text + " bidang minat";
            }

            if(cek_pend == 0){
              text = text + " pendidikan terakhir";
            }

            if(cek_prgbim == 0){
              text = text + " proses bimbingan";
            }

            if(cek_prgpen == 0){
              text = text + " progres penelitian";
            }

            text = text + " harus dipilih";
            alert(text);
        }
    })
  
    $("#tombol_tambah_input").click(function(){
        $("ol").append("<li><input name='topik[]' class='form-control m-1' placecholder='tuliskan topik skripsi disini . . .'></li>");
    });

    // $("input[name=bminE][value='"+$('#handlerBidMin').val()+"']").prop('checked', true);
    // if($("input[name=bminE][value='"+$('#handlerBidMin').val()+"']").is(":checked")){
        
    //     $('label[for=bminE'+$('#handlerBidMin').val()+']').addClass('alert-primary')
    //     console.log("checked")
    // }

    // $("input[name=pendE][value='"+$('#handlerPend').val()+"']").prop('checked', true);
    // if($("input[name=pendE][value='"+$('#handlerPend').val()+"']").is(":checked")){
        
    //     $('label[for=pendE'+$('#handlerPend').val()+']').addClass('alert-primary')
    //     console.log("checked")
    // }


    // $("input[name=prgbimE][value='"+$('#handlerProgBim').val()+"']").prop('checked', true);
    // if($("input[name=prgbimE][value='"+$('#handlerProgBim').val()+"']").is(":checked")){
        
    //     $('label[for=prgbimE'+$('#handlerProgBim').val()+']').addClass('alert-primary')
    //     console.log("checked")
    // }


    // $("input[name=prgpenE][value='"+$('#handlerProgPen').val()+"']").prop('checked', true);
    // if( $("input[name=prgpenE][value='"+$('#handlerProgPen').val()+"']").is(":checked")){
        
    //     $('label[for=prgpenE'+$('#handlerProgPen').val()+']').addClass('alert-primary')
    //     console.log("checked")
    // }


    $('.bidangminat_color').click(function(){
    $('.bidangminat_color').removeClass('alert-primary')
    $(this).addClass('alert-primary')
    })

    $('.pendidikan_color').click(function(){
    $('.pendidikan_color').removeClass('alert-primary')
    $(this).addClass('alert-primary')
    })

    $('.bimbingan_color').click(function(){
    $('.bimbingan_color').removeClass('alert-primary')
    $(this).addClass('alert-primary')
    })

    $('.penelitian_color').click(function(){
    $('.penelitian_color').removeClass('alert-primary')
    $(this).addClass('alert-primary')
    })
});
  
</script>
</body>

</html>
