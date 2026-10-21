<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">


    <!-- ckeditor text area -->
    <script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script><!-- 
    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/decoupled-document/ckeditor.js"></script> -->
    <!-- <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/balloon-block/ckeditor.js"></script> -->


    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Source+Serif+Pro:400,600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ URL::asset('template/fonts/icomoon/style.css') }}">

    <link rel="stylesheet" href="{{ URL::asset('template/css/owl.carousel.min.css') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ URL::asset('template/css/bootstrap.min.css') }}">
    
    <!-- Style -->
    <link rel="stylesheet" href="{{ URL::asset('template/css/style.css') }}">
    <!-- icon pengaturan -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  
    <style type="text/css">
      /*start kode floating button*/

      .float{
        position:fixed;
        width:60px;
        height:60px;
        bottom:40px;
        right:40px;
        background-color:#0C9;
        color:#FFF;
        border-radius:50px;
        text-align:center;
        box-shadow: 2px 2px 3px #999;
      }

      .my-float{
        margin-top:18px;
      }  

        

      /*ennd kode floating button*/      
    </style>
    <title>SPK DOSBING</title>
  </head>
  <body>
  
    
    <aside class="sidebar">
      <div class="toggle">
        <a href="#" class="burger js-menu-toggle" data-toggle="collapse" data-target="#main-navbar">
              <span></span>
            </a>
      </div>
      <div class="side-inner">

        <div class="profile">
          <!-- <img src="images/person_4.jpg" alt="Image" class="img-fluid"> -->
          <h3 class="name">{{$data->nama}}</h3>
          <span class="country">{{$data->id_dosen}}</span>
          <div class="row justify-content-center">
          </div>
        </div>

        
        <div class="nav-menu">
          <ul>
            <li><a href="{{url('/home')}}"><span class="icon-home mr-3"></span>Home</a></li>            
            <li><a href="{{route('subKriteriaDosen')}}"><span class="icon-pie-chart mr-3"></span>bobot profile dosen</a></li>
            <li>
              <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <span class="icon-sign-out mr-3"></span>{{ __('Logout') }}
              </a>
            </li>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
              </form>
          </ul>
        </div>
      </div>
      
    </aside>

    <main>
      <div class="site-section">
        <div class="container">
          @yield('isi')
          <a href="{{route('dsnUpdate')}}" class="float">
             <i class="material-icons my-float">settings</i>
          </a>  
        </div>
      </div>  
    </main>
    
    

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
    $("#tombol_tambah_input").click(function(){
      $("ol").prepend("<li><input name='topik[]' class='form-control m-1' placecholder='tuliskan topik skripsi disini . . .'></li>");
    });
    $('#puppop').hide();
    

    $('.auto_sub').change(function(){
      $(".auto_sub").submit();
    })

    $('#link_group').keyup(function(){
      $('.link2').val($('#link_group').val())
    })
    
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
  }) 

  
</script>
  </body>
</html>