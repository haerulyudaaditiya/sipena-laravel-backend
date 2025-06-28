<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Sipena</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <link href="{{ asset('assets/img/logo_only.png') }}" rel="icon">
    <link href="{{ asset('assets/img/logo_only.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/onepage/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/onepage/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/onepage/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/onepage/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/onepage/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('assets/onepage/css/main.css') }}" rel="stylesheet">
</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center sticky-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">

            <a href="{{ url()->current() }}" class="logo d-flex align-items-center me-auto">
                <h1 class="sitename">Sipena</h1>
            </a>


            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#hero" class="active">Beranda<br></a></li>
                    <li><a href="#about">Tentang Kami</a></li>
                    <li><a href="#services">Layanan</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <a class="btn-getstarted" href="{{ route('login') }}">Masuk</a>

        </div>
    </header>

    <main class="main">

        <!-- Hero Section untuk SIPENA -->
        <section id="hero" class="hero section">
            <img src="{{ asset('assets/onepage/img/hero-bg-abstract.jpg') }}" alt="Background Hero" data-aos="fade-in"
                class="">

            <div class="container">
                <div class="row justify-content-center" data-aos="zoom-out">
                    <div class="col-xl-7 col-lg-9 text-center">
                        <h1>Sistem Informasi Pengelolaan Karyawan (SIPENA)</h1>
                        <p>Selamat datang di SIPENA, sistem informasi terintegrasi untuk pengelolaan data karyawan,
                            absensi, cuti, penggajian, dan penilaian kinerja dalam perusahaan Anda.</p>
                    </div>
                </div>
                <div class="text-center" data-aos="zoom-out" data-aos-delay="100">
                    <a href="{{ route('login') }}" class="btn-get-started">Mulai Sekarang</a>
                </div>

                <div class="row gy-4 mt-5">
                    <!-- Icon Box 1 - Data Karyawan -->
                    <div class="col-md-6 col-lg-3" data-aos="zoom-out" data-aos-delay="100">
                        <div class="icon-box">
                            <div class="icon"><i class="bi bi-person-lines-fill"></i></div>
                            <h4 class="title"><a href="#">Manajemen Data Karyawan</a></h4>
                            <p class="description">Mengelola data pribadi karyawan, jabatan, dan status kepegawaian
                                dengan akses mudah dan terintegrasi.</p>
                        </div>
                    </div><!-- End Icon Box -->

                    <!-- Icon Box 2 - Absensi & Cuti -->
                    <div class="col-md-6 col-lg-3" data-aos="zoom-out" data-aos-delay="200">
                        <div class="icon-box">
                            <div class="icon"><i class="bi bi-calendar-check"></i></div>
                            <h4 class="title"><a href="#">Absensi & Pengajuan Cuti</a></h4>
                            <p class="description">Rekam absensi harian karyawan, dan kelola pengajuan cuti dengan
                                sistem otomatis yang mudah diakses oleh karyawan dan HR.</p>
                        </div>
                    </div><!-- End Icon Box -->

                    <!-- Icon Box 3 - Penggajian -->
                    <div class="col-md-6 col-lg-3" data-aos="zoom-out" data-aos-delay="300">
                        <div class="icon-box">
                            <div class="icon"><i class="bi bi-cash-stack"></i></div>
                            <h4 class="title"><a href="#">Pengelolaan Penggajian</a></h4>
                            <p class="description">Menghitung gaji pokok, tunjangan, dan potongan, serta membuat slip
                                gaji secara otomatis dengan sistem yang tepat dan efisien.</p>
                        </div>
                    </div><!-- End Icon Box -->

                    <!-- Icon Box 4 - Penilaian Kinerja -->
                    <div class="col-md-6 col-lg-3" data-aos="zoom-out" data-aos-delay="400">
                        <div class="icon-box">
                            <div class="icon"><i class="bi bi-bar-chart"></i></div>
                            <h4 class="title"><a href="#">Penilaian Kinerja Karyawan</a></h4>
                            <p class="description">Evaluasi kinerja karyawan dengan fitur laporan yang mendalam dan
                                mudah dipahami untuk meningkatkan produktivitas.</p>
                        </div>
                    </div><!-- End Icon Box -->
                </div>
            </div>
        </section><!-- /Hero Section -->

        <!-- About Section -->
        <section id="about" class="about section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Tentang SIPENA<br></h2>
                <p>SIPENA adalah Sistem Informasi Pengelolaan Karyawan yang dirancang untuk memudahkan manajemen data
                    karyawan di perusahaan.</p>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="row gy-4">

                    <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
                        <p>
                            SIPENA bertujuan untuk mengelola informasi karyawan secara efisien dan memudahkan proses
                            administratif yang berkaitan dengan pengelolaan data karyawan. Sistem ini memungkinkan
                            pengelolaan data karyawan, manajemen absensi, dan pemantauan kinerja yang lebih efektif.
                        </p>
                        <ul>
                            <li><i class="bi bi-check2-circle"></i> <span>Mengelola data karyawan secara terpusat dan
                                    efisien.</span></li>
                            <li><i class="bi bi-check2-circle"></i> <span>Mempermudah pengelolaan absensi dan cuti
                                    karyawan.</span></li>
                            <li><i class="bi bi-check2-circle"></i> <span>Menawarkan laporan kinerja yang akurat dan
                                    mudah dipahami.</span></li>
                        </ul>
                    </div>

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                        <p>SIPENA dirancang untuk memberikan kemudahan bagi perusahaan dalam mengelola data karyawan.
                            Dengan antarmuka yang sederhana dan mudah digunakan, sistem ini memungkinkan pengelolaan
                            data yang lebih terorganisir dan terstruktur dengan baik, serta meningkatkan efisiensi dalam
                            pengelolaan SDM.</p>
                    </div>

                </div>

            </div>

        </section><!-- /About Section -->

        <!-- About Alt Section -->
        <section id="about-alt" class="about-alt section">

            <div class="container">

                <div class="row gy-4">
                    <div class="col-lg-6 position-relative align-self-start" data-aos="fade-up" data-aos-delay="100">
                        <img src="{{ asset('assets/onepage/img/about.jpg') }}" class="img-fluid" alt="SIPENA">
                    </div>
                    <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="200">
                        <h3>Manfaat SIPENA dalam Pengelolaan Karyawan yang Efisien</h3>
                        <p class="fst-italic">
                            SIPENA memudahkan pengelolaan data karyawan secara terpusat dan efisien, memberikan laporan
                            yang akurat, serta meningkatkan kinerja perusahaan.
                        </p>
                        <ul>
                            <li><i class="bi bi-check2-all"></i> <span>Mengelola data karyawan secara efisien dan
                                    terpusat.</span></li>
                            <li><i class="bi bi-check2-all"></i> <span>Mempermudah pengelolaan absensi dan cuti
                                    karyawan.</span></li>
                            <li><i class="bi bi-check2-all"></i> <span>Menyediakan laporan kinerja yang mudah dipahami
                                    dan dapat diakses kapan saja.</span></li>
                        </ul>
                        <p>
                            SIPENA mengoptimalkan proses administrasi karyawan, meminimalkan kesalahan manual, dan
                            memastikan informasi yang lebih terorganisir. Dengan antarmuka yang mudah digunakan, SIPENA
                            memungkinkan perusahaan untuk fokus pada pengembangan sumber daya manusia yang lebih baik.
                        </p>
                    </div>
                </div>

            </div>

        </section><!-- /About Alt Section -->

        <!-- Services Section -->
        <section id="services" class="services section light-background">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Layanan SIPENA</h2>
                <p>SIPENA memberikan berbagai layanan yang memudahkan pengelolaan data karyawan dan administrasi sumber
                    daya manusia.</p>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="row gy-4">

                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="service-item item-cyan position-relative">
                            <div class="icon">
                                <svg width="100" height="100" viewBox="0 0 600 600"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="none" stroke-width="0" fill="#f5f5f5"
                                        d="M300,521.0016835830174C376.1290562159157,517.8887921683347,466.0731472004068,529.7835943286574,510.70327084640275,468.03025145048787C554.3714126377745,407.6079735673963,508.03601936045806,328.9844924480964,491.2728898941984,256.3432110539036C474.5976632858925,184.082847569629,479.9380746630129,96.60480741107993,416.23090153303,58.64404602377083C348.86323505073057,18.502131276798302,261.93793281208167,40.57373210992963,193.5410806939664,78.93577620505333C130.42746243093433,114.334589627462,98.30271207620316,179.96522072025542,76.75703585869454,249.04625023123273C51.97151888228291,328.5150500222984,13.704378332031375,421.85034740162234,66.52175969318436,486.19268352777647C119.04800174914682,550.1803526380478,217.28368757567262,524.383925680826,300,521.0016835830174">
                                    </path>
                                </svg>
                                <i class="bi bi-activity"></i>
                            </div>
                            <a href="service-details.html" class="stretched-link">
                                <h3>Manajemen Data Karyawan</h3>
                            </a>
                            <p>Sistem ini mengelola data karyawan secara efisien, memudahkan proses pengelolaan data
                                yang lebih terorganisir dan terpusat.</p>
                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="service-item item-orange position-relative">
                            <div class="icon">
                                <svg width="100" height="100" viewBox="0 0 600 600"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="none" stroke-width="0" fill="#f5f5f5"
                                        d="M300,582.0697525312426C382.5290701553225,586.8405444964366,449.9789794690241,525.3245884688669,502.5850820975895,461.55621195738473C556.606425686781,396.0723002908107,615.8543463187945,314.28637112970534,586.6730223649479,234.56875336149918C558.9533121215079,158.8439757836574,454.9685369536778,164.00468322053177,381.49747125262974,130.76875717737553C312.15926192815925,99.40240125094834,248.97055460311594,18.661163978235184,179.8680185752513,50.54337015887873C110.5421016452524,82.52863877960104,119.82277516462835,180.83849132639028,109.12597500060166,256.43424936330496C100.08760227029461,320.3096726198365,92.17705696193138,384.0621239912766,124.79988738764834,439.7174275375508C164.83382741302287,508.01625554203684,220.96474134820875,577.5009287672846,300,582.0697525312426">
                                    </path>
                                </svg>
                                <i class="bi bi-broadcast"></i>
                            </div>
                            <a href="service-details.html" class="stretched-link">
                                <h3>Pengelolaan Absensi</h3>
                            </a>
                            <p>Mempermudah manajemen absensi karyawan dengan fitur pengelolaan cuti dan kehadiran secara
                                otomatis.</p>
                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="service-item item-teal position-relative">
                            <div class="icon">
                                <svg width="100" height="100" viewBox="0 0 600 600"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="none" stroke-width="0" fill="#f5f5f5"
                                        d="M300,541.5067337569781C382.14930387511276,545.0595476570109,479.8736841581634,548.3450877840088,526.4010558755058,480.5488172755941C571.5218469581645,414.80211281144784,517.5187510058486,332.0715597781072,496.52539010469104,255.14436215662573C477.37192572678356,184.95920475031193,473.57363656557914,105.61284051026155,413.0603344069578,65.22779650032875C343.27470386102294,18.654635553484475,251.2091493199835,5.337323636656869,175.0934190732945,40.62881213300186C97.87086631185822,76.43348514350839,51.98124368387456,156.15599469081315,36.44837278890362,239.84606092416172C21.716077023791087,319.22268207091537,43.775223500013084,401.1760424656574,96.891909868211,461.97329694683043C147.22146801428983,519.5804099606455,223.5754009179313,538.201503339737,300,541.5067337569781">
                                    </path>
                                </svg>
                                <i class="bi bi-easel"></i>
                            </div>
                            <a href="service-details.html" class="stretched-link">
                                <h3>Pelaporan Kinerja</h3>
                            </a>
                            <p>Memberikan laporan kinerja karyawan yang dapat diakses secara real-time, meningkatkan
                                transparansi dan efisiensi.</p>
                        </div>
                    </div><!-- End Service Item -->

                </div>

            </div>

        </section><!-- /Services Section -->


    </main>

    <footer id="footer" class="footer light-background">

        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright</span> <strong class="px-1 sitename">Sipena</strong> <span>All Rights Reserved</span>
            </p>
        </div>

    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <script src="{{ asset('assets/onepage/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/onepage/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('assets/onepage/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/onepage/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('assets/onepage/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/onepage/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/onepage/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/onepage/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('assets/onepage/js/main.js') }}"></script>


</body>

</html>
