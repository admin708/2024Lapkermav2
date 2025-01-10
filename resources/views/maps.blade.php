<!DOCTYPE html>
<html lang="en">

<head>

    <!-- ========== Meta Tags ========== -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Examin - Education and LMS Template">

    <!-- ========== Page Title ========== -->
    <title>Laporan Kerjasama | UNHAS</title>

    <!-- ========== Favicon Icon ========== -->
    <link rel="shortcut icon" href="{{ asset('new_template/assets/img/unhas.png') }}" type="image/x-icon">

    <!-- ========== Start Stylesheet ========== -->
    <link href="{{ asset('new_template/assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('new_template/assets/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('new_template/assets/css/flaticon-set.css') }}" rel="stylesheet" />
    <link href="{{ asset('new_template/assets/css/elegant-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('new_template/assets/css/magnific-popup.css') }}" rel="stylesheet" />
    <link href="{{ asset('new_template/assets/css/owl.carousel.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('new_template/assets/css/owl.theme.default.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('new_template/assets/css/animate.css') }}" rel="stylesheet" />
    <link href="{{ asset('new_template/assets/css/bootsnav.css') }}" rel="stylesheet" />
    <link href="{{ asset('new_template/assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('new_template/assets/css/responsive.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <!-- ========== End Stylesheet ========== -->

    <!-- ========== Google Fonts ========== -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700,800" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/svg-pan-zoom@3.6.1/dist/svg-pan-zoom.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/StephanWagner/svgMap@v2.10.1/dist/svgMap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/gh/StephanWagner/svgMap@v2.10.1/dist/svgMap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    {{-- Leaftlet --}}
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <style>
        .top3 {
            margin-top: 3.5rem;
            margin-right: 1rem;
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
            padding-left: 0.625rem;
            font-size: 1.1rem;
            border-radius: 0.25rem;
        }

        #map-kerjasama {
            width: 100%;
            height: 600px;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
    @livewireStyles
</head>

<body>
    <!-- Start Header Top
    ============================================= -->
    <div class="top-bar-area address-two-lines bg-dark text-light">
        <div class="container">
            <div class="row">
                <div class="col-md-2 address-info">
                    <div class="info box">
                        <ul>
                            @foreach (\App\Models\ContactInfo::where('status', 1)->get() as $item)
                                <li>
                                    <span><img src="{{ asset('new_template/assets/img/whatsappwhite.png') }}"
                                            style="margin-right: 3px" height="15">{{ $item->nama }}</span>
                                    <a class="" href="https://wa.me/{{ $item->no_hp }}?text=" target="blank">
                                        +{{ $item->no_hp }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div style="display:flex; flex-direction:row-reverse">
                    @if (auth()->check())
                        <div class="user-login text-right">
                            <a href="{{ route('index') }}">
                                <i class="fas fa-table"></i>
                                {{ auth()->user()->role_id == 6 ? 'Guest Input' : 'Admin Panel' }}
                            </a>
                        </div>
                    @else
                        <div class="user-login text-right">
                            <a class="popup-with-form" href="#login-form">
                                <i class="fas fa-user"></i> Login
                            </a>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
    <!-- End Header Top -->

    <!-- Tutorial Modal -->
    <div class="modal fade" id="tutorialModal" tabindex="-1" aria-labelledby="tutorialModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary text-center w-100" id="tutorialModalLabel">Cara Melakukan
                        Request MoU</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 20px;">
                    <ol style="line-height: 1.6; padding: 20px;">
                        <li>Log in to the Lapkerma website.</li>
                        <li>On the main page, click the Request MoU button.</li>
                        <li>If you do not have an account, please register first.</li>
                        <li>After registering, a page to enter the OTP code sent via the registered email will appear.
                            Enter the OTP code for verification.</li>
                        <li>After contacting, the admin will verify your email. After successful verification,
                            you can log in using that email.</li>
                        <li>After successfully entering the main page, select the Input tab, then select MoU in the
                            dropdown.
                        </li>
                        <li>Enter your data to request an MoU. You can upload the MoU document that has been prepared
                            previously. If not, you can check the checkbox in the upper left corner
                            to fill in the MoU data using the available template.</li>
                        <li>After filling in all the required columns, press the Submit button.</li>
                        <li>After pressing the Submit button, a pop-up notification will appear that your MoU request
                            is being processed. Please wait for confirmation from the admin within 5 working days.</li>
                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Header
    ============================================= -->
    <header id="home">

        <!-- Start Navigation -->
        <nav class="navbar navbar-default navbar-sticky bootsnav" style="z-index: 2">

            <!-- Start Top Search -->
            <div class="container">
                <div class="row">
                    <div class="top-search">
                        <div class="input-group">
                            <form action="#">
                                <input type="text" name="text" class="form-control" placeholder="Search">
                                <button type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Top Search -->

            <div class="container">

                <!-- Start Atribute Navigation -->
                {{-- <div class="attr-nav">
                    <ul>
                        <li class="search"><a href="#"><i class="fa fa-search"></i></a></li>
                    </ul>
                </div>         --}}
                <!-- End Atribute Navigation -->

                <!-- Start Header Navigation -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                        <i class="fa fa-bars"></i>
                    </button>
                    <a class="navbar-brand" href="{{ route('maps') }}">
                        <img src="{{ asset('new_template/assets/img/logo_new.png') }}" class="logo" alt="Logo"
                            style="max-width: 230px">
                    </a>
                </div>
                <!-- End Header Navigation -->

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <ul class="nav navbar-nav navbar-right" data-in="#" data-out="#">
                        <li class="dropdown active">
                            <a role="button" class="dropdown-toggle" data-toggle="dropdown">Dashboard</a>
                            <ul class="dropdown-menu animated #" style="display: none;">
                                <li><a href="{{route('maps')}}">Maps</a></li>
                                <li><a href="{{route('chart')}}">Chart</a></li>
                                <li><a href="{{route('report')}}">Report</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a role="button" class="dropdown-toggle active" data-toggle="dropdown">MoU</a>
                            <ul class="dropdown-menu animated #" style="display: none;">
                                <li><a class="popup-with-form" href="#MoU-login-form">
                                    Request MoU
                                </a></li>
                                <li><a data-toggle="modal" data-target="#tutorialModal" role="button">How To Request MoU</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a role="button" class="dropdown-toggle active" data-toggle="dropdown">MoA & Ia</a>
                            <ul class="dropdown-menu animated #" style="display: none;">
                                <li><a target="_blank" href="https://drive.google.com/drive/folders/1P-8eylxcLpNNeMox1qygJHD5EzE60yNd?hl=id">Tutorial Input MoA & Ia</a></li>
                            </ul>
                        </li>
                        {{-- <li class="">
                            <a href="https://partnership.unhas.ac.id/partners">partners</a>
                        </li> --}}
                    </ul>
                </div>
            </div>

        </nav>
        <!-- End Navigation -->

    </header>
    <!-- End Header -->

    <!-- Start Login Form
    ============================================= -->
    <form action="{{ route('login') }}" method="post" id="login-form" class="mfp-hide white-popup-block">
        <div class="col-md-4 login-social">
            {{-- <h3 style="margin-top: 30px">Aplikasi ditutup sementara sedang dilakukan maintenance</h3> --}}
            <h5 style="margin-top: 30px; font-weight: 700">LAPORAN KERJASAMA UNIVERSITAS HASANUDDIN</h5>
            <div style="font-size: 12px">
                <label>UPDATE :</label>
                <label>- Dashboard Dapat diakses secara umum</label>
                <label>- Update Dashboard (Map Sebaran kerjasama)</label>
                <label>- Menambahkan Menu Upload Laporan MoA dan Ia</label>
            </div>
        </div>
        <div class="col-md-8 login-custom">
            <h4>Gunakan User & Password Apps Anda !</h4>
            @csrf
            <div>
                <input type="text" name="email" class="form-control" value="{{ old('email') }}"
                    placeholder="NIP / USER APPS">
            </div>
            <br>
            <div>
                <input type="password" name="password" class="form-control" placeholder="PASSWORD" required>
            </div>
            @if (session('errors'))
                <div class="mt-1 alert bg-rgba-danger mb-1" style="margin-bottom: 0px">
                    <i class="bx bx-info-circle align-middle"></i>
                    <span class="align-middle">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </span>
                </div>
            @endif
            <hr>
            <button type="submit" class="btn btn-primary btn-block">Log In</button>
        </div>
    </form>
    <!-- End Login Form -->

    <!-- Start Guest Mou Login Form
    ============================================= -->
    <form action="{{ route('login') }}" method="post" id="MoU-login-form" class="mfp-hide white-popup-block">
        <div class="mt-1 alert bg-rgba-danger mb-1" style="margin-bottom: 0px">
            <h4>Use your App Username & Password to create an MoU!</h4>
            @csrf
            <div>
                <input type="text" name="email" class="form-control" value="{{ old('email') }}"
                    placeholder="NIP / USER APPS">
            </div>
            <br>
            <div>
                <input type="password" name="password" class="form-control" placeholder="PASSWORD" required>
            </div>
            @if (session('errors'))
                <div class="mt-1 alert bg-rgba-danger mb-1" style="margin-bottom: 0px">
                    <i class="bx bx-info-circle align-middle"></i>
                    <span class="align-middle">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </span>
                </div>
            @endif
            <hr>
            <button type="submit" class="btn btn-primary btn-block">Log In</button>
            <a class="popup-with-form" style="color:blue; text-decoration: underline;" href="#regis-form">Register if
                you don't have an
                account</a>
        </div>
    </form>
    <!-- End Guest Mou Login Form -->

    <!-- Guest Registration Form -->
    @livewire('input.guest-registration')
    <!-- Guest Registration Form -->

    <!-- OTP Form -->
    <form action="{{ route('verifyOtp') }}" method="POST" id="otp-form" class="mfp-hide white-popup-block">
        @csrf
        <div class="otp-section">
            <h4>Enter OTP</h4>
            <input max="6" type="text" name="otp" class="form-control" placeholder="Enter OTP"
                required>
            <button type="submit" class="btn btn-primary btn-block">Verify OTP</button>
        </div>
    </form>

    <!-- OTP Form -->


    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    

    @livewire('dashboard-chart.search')

    <!-- Start Top Categories
    ============================================= -->
    <div id="top-categories" class="top-cat-area bottom-less" style="padding-top: 30px">
        <div class="container">
            <div class="row">
                <div class="col-md-8 top-cat-items">
                    @livewire('dashboard-chart.map-dashboard')
                </div>
            </div>
        </div>
    </div>
    <!-- End Top Categories -->

    <!-- Start Top Categories
    ============================================= -->
    <div id="map-kerjasama-section" class="top-cat-area bottom-less map-kerjasama-section"
        style=" padding-top: 100px; padding-bottom: 100px; background-color: #f5f5f5">
        <div class="container">
            <div class="row" style="display: flex; align-items: center; justify-content: center;">
                <div class="col-md-8
                top-cat-items">
                    @livewire('dashboard-chart.kerjasama-map')
                </div>
            </div>
        </div>
    </div>
    <!-- End Top Categories -->

    <!-- Start Footer
    ============================================= -->
    <!-- Start Footer Bottom -->
    <div class="footer-bottom bg-dark">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-6 text-right link">
                        <ul>
                            <li>
                                <a target="blank" href="http://dsitd.unhas.ac.id" style="font-size: 9px">@
                                    DSITD-Unhas</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Footer Bottom -->

    <div id="myDiv" class="side
    @if (session('errors')) on @endif
    
    ">
        <a href="/" onclick="removeClass()" class="close-side"><i class="fa fa-times"></i></a>
        <div class="widget">
            <h4 class="title">Error Login</h4>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>


    </div>
    <!-- End Footer -->
    @livewireScripts
    <!-- jQuery Frameworks
    ============================================= -->
    <script src="{{ asset('new_template/assets/js/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('new_template/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('new_template/assets/js/equal-height.min.js') }}"></script>
    <script src="{{ asset('new_template/assets/js/jquery.appear.js') }}"></script>
    <script src="{{ asset('new_template/assets/js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('new_template/assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('new_template/assets/js/modernizr.custom.13711.js') }}"></script>
    <script src="{{ asset('new_template/assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('new_template/assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('new_template/assets/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('new_template/assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('new_template/assets/js/count-to.js') }}"></script>
    <script src="{{ asset('new_template/assets/js/loopcounter.js') }}"></script>
    <script src="{{ asset('new_template/assets/js/bootsnav.js') }}"></script>
    <script src="{{ asset('new_template/assets/js/main.js') }}"></script>
    @stack('custom-scripts')
    @stack('chart-riwayatKerjasama')
    @stack('chart-statusKerjasama')
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        function removeClass() {
            // Mendapatkan elemen div berdasarkan ID
            var divElement = document.getElementById('myDiv');

            // Menghapus class tertentu dari elemen div
            divElement.classList.remove('on');
        }
        document.addEventListener("DOMContentLoaded", function() {
            // Check if the session variable for OTP form is set
            @if (session('showOtpForm'))
                // Trigger the display of the OTP form popup
                $.magnificPopup.open({
                    items: {
                        src: '#otp-form' // The id of the OTP form
                    },
                    type: 'inline'
                });
            @endif
        });
    </script>
</body>

</html>