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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Responsive sidebar template with sliding effect and dropdown menu based on bootstrap 3">
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

    <!-- ckeditor text area -->
    <script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>

    <!-- icon menu pojok kiri atas -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style type="text/css">
        .tombol_home:hover{
            color: #4DFF5B;
        }

        /*css switch*/
        .switch {
            position: relative;
            display: block;
            vertical-align: top;
            width: 100px;
            height: 30px;
            padding: 3px;
            margin: 0 10px 10px 0;
            background: linear-gradient(to bottom, #eeeeee, #FFFFFF 25px);
            background-image: -webkit-linear-gradient(top, #eeeeee, #FFFFFF 25px);
            border-radius: 18px;
            box-shadow: inset 0 -1px white, inset 0 1px 1px rgba(0, 0, 0, 0.05);
            cursor: pointer;
            box-sizing:content-box;
        }
        .switch-input {
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            box-sizing:content-box;
        }
        .switch-label {
            position: relative;
            display: block;
            height: inherit;
            font-size: 10px;
            text-transform: uppercase;
            background: #eceeef;
            border-radius: inherit;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.12), inset 0 0 2px rgba(0, 0, 0, 0.15);
            box-sizing:content-box;
        }
        .switch-label:before, .switch-label:after {
            position: absolute;
            top: 50%;
            margin-top: -.5em;
            line-height: 1;
            -webkit-transition: inherit;
            -moz-transition: inherit;
            -o-transition: inherit;
            transition: inherit;
            box-sizing:content-box;
        }
        .switch-label:before {
            content: attr(data-off);
            right: 11px;
            color: #aaaaaa;
            text-shadow: 0 1px rgba(255, 255, 255, 0.5);
        }
        .switch-label:after {
            content: attr(data-on);
            left: 11px;
            color: #FFFFFF;
            text-shadow: 0 1px rgba(0, 0, 0, 0.2);
            opacity: 0;
        }
        .switch-input:checked ~ .switch-label {
            background: #E1B42B;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.15), inset 0 0 3px rgba(0, 0, 0, 0.2);
        }
        .switch-input:checked ~ .switch-label:before {
            opacity: 0;
        }
        .switch-input:checked ~ .switch-label:after {
            opacity: 1;
        }
        .switch-handle {
            position: absolute;
            top: 4px;
            left: 4px;
            width: 28px;
            height: 28px;
            background: linear-gradient(to bottom, #FFFFFF 40%, #f0f0f0);
            background-image: -webkit-linear-gradient(top, #FFFFFF 40%, #f0f0f0);
            border-radius: 100%;
            box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
        }
        .switch-handle:before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            margin: -6px 0 0 -6px;
            width: 12px;
            height: 12px;
            background: linear-gradient(to bottom, #eeeeee, #FFFFFF);
            background-image: -webkit-linear-gradient(top, #eeeeee, #FFFFFF);
            border-radius: 6px;
            box-shadow: inset 0 1px rgba(0, 0, 0, 0.02);
        }
        .switch-input:checked ~ .switch-handle {
            left: 74px;
            box-shadow: -1px 1px 5px rgba(0, 0, 0, 0.2);
        }
         
        /* Transition
        ========================== */
        .switch-label, .switch-handle {
            transition: All 0.3s ease;
            -webkit-transition: All 0.3s ease;
            -moz-transition: All 0.3s ease;
            -o-transition: All 0.3s ease;
        }

        /*end css switch*/
    </style>
</head>

<body style="background: #eee;">
    <div class="page-wrapper default-theme toggled">
        <nav id="sidebar" class="sidebar-wrapper">
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
                            Admin
                        </span>
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
                            <a href="{{route('dataDosen')}}">
                                <i class="fab fa-searchengin"></i>
                                <span class="menu-text">Data Dosen</span>
                            </a>
                        </li>
                        

                        <li>
                            <a href="{{route('subKriteriaDosen')}}">
                                <i class="fab fa-searchengin"></i>
                                <span class="menu-text">Buka Tutup Pengajuan Penelitian</span>
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
<!--         <div class="" style="background: #1d1d1d; height: 100px; width: 100%;">
            header
        </div> -->
        <main class="page-content">
        <nav class="navbar navbar-light bg-light">
          <a id="toggle-sidebar" href="#" class="rounded-0 navbar-brand" style=" font-size: 40px; color: #000;" >
            <i class="material-icons" >menu</i>
          </a> 
        </nav>  
            <div id="overlay" class="overlay"></div>
            <div class="container-fluid p-5">
                <div class="row">
                    

                    <div class="form-group col-md-12 ">
                        
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
        </main><!-- 
        <div class="" style="background: #1d1d1d; height: 500px; width: 100%;">
           <div class="row">
               <div class="col-sm">
                   <p style="color: ">Skripsi UAD 2020-2021</p>
               </div>
           </div>
        </div> -->
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

        $("#theTahunAjaran").change(function(){
           var tahun_sekarang =  new Date().getFullYear();
           var selisih_tahun = Math.abs(tahun_sekarang - $("#theTahunAjaran").val());
           var value_tahun_sekarang = $("#theTahunAjaran").val();
           if(selisih_tahun > 1)
           {
            alert("tahun terlalu jauh");
            $("#theTahunAjaran").val(tahun_sekarang);
            $("#futureY").val(parseInt($("#theTahunAjaran").val())+1);
           }else{
            $("#futureY").val(parseInt(value_tahun_sekarang)+1);
           }


        })

        // $("#futureY").change(function(){
        //     var tahun_sekarang =  new Date().getFullYear();
        //     var selisih_tahun = $("#theTahunAjaran").val() - $("#futureY").val();
        //     console.log($("#theTahunAjaran").val() +"-"+ $("#futureY").val()+"="+selisih_tahun);
        //     if(selisih_tahun > 0 || selisih_tahun == 0 || selisih_tahun < 1)
        //     {
        //      alert("tahun tidak valid");
        //      $("#futureY").val(tahun_sekarang+1);

        //     }


        // })

        $(".semester").click(function(){
            $("#temp_semester").val($(this).val());
        })


        $(".pengajuan_cb").change(function(){
            var id = $(this).data('id');
            var tahun = "";
            var cekcek = true;
            var batalkan = false;
            
            if($(this).is(":checked")==true){
                console.log("menyala");
                if ($('#temp_semester').val() == "belum_di_atur") {
                
                    // buat dia tetap mati
                    $(this).prop('checked', false);
                    alert("Semester belum di atur");
                    batalkan = true;
                }
                
            }
            else if($(this).is(":checked")==false){
                console.log("matui");
                // buat dia tetap menyala

                var cek = confirm("akan menutup pengajuan dosen dengan id "+id);
                console.log("hasil si cek = "+cek);

                cekcek = cek;
                
            }
            

            if( cekcek == false){
                $(this).prop('checked', true);
                console.log("massuuuuuuk");
            }else if(batalkan!=true){

                $("#stat_loading"+id).text("wait...");
                var status;
                console.log(id+"="+$(this).is(":checked"));

                if($(this).is(":checked")==true){
                    status = $("#temp_semester").val();
                    tahun = $("#theTahunAjaran").val()+"-"+$("#futureY").val();
                }
                else{
                    status = "tutup";
                }



                console.log(status);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },              
                    type: 'post',
                    url: '{{ route("simpanDataSatuPeriode") }}',
                    data: {
                    id_dosen : id,
                    status_ : status,
                    tahun_ : tahun,
                    },
                    success: function(data){
                      // Do something success-ish
                      console.log(data);

                      $("#stat_loading"+id).text("ok");

                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        $("#stat_loading"+id).text("fail :("); 
                    }
                });




            }

        })
        



 

        



        $('#puppop').hide();
        

        $('.auto_sub').change(function(){
          $(".auto_sub").submit();
        })

        $('#link_group').keyup(function(){
          $('.link2').val($('#link_group').val())
        })
        


































        $('#openGanjil').click(function(){
            $('#statIdPendaftaran').val('ganjil');
            
            $('#subformPendaftaran').submit();
        })
        $('#openGenap').click(function(){
            $('#statIdPendaftaran').val('genap');
            
            $('#subformPendaftaran').submit();
        })
        $('#closePenelitian').click(function(){
            
            $('#statIdPendaftaran').val('tutup');
            $('#subformPendaftaran').submit();
        })





        $('#tombol-logout').click(function(){
          var c = confirm('logout ?');
          if(c){
            event.preventDefault();document.getElementById('logout-form').submit();
          
          }
        })

     



    });









      
    </script>
</body>

</html>