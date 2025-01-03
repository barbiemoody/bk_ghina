<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <title>Sistem Temu Janji Poliklinik</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif; /* Apply Poppins font to the entire body */
        }
        h1 {
            font-weight: 700; /* Bold font for the main heading */
        }
        p {
            font-weight: 400; /* Regular font for the paragraph */
        }
    </style>
</head>


<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Poliklinik</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="py-5 bg-primary text-white text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">Sistem Temu Janji Pasien - Dokter</h1>
            <p class="lead">Bimbingan Karir 2024 Bidang Web</p>
        </div>
    </header>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center">
            <div class="col-lg-4 mb-4">
                   <div class="bg-light p-4 rounded shadow-sm">
                        <div class="mb-3 text-primary">
                            <i class="bi bi-person-circle fs-1"></i>
                        </div>
                        <h5>Login Sebagai Admin</h5>
                        <p>Apabila Anda adalah seorang Admin, silahkan Login terlebih dahulu untuk memulai melayani Pasien!</p>
                        <a href="http://<?= $_SERVER['HTTP_HOST'] ?>/bk_ghina/pages/auth/login.php" class="btn btn-primary">
                            Klik untuk Login <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="bg-light p-4 rounded shadow-sm">
                        <div class="mb-3 text-success">
                            <i class="bi bi-person-circle fs-1"></i>
                        </div>
                        <h5>Login Sebagai Dokter</h5>
                        <p>Apabila Anda adalah seorang Dokter, silahkan Login terlebih dahulu untuk memulai melayani Pasien!</p>
                        <a href="http://<?= $_SERVER['HTTP_HOST'] ?>/bk_ghina/pages/auth/login.php" class="btn btn-success">
                            Klik untuk Login <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="bg-light p-4 rounded shadow-sm">
                        <div class="mb-3 text-danger">
                            <i class="bi bi-person-heart fs-1"></i>
                        </div>
                        <h5>Login Sebagai Pasien</h5>
                        <p>Apabila Anda adalah seorang Pasien yang sudah terdaftar, silahkan Login untuk membuat janji temu!</p>
                        <a href="http://<?= $_SERVER['HTTP_HOST'] ?>/bk_ghina/pages/auth/login-pasien.php" class="btn btn-danger">
                            Klik untuk Login <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Testimoni Pasien</h2>
                <p class="text-muted">Apa kata mereka tentang layanan kami?</p>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3 text-primary">
                                    <i class="bi bi-chat-right-quote-fill fs-1"></i>
                                </div>
                                <div>
                                    <p class="mb-0">Baguss bangett</p>
                                    <small class="text-muted">- Ghina, Semarang</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3 text-primary">
                                    <i class="bi bi-chat-right-quote-fill fs-1"></i>
                                </div>
                                <div>
                                    <p class="mb-0">Polkee dan sehat</p>
                                    <small class="text-muted">- Lila, Semarang</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4 bg-primary text-white text-center">
        <div class="container">
            <p class="mb-0">
                Copyright &copy; <script>document.write(new Date().getFullYear());</script>
                <a href="#" class="text-white text-decoration-none">Ghina</a>. All Rights Reserved.
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
