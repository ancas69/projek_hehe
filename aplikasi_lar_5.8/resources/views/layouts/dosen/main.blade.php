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
                        @if(isset($data->foto))
                        <img class="card-img-top" src="data:image/png;base64,{{base64_encode($data->foto)}}">
                        @else
                        <img class="img-responsive img-rounded" src="{{URL::asset('img/the-face.png')}}" alt="User picture">
                        @endif
                    </div>
                    <div class="user-info">
                        <span class="user-name">
                            {{ $data->nama }}
                        </span>
                        <span class="user-role">{{ $data->id_dosen }}</span>
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

                        <li class="sidebar-dropdown">
                            <a href="#">
                                <i class="fas fa-file-signature"></i>
                                <span class="menu-text">Calon Mhs Bimbingan</span>
                            </a>
                            <div class="sidebar-submenu">
                                <ul>
                                    <li>
                                        <a href="{{route('daftarMahasiswa',['id' => 'Menunggu' ])}}">
                                            Menunggu
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('daftarMahasiswa',['id' => 'Tolak' ])}}">Ditolak</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="sidebar-dropdown">
                            <a href="#">
                                <i class="fa fa-file-contract"></i>
                                <span class="menu-text">Mhs. Bimbingan</span>
                            </a>
                            <div class="sidebar-submenu">
                                <ul>
                                    <li>
                                        <a href="{{route('daftarMahasiswa',['id' => 'Terima' ])}}">
                                            Sedang Meneliti
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('daftarMahasiswa',['id' => 'Selesai' ])}}">Selesai</a>
                                    </li>
                                </ul>
                            </div>
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
                        <a class="dropdown-item" href="{{route('dsnUpdate')}}">My profile</a>
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


      
      // function tambahcekCal($id_calon){       
      //   $(".tambah_cekCal").append("<input name='cek_id_calon[]' type='' value='"+$id_calon+"'>");       
      // }


      $(document).ready(function(){
        // $("#tempat_sembunyi").hide();





        $("#search-div").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#cari-disini .item-yang-dicari").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
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

















        $('.color_filter').click(function(){
          $('.color_filter').removeClass('btn-primary')
          $('.color_filter').addClass('btn-secondary')
          $(this).removeClass('btn-secondary')
          $(this).addClass('btn-primary')
        })












        $(".the_cebtndel").click(function(){
            
            if($('.the_ceboxCal').is(":checked")){

                $('#statIdCekCalon').val('hapus');
                
                $('#the_ceformupdt').submit();
            }else if($('.the_ceboxCal').is(":not(:checked)")){
                alert("Tidak ada yang ditandai");
            }
        });

        $(".the_cebtnter").click(function(){
            var jumlah_yang_diterima =($('.the_ceboxCal').filter(':checked').length);
            console.log(jumlah_yang_diterima);
            if($('.the_ceboxCal').is(":checked") ){
                if(jumlah_yang_diterima <= $('#sisa_slot').val() ){
                    $('#statIdCekCalon').val('terima');
                    $('#the_ceformupdt').submit();
                }else{
                    alert("Slot bimbingan hanya tersisa "+$('#sisa_slot').val()+".");
                }
            }else if($('.the_ceboxCal').is(":not(:checked)")){
                alert("Tidak ada yang ditandai");
            }
        });

        $(".the_cebtntol").click(function(){
            if($('.the_ceboxCal').is(":checked")){
                $('#statIdCekCalon').val('tolak');
                $('#the_ceformupdt').submit();
            }else if($('.the_ceboxCal').is(":not(:checked)")){
                alert("Tidak ada yang ditandai");
            }
        });

        $(".the_cebtnsel").click(function(){
            if($('.the_ceboxCal').is(":checked")){
                $('#statIdCekCalon').val('selesai');
                $('#the_ceformupdt').submit();
            }else if($('.the_ceboxCal').is(":not(:checked)")){
                alert("Tidak ada yang ditandai");
            }
        });











        $('#terimaDetailCalonBtn').click(function(){
            $('#statIdDetailCalon').val('terima');
            $('#subDetailCalon').submit();
        })

        $('#tolakDetailCalonBtn').click(function(){
            $('#statIdDetailCalon').val('tolak');
            $('#subDetailCalon').submit();
        })

        $('#selesaiDetailCalonBtn').click(function(){
            $('#statIdDetailCalon').val('selesai');
            $('#subDetailCalon').submit();
        })



        $('#btnDownload').click(function(){
            $('#lakukanDownload').submit();
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



        $("input[name=bminE][value='"+$('#handlerBidMin').val()+"']").prop('checked', true);
        if($("input[name=bminE][value='"+$('#handlerBidMin').val()+"']").is(":checked")){
            
            $('label[for=bminE'+$('#handlerBidMin').val()+']').addClass('alert-primary')
            console.log("checked")

            
        }

        var penampung = $('#penampung_topik_').val() ? $('#penampung_topik_').val() : "ko#song";
        var strArray = penampung.split("#");
        for(var i = 0; i < strArray.length; i++){
          console.log(strArray[i]); 
          $(":checkbox[value='"+strArray[i].toUpperCase()+"']").attr("checked","true");    
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



        $('#foto_dosen_cek').bind('change', function() {

            //this.files[0].size gets the size of your file.
            if(this.files[0].size > 1048576){
              alert("ukuran file terlalu besar");
              $('#foto_dosen_cek').val(null);
            }
            

        });

        $('#tombol-logout').click(function(){
          var c = confirm('logout ?');
          if(c){
            event.preventDefault();document.getElementById('logout-form').submit();
          
          }
        })

        $('#tombol_save_identitas_dosen').click(function(){
            var cek_topik = ($('.checktp').filter(':checked').length);
            var cek_bmin = $('input[name=bminE]').filter(':checked').length;
            var cek_pend = $('input[name=pendE]').filter(':checked').length;
            var cek_prgbim = $('input[name=prgbimE]').filter(':checked').length;
            var cek_prgpen = $('input[name=prgpenE]').filter(':checked').length;
            if(cek_topik>0 && cek_bmin > 0 && cek_pend > 0 && cek_prgbim > 0 && cek_prgpen > 0)
            {
                document.getElementById("formupdateidentitasdosen").submit();
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


        $('#cek_all').click(function(){
            $(".the_ceboxCal").prop("checked",true);
        })

        $('#uncek_all').click(function(){
            $(".the_ceboxCal").prop("checked",false);
        })

        // $(".edit_topik").click(function(){
        //     var id = $(this).attr("data-id");
        //     console.log("edit topik = "+id);
        //     $("#"+id).appendTo("#tempat_sembunyi");
        // })

        $(".tombol_hapus_topik").click(function(){
            var id = $(this).data("id");

            var cek = confirm("akan menghapus topik "+$(this).data("topik"));

            if(cek == true){
                $("#name_hapus_topik").val(id);
                console.log(id);
                $("#form_tombol_hapus_topik").submit();
            }

        })

        $("#tombol_tambah_topik").click(function(){
            var topik_tambah = $("#input_new_topik").val();

            $("#name_tambah_topik").val(topik_tambah);

            $("#form_tombol_tambah_topik").submit();

        })

    });



    function filter(fil){
        $('#search-filter').val(fil);
        console.log(fil);
        var value = fil.toLowerCase();
        $("#cari-disini .item-yang-dicari").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    }








      
    </script>
</body>

</html>