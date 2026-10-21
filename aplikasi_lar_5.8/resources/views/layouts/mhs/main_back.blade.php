<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Source+Serif+Pro:400,600&display=swap" rel="stylesheet">

    <!-- icons google -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="{{ URL::asset('template/fonts/icomoon/style.css') }}">

    <link rel="stylesheet" href="{{ URL::asset('template/css/owl.carousel.min.css') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ URL::asset('template/css/bootstrap.min.css') }}">
    
    <!-- Style -->
    <link rel="stylesheet" href="{{ URL::asset('template/css/style.css') }}">

    <!-- icon pengaturan -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- icon panah -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <title>SPK DOSBING</title>
    <style type="text/css">
      /*radio css*/

 

      

      /*endradio css*/

      .tengah{
        vertical-align: middle;
      }
      .red{
        background-color: red;
      }

      /*start slider persentase*/
      .slider {
        -webkit-appearance: none;
        width: 100%;
        height: 15px;
        border-radius: 5px;  
        background: #d3d3d3;
        outline: none;
        opacity: 0.7;
        -webkit-transition: .2s;
        transition: opacity .2s;
      }

      .slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 25px;
        height: 25px;
        border-radius: 50%; 
        background: #04AA6D;
        cursor: pointer;
      }

      .slider::-moz-range-thumb {
        width: 25px;
        height: 25px;
        border-radius: 50%;
        background: #04AA6D;
        cursor: pointer;
      }
      /*end slider*/

      /*start kode card dosen*/

      .twPc-div {
          background: #fff none repeat scroll 0 0;
          border: 1px solid #e1e8ed;
          border-radius: 6px;
          /*height: 200px;*/
          max-width: 340px; // orginal twitter width: 290px;
      }
      .twPc-bg {
          background-image: url("https://pbs.twimg.com/profile_banners/50988711/1384539792/600x200");
          background-position: 0 50%;
          background-size: 100% auto;
          border-bottom: 1px solid #e1e8ed;
          border-radius: 4px 4px 0 0;
          height: 95px;
          width: 100%;
      }
      .twPc-block {
          display: block !important;
      }
      .twPc-button {
          margin: -35px -10px 0;
          text-align: right;
          width: 100%;
      }
      .twPc-avatarLink {
          background-color: #fff;
          border-radius: 6px;
          display: inline-block !important;
          float: left;
          margin: -30px 5px 0 8px;
          max-width: 100%;
          padding: 1px;
          vertical-align: bottom;
      }
      .twPc-avatarImg {
          border: 2px solid #fff;
          border-radius: 7px;
          box-sizing: border-box;
          color: #fff;
          height: 72px;
          width: 72px;
      }
      .twPc-divUser {
          margin: 5px 0 0;
      }
      .twPc-divName {
          font-size: 18px;
          font-weight: 700;
          line-height: 21px;
      }
      .twPc-divName a {
          color: inherit !important;
      }
      .twPc-divStats {
          width: 100%;
          /*border: solid 1px;*/
          position: absolute;
          bottom: 0;
      }
      .twPc-Arrange {
          box-sizing: border-box;
          display: table;
          margin: 0;
          min-width: 100%;
          padding: 0;
          table-layout: auto;
      }
      ul.twPc-Arrange {
          list-style: outside none none;
          margin: 0;
          padding: 0;
      }
      .twPc-ArrangeSizeFit {
          display: table-cell;
          padding: 0;
          vertical-align: top;
      }
      .twPc-ArrangeSizeFit a:hover {
          text-decoration: none;
      }
      .twPc-StatValue {
          display: block;
          font-size: 18px;
          font-weight: 500;
          transition: color 0.15s ease-in-out 0s;
      }
      .twPc-StatLabel {
          color: #8899a6;
          font-size: 10px;
          letter-spacing: 0.02em;
          overflow: hidden;
          text-transform: uppercase;
          transition: color 0.15s ease-in-out 0s;
      }

      /*end kode card dosen*/

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
          <h3 class="name">{{ $data->nama }}</h3>
          <span class="country">{{ $data->id_mahasiswa }}</span>
        </div>

        
        <div class="nav-menu">
          <ul>
            <li><a href="{{url('/home')}}"><span class="icon-home mr-3"></span>Home</a></li>
             <li><a href="{{route('pembobotan')}}"><span class="icon-search2 mr-3"></span>SPK Dosbing</a></li>

            <li><a href="{{route('Dosen')}}"><span class="icon-pie-chart mr-3"></span>Info Dosen</a></li>
            <li><a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
              <span class="icon-sign-out mr-3"></span>{{ __('Logout') }}</a></li>
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
          <a href="{{route('mhsUpdate')}}" class="float">
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
  $(document).ready(function(){
    $("#search-div").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $("#cari-disini .item-yang-dicari").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
    $('#persentase').change(function(){
      var perc = $('#persentase').val();
       $('#akademik').text(perc+'%')
       $('#penelitian').text(100-perc+'%')
       $('#id_akademik').val(perc)
       $('#id_penelitian').val(100-perc)
    })
    var perc = $('#persentase').val();
    $('#akademik').text(perc+'%')
    $('#penelitian').text(100-perc+'%')
    $('#id_akademik').val(perc)
    $('#id_penelitian').val(100-perc)
      
    // $('#puppop').hide();
    // var n;
    // $('#tombol-reset').click(function(){
    //   n=100;
    //   $('#al').val(n);
    //   if((n)!=100){
    //     $('#tombol-kirim').attr('disabled',''); 
    //     $('#puppop').show();
    //   }
    //   else{
    //     $('#tombol-kirim').removeAttr('disabled'); 
    //     $('#puppop').hide();
    //   }
    // })
    // $('#rpl, #sup, #sc').change(function(){
    //   var a = Number($('#rpl').val())
    //   var b = Number($('#sup').val())
    //   var c = Number($('#sc').val())
    //   n=a+b+c;
    //   $('#al').val(n);
    //   if((n)!=100){
    //     $('#tombol-kirim').attr('disabled',''); 
    //     $('#puppop').show();
    //   }
    //   else{
    //     $('#tombol-kirim').removeAttr('disabled'); 
    //     $('#puppop').hide();
    //   }
    // })
    // var a = Number($('#rpl').val())
    // var b = Number($('#sup').val())
    // var c = Number($('#sc').val())
    // n=a+b+c;
    // $('#al').val(n);

    // rangeVal('#progres','#progres-cur');
    // rangeVal('#spk','#spk-cur');
    // rangeVal('#pakar','#pakar-cur');
    // rangeVal('#dm','#dm-cur');
    // rangeVal('#stbi','#stbi-cur');
    // rangeVal('#robot','#robot-cur');
    // rangeVal('#grafkom','#grafkom-cur');
    // rangeVal('#kompvisi','#kompvisi-cur');
    // rangeVal('#kk','#kk-cur');
    // rangeVal('#df','#df-cur');
    // rangeVal('#net','#net-cur');
    // rangeVal('#rpll','#rpll-cur');
    // rangeVal('#mm','#mm-cur');
    // rangeVal('#bimbingan','#bimbingan-cur');

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

    $('.color_filter').click(function(){
      $('.color_filter').removeClass('btn-primary')
      $('.color_filter').addClass('btn-secondary')
      $(this).removeClass('btn-secondary')
      $(this).addClass('btn-primary')
    })

    $('.auto_sub').change(function(){
      $(".auto_sub").submit();
    })




  })


  function filter(fil){
    
    
    $('#search-filter').val(fil);

    var value = fil.toLowerCase();
    $("#cari-disini .item-yang-dicari").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  }



  
  </script>
  </body>
</html>