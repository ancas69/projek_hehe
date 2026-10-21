<!DOCTYPE html>
<html lang="en">
<!-- 
LINK TEMPLATE    :    https://github.com/azouaoui-med/pro-sidebar-template 
-->
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
<!--     <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <meta name="description" content="Responsive sidebar template with sliding effect and dropdown menu based on bootstrap 3">
<!-- untuk ajax profile matching process-->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SPK DOSBING</title>

    <!-- using online links -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
        integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
        integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <link rel="stylesheet" href="//malihu.github.io/custom-scrollbar/jquery.mCustomScrollbar.min.css">

    <!-- using local links -->
    <!-- <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../node_modules/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../node_modules/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css"> -->

    <link rel="stylesheet" href="{{ URL::asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/sidebar-themes.css') }}">
    <link rel="shortcut icon" type="image/png" href="{{ URL::asset('img/favicon.png') }}" />

    <!-- icons google -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        
    <!-- icon pengaturan -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- icon menu pojok kiri atas -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- sweetalert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    




    <style type="text/css">
      body{
        background-color: #eee;
      }

      blockquote {
          background: #FAFAFA;
          border-top: 1px solid #E8E8E8;
          border-bottom: 1px solid #E8E8E8;
          border-left: 0px solid #E8E8E8;
          border-right: 0px solid #E8E8E8;
          margin: 5px 5px 20px 30px;
          padding: 25px 30px 5px;
      }
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

/*start tab */

/*end tab */

    .warna_biru{
      background-color: rgba(0, 0, 255, 0.2);
    }

    .warna_hijau{
      background-color: rgba(0, 255, 0, 0.2);
    }


    </style>
</head>

<body>

    <div class="page-wrapper default-theme" style="">

        <nav id="sidebar" class="sidebar-wrapper" style="">
            <div class="sidebar-content">
                <!-- sidebar-brand  -->
                <div class="sidebar-item sidebar-brand">
                    <a href="#">SPK DOSBING</a>
                </div>
                <!-- sidebar-header  -->
                <div class="sidebar-item sidebar-header d-flex flex-nowrap">
                    <div class="user-pic">
                        <img class="img-responsive img-rounded" src="{{URL::asset('img/the-face.png')}}" alt="User picture">
                    </div>
                    <div class="user-info">
                        <span class="user-name">
                            {{ $data->nama }}
                        </span>
                        <span class="user-role">{{ $data->id_mahasiswa }}</span>
                        <span class="user-status">
                            <i class="fa fa-circle"></i>
                            <span>Online</span>
                        </span>
                    </div>
                </div>
                
                <!-- sidebar-menu  -->
                <div class=" sidebar-item sidebar-menu">
                    <ul>
                        <li class="header-menu">
                            <span>General</span>
                        </li>
                        
                        
                        <li>
                            <a href="{{url('/home')}}">
                                <i class="fas fa-tachometer-alt"></i>
                                <span class="menu-text">Home</span>
                                <!-- <span class="badge badge-pill badge-primary">Beta</span> -->
                            </a>
                        </li>
                        <li>
                            <a href="{{route('pembobotan')}}">
                                <i class="fab fa-searchengin"></i>
                                <span class="menu-text">SPK Profile Matching</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('Dosen')}}">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <span class="menu-text">Info Dosen</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- sidebar-menu  -->
            </div>
            <!-- sidebar-footer  -->
            <div class="sidebar-footer">
                <!-- <div class="dropdown">

                    <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bell"></i>
                        <span class="badge badge-pill badge-warning notification">3</span>
                    </a>
                    <div class="dropdown-menu notifications" aria-labelledby="dropdownMenuMessage">
                        <div class="notifications-header">
                            <i class="fa fa-bell"></i>
                            Notifications
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">
                            <div class="notification-content">
                                <div class="icon">
                                    <i class="fas fa-check text-success border border-success"></i>
                                </div>
                                <div class="content">
                                    <div class="notification-detail">Lorem ipsum dolor sit amet consectetur adipisicing
                                        elit. In totam explicabo</div>
                                    <div class="notification-time">
                                        6 minutes ago
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a class="dropdown-item" href="#">
                            <div class="notification-content">
                                <div class="icon">
                                    <i class="fas fa-exclamation text-info border border-info"></i>
                                </div>
                                <div class="content">
                                    <div class="notification-detail">Lorem ipsum dolor sit amet consectetur adipisicing
                                        elit. In totam explicabo</div>
                                    <div class="notification-time">
                                        Today
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a class="dropdown-item" href="#">
                            <div class="notification-content">
                                <div class="icon">
                                    <i class="fas fa-exclamation-triangle text-warning border border-warning"></i>
                                </div>
                                <div class="content">
                                    <div class="notification-detail">Lorem ipsum dolor sit amet consectetur adipisicing
                                        elit. In totam explicabo</div>
                                    <div class="notification-time">
                                        Yesterday
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-center" href="#">View all notifications</a>
                    </div>
                </div>
                <div class="dropdown">
                    <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-envelope"></i>
                        <span class="badge badge-pill badge-success notification">7</span>
                    </a>
                    <div class="dropdown-menu messages" aria-labelledby="dropdownMenuMessage">
                        <div class="messages-header">
                            <i class="fa fa-envelope"></i>
                            Messages
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">
                            <div class="message-content">
                                <div class="pic">
                                    <img src="img/user.jpg" alt="">
                                </div>
                                <div class="content">
                                    <div class="message-title">
                                        <strong> Jhon doe</strong>
                                    </div>
                                    <div class="message-detail">Lorem ipsum dolor sit amet consectetur adipisicing
                                        elit. In totam explicabo</div>
                                </div>
                            </div>

                        </a>
                        <a class="dropdown-item" href="#">
                            <div class="message-content">
                                <div class="pic">
                                    <img src="img/user.jpg" alt="">
                                </div>
                                <div class="content">
                                    <div class="message-title">
                                        <strong> Jhon doe</strong>
                                    </div>
                                    <div class="message-detail">Lorem ipsum dolor sit amet consectetur adipisicing
                                        elit. In totam explicabo</div>
                                </div>
                            </div>

                        </a>
                        <a class="dropdown-item" href="#">
                            <div class="message-content">
                                <div class="pic">
                                    <img src="img/user.jpg" alt="">
                                </div>
                                <div class="content">
                                    <div class="message-title">
                                        <strong> Jhon doe</strong>
                                    </div>
                                    <div class="message-detail">Lorem ipsum dolor sit amet consectetur adipisicing
                                        elit. In totam explicabo</div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-center" href="#">View all messages</a>

                    </div>
                </div> -->
                <div class="dropdown">
                    <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-cog"></i>
                        <!-- <span class="badge-sonar"></span> -->
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuMessage">
                        <a class="dropdown-item" href="{{route('mhsUpdate')}}">My profile</a>
                    </div>
                </div>
                <div>
                    <a id="tombol-logout" href="javascript:void(0)">
                        <i class="fa fa-power-off"></i>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
                <!-- <div class="pinned-footer">
                    <a onclick="event.preventDefault();document.getElementById('logout-form').submit();" href="{{ route('logout') }}">
                        <i class="fas fa-ellipsis-h"></i>
                    </a>
                </div> -->
            </div>
        </nav>
        <!-- page-content  -->
        <main class="page-content">
        <nav class="navbar navbar-light bg-light">
          <a id="toggle-sidebar" href="#" class="rounded-0 navbar-brand" style=" font-size: 40px; color: #000;" >
            <i class="material-icons" >menu</i>
          </a> 
        </nav>     
                
       
            <div id="overlay" class="overlay"></div>
            <div class="container-fluid p-5">
                <div class="row">
                    <div class="form-group col-md-12">
                        
                        <hr>
                        <h2>
                          @yield('judul_halaman')
                        </h2>
                        <p>@yield('keterangan_halaman')</p>

                    </div>
                    <!-- <div class="form-group col-md-12">
                        <iframe
                            src="https://ghbtns.com/github-btn.html?user=azouaoui-med&repo=pro-sidebar-template&type=star&count=true&size=small"
                            frameborder="0" scrolling="0" width="100px" height="30px"></iframe>
                        <iframe
                            src="https://ghbtns.com/github-btn.html?user=azouaoui-med&repo=pro-sidebar-template&type=fork&count=true&size=small"
                            frameborder="0" scrolling="0" width="100px" height="30px"></iframe>
                    </div> -->

                    <!-- <div class="form-group col-md-12">
                        <a id="toggle-sidebar" class="btn btn-secondary rounded-0" href="#">
                            <span>Side Menu</span>
                        </a>
                         <a id="pin-sidebar" class="btn btn-outline-secondary rounded-0" href="#">
                            <span>Pin Sidebar</span>
                        </a>

                    </div> -->
                </div>
                <hr>
                @yield('isi')
                
                <!-- <div class="row">
                    <div class="form-group col-md-12">
                        <h3>Themes</h3>
                        <p>Here are more themes that you can use</p>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-12">
                        <a href="#" data-theme="default-theme" class="theme default-theme selected"></a>
                        <a href="#" data-theme="chiller-theme" class="theme chiller-theme"></a>
                        <a href="#" data-theme="legacy-theme" class="theme legacy-theme"></a>
                        <a href="#" data-theme="ice-theme" class="theme ice-theme"></a>
                        <a href="#" data-theme="cool-theme" class="theme cool-theme"></a>
                        <a href="#" data-theme="light-theme" class="theme light-theme"></a>
                    </div>
                    <div class="form-group col-md-12">
                        <p>You can also use background image </p>
                    </div>
                    <div class="form-group col-md-12">
                        <a href="#" data-bg="bg1" class="theme theme-bg selected"></a>
                        <a href="#" data-bg="bg2" class="theme theme-bg"></a>
                        <a href="#" data-bg="bg3" class="theme theme-bg"></a>
                        <a href="#" data-bg="bg4" class="theme theme-bg"></a>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="toggle-bg" checked>
                            <label class="custom-control-label" for="toggle-bg">Background image</label>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="toggle-border-radius">
                            <label class="custom-control-label" for="toggle-border-radius">Border radius</label>
                        </div>
                    </div>

                </div> -->


                <!-- <hr>
                <div class="row ">
                    <div class="form-group col-md-12">
                        <small>Made with <i class="fa fa-heart text-danger" aria-hidden="true"></i> by <span
                                class="text-secondary font-weight-bold">Mohamed
                                Azouaoui</span></small>
                    </div>
                    <div class="form-group col-md-12">
                        <a href="https://github.com/azouaoui-med" target="_blank"
                            class="btn btn-sm bg-secondary shadow-sm rounded-0 text-light mr-3 mb-3">
                            <i class="fab fa-github" aria-hidden="true"></i>
                        </a>
                        <a href="https://twitter.com/azouaoui_med" target="_blank"
                            class="btn btn-sm bg-secondary shadow-sm rounded-0 text-light mr-3 mb-3">
                            <i class="fab fa-twitter" aria-hidden="true"></i>
                        </a>
                        <a href="https://www.instagram.com/azouaoui_med/" target="_blank"
                            class="btn btn-sm bg-secondary shadow-sm rounded-0 text-light mr-3 mb-3">
                            <i class="fab fa-instagram" aria-hidden="true"></i>
                        </a>
                        <a href="https://www.linkedin.com/in/mohamed-azouaoui/" target="_blank"
                            class="btn btn-sm bg-secondary shadow-sm rounded-0 text-light mr-3 mb-3">
                            <i class="fab fa-linkedin-in" aria-hidden="true"></i>
                        </a>
                    </div>
                </div> -->
            </div>
        </main>
        <!-- page-content" -->
    </div>
    <!-- page-wrapper -->

    <!-- using online scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
        integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
        integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous">
    </script>
    <script src="//malihu.github.io/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>

    <!-- using local scripts -->
    <!-- <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../node_modules/popper.js/dist/umd/popper.min.js"></script>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../node_modules/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script> -->
    <!-- {{ URL::asset('template/fonts/icomoon/style.css') }} -->

    <script src="{{ URL::asset('js/main.js') }}"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        // $('#formTopikSistemCerdas').hide();
        // $('#formTopikRelata').hide();
        // $('#progres_profile_matching').hide();
        // $('#load_result_prfile_matching').hide();



        $("#search-div").on("keyup", function() {
          var value = $(this).val().toLowerCase();
          $("#cari-disini .item-yang-dicari").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
          });
        });
        $('#persentase').change(function(){
          var perc = $('#persentase').val();
           $('#NCF').text(100-perc+'%')
           $('#NSF').text(perc+'%')
           $('#id_NCF').val(100-perc)
           $('#id_NSF').val(perc)
        })
        var perc = $('#persentase').val();
        $('#NCF').text(100-perc+'%')
        $('#NSF').text(perc+'%')
        $('#id_NCF').val(100-perc)
        $('#id_NSF').val(perc)
          
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



        $('#btndownloadFileCalonMhs').click(function(){
          $('#formdownloadFileCalonMhs').submit();

        })

        $('#btnhapusCalonMhs').click(function(){
            $('#formhapusCalonMhs').submit();
        })

        $('#btnPerbaharuiPengajuan').click(function(){
            $('#formPerbaharuiPengajuan').submit();
        })


        $('#gap_pm_alert').text("Tekan Tombol Proses Data");
        $('#total_pm_alert').text("Tekan Tombol Proses Data");
        $('#hasil_pm_alert').text("Tekan Tombol Proses Data");

        

        var limit = 4;
        $('input[type=checkbox]').on('change', function (e) {
            if ($('input[type=checkbox]:checked').length > limit) {
                $(this).prop('checked', false);
                alert("allowed only 4");
            }
        });

        $('#tombol-kirim-subKriteria').click(function(){
         var jumlah_yang_di_cek;
         
          $('#progres_profile_matching').show();

         
         jumlah_yang_di_cek = ($('.checktp').filter(':checked').length);





         var cek_bmin = $('input[name=bminE]').filter(':checked').length;

         var cek_pend = $('input[name=pendE]').filter(':checked').length;
         var cek_prgbim = $('input[name=prgbimE]').filter(':checked').length;
         var cek_prgpen = $('input[name=prgpenE]').filter(':checked').length;

         
          
          
         

         console.log(jumlah_yang_di_cek);

         if(jumlah_yang_di_cek<5 && jumlah_yang_di_cek >=1 && cek_bmin > 0 && cek_pend > 0 && cek_prgbim > 0 && cek_prgpen > 0)
         {
          $("#peringatan-topik").text("");
          var myForm = document.getElementById('formSubKriteria');
          var dataFormSubKriteria = new FormData(myForm);
          

          var topikF = document.getElementsByName('topik[]'); 

          var topikJS= [];
          var j=0;

          for (var i = 0; i < topikF.length; i++) { 
            if(topikF[i].checked){
              topikJS[j] = topikF[i].value;
              j++;
            }
          } 

          $('#gap_pm_alert').text("tunggu . . .");
          $('#total_pm_alert').text("tunggu . . .");
          $('#hasil_pm_alert').text("tunggu . . .");
          console.log(topikJS);

          
          $.ajax({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              xhr: function() {
                  var xhr = new window.XMLHttpRequest();

                  //Upload progress
                  xhr.upload.addEventListener("progress", function(evt){
                      if (evt.lengthComputable) {
                          var percentComplete = evt.loaded / evt.total;
                          //Do something with upload progress
                          console.log(percentComplete);
                          console.log("persentase : "+Math.floor(evt.loaded / evt.total *100) + '%');
                          document.getElementById('progres_profile_matching').value = Math.floor((evt.loaded / evt.total)*100);
                      }
                  }, false);

                  // xhr: function() {
                  //   var xhr = $.ajaxSettings.xhr();
                  //   xhr.upload.onprogress = function(e) {
                  //       console.log(Math.floor(e.loaded / e.total *100) + '%');
                  //   };
                  //   return xhr;
                  // },
                 
                 // Download progress
                 // xhr.addEventListener("progress", function(evt){
                 //     if (evt.lengthComputable) {
                 //         var percentComplete = evt.loaded / evt.total;
                 //         // Do something with download progress
                 //         console.log("donlot : "+percentComplete);
                 //     }
                 // }, false);
                 
                 return xhr;
              },
              type: 'post',
              url: '{{ route("processAll") }}',
              data: {
                pendE : dataFormSubKriteria.get('pendE'), 
                bminE : dataFormSubKriteria.get('bminE'),
                prgbimE : dataFormSubKriteria.get('prgbimE'),
                prgpenE : dataFormSubKriteria.get('prgpenE'),
                topik : topikJS,
              },
              success: function(data){
                  // Do something success-ish
                  console.log(data);
                  
                  
                  $('#load_result_prfile_matching').show();
                  $('#gap_pm').load("{{route('peta')}}", function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      $('#gap_pm_alert').text("diperbaharui");
                    if(statusTxt == "error")
                      $('#gap_pm_alert').text("gagal");
                  });
                  $('#total_pm').load("{{route('perhitunganTotal')}}", function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      $('#total_pm_alert').text("diperbaharui");
                    if(statusTxt == "error")
                      $('#total_pm_alert').text("gagal");
                  });
                  $('#hasil_pm').load("{{route('hasilAkhir')}}", function(responseTxt, statusTxt, xhr){
                    if(statusTxt == "success")
                      $('#hasil_pm_alert').text("diperbaharui");
                    document.getElementById('progres_profile_matching').value = 0;
                    if(statusTxt == "error")
                      $('#hasil_pm_alert').text("gagal");
                    document.getElementById('progres_profile_matching').value = 0;
                  });

              }
          });          
          //$('#formSubKriteria').submit();
         }
         else if(jumlah_yang_di_cek == 0 || cek_bmin == 0 || cek_pend == 0 || cek_prgbim == 0 || cek_prgpen == 0){
            var text = "";

            if(jumlah_yang_di_cek == 0)
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
         else {
            alert("topik melebihi batas");
         }
        })












        $("input[name=bminE][value='"+$('#handlerBidMin').val()+"']").prop('checked', true);
        if($("input[name=bminE][value='"+$('#handlerBidMin').val()+"']").is(":checked")){
            
            $('label[for=bminE'+$('#handlerBidMin').val()+']').addClass('alert-primary')
            console.log("checked")


            var penampung = $('#penampung_topik_mhs').val();
            var strArray = penampung.split("#");
            for(var i = 0; i < strArray.length; i++){
                console.log(strArray[i]); 
                $(":checkbox[value='"+strArray[i]+"']").attr("checked","true");    
            }

        }

        $("input[name=pendE][value='"+$('#handlerPend').val()+"']").prop('checked', true);
        if($("input[name=pendE][value='"+$('#handlerPend').val()+"']").is(":checked")){
            
            $('label[for=pendE'+$('#handlerPend').val()+']').addClass('alert-primary')
            console.log("checked")
        }


        $("input[name=prgbimE][value='"+$('#handlerProgBim').val()+"']").prop('checked', true);
        if($("input[name=prgbimE][value='"+$('#handlerProgBim').val()+"']").is(":checked")){
            
            $('label[for=prgbimE'+$('#handlerProgBim').val()+']').addClass('alert-primary')
            console.log("checked")
        }


        $("input[name=prgpenE][value='"+$('#handlerProgPen').val()+"']").prop('checked', true);
        if( $("input[name=prgpenE][value='"+$('#handlerProgPen').val()+"']").is(":checked")){
            
            $('label[for=prgpenE'+$('#handlerProgPen').val()+']').addClass('alert-primary')
            console.log("checked")
        }


        $('#tombol-logout').click(function(){
          var c = confirm('logout ?');
          if(c){
            event.preventDefault();document.getElementById('logout-form').submit();
          
          }
        })


        $('#form_input_file').bind('change', function() {

          //this.files[0].size gets the size of your file.
          if(this.files[0].size > 1048576){
            alert("ukuran file terlalu besar");
            $('#form_input_file').val(null);
          }
          

        });

        $("td.pilihan_topik select").val($('#topik_mhs').val()).change();

        console.log($('#topik_mhs').val())

















        
// href="{{route('peta')}}"
// href="{{route('perhitunganTotal')}}"
// href="{{route('hasilAkhir')}}"
        // $('#').click(function(){
          
        // })
        // $('#').click(function(){
          
        // })
        // $('#').click(function(){
          
        // })


      })


      function filter(fil){ 
        $('#search-filter').val(fil);

        var value = fil.toLowerCase();
        $("#cari-disini .item-yang-dicari").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      }






      // function _(el){
      //   return document.getElementById(el);
      // }
      // function uploadFile(){
      //   //var file = _("file1").files[0];
      //   // alert(file.name+" | "+file.size+" | "+file.type);
      //   var myForm = document.getElementById('upload_resume_form');
      //   var formdata = new FormData(myForm);
      //   //formdata.append("file1", file);
      //   var ajax = new XMLHttpRequest();
      //   ajax.upload.addEventListener("progress", progressHandler, false);
      //   ajax.addEventListener("load", completeHandler, false);
      //   ajax.addEventListener("error", errorHandler, false);
      //   ajax.addEventListener("abort", abortHandler, false);
      //   ajax.open("POST", "{{route('tambahFile')}}");
      //   ajax.send(formdata);
      // }
      // function progressHandler(event){
      //   _("loaded_n_total").innerHTML = "Uploaded "+event.loaded+" bytes of "+event.total;
      //   var percent = (event.loaded / event.total) * 100;
      //   _("progressBar").value = Math.round(percent);
      //   _("status").innerHTML = Math.round(percent)+"% uploaded... please wait";
      // }
      // function completeHandler(event){
      //   _("status").innerHTML = event.target.responseText;
      //   _("progressBar").value = 0;
      // }
      // function errorHandler(event){
      //   _("status").innerHTML = "Upload Failed";
      // }
      // function abortHandler(event){
      //   _("status").innerHTML = "Upload Aborted";
      // }



      
    </script>
</body>

</html>