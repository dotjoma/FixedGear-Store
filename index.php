<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fixed Gear Culture</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts: Orbitron for creative headline -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            background: #181818;
            color: #fff;
        }
        .hero-section {
            position: relative;
            min-height: 80vh;
            background: url('assets/images/photo-1506744038136-46273834b3fb.avif') center center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .hero-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.6);
            z-index: 1;
        }
        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            width: 100%;
        }
        .hero-title {
            font-family: 'Orbitron', 'Arial Black', Arial, sans-serif;
            font-size: 3rem;
            font-weight: 900;
            letter-spacing: 2px;
            line-height: 1.1;
            text-transform: uppercase;
        }
        .hero-title .fixed {
            font-size: 4rem;
            letter-spacing: 4px;
        }
        .hero-desc {
            font-size: 1.2rem;
            margin: 1.5rem 0 2.5rem 0;
            color: #e0e0e0;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        .hero-btn {
            font-weight: bold;
            font-size: 1.1rem;
            border-radius: 30px;
            padding: 0.75rem 2.5rem;
            margin: 0 0.5rem;
            transition: background 0.2s, color 0.2s;
        }
        .hero-btn.shop {
            background: #e6ff00;
            color: #181818;
        }
        .hero-btn.shop:hover {
            background: #c6e600;
            color: #181818;
        }
        .hero-btn.featured {
            background: transparent;
            border: 2px solid #e6ff00;
            color: #e6ff00;
        }
        .hero-btn.featured:hover {
            background: #e6ff00;
            color: #181818;
        }
        .feature-cards {
            margin-top: -60px;
            z-index: 3;
            position: relative;
        }
        .feature-card {
            background: #e6ff00;
            color: #181818;
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            padding: 2.5rem 1.5rem;
            text-align: center;
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 2rem;
            transition: transform 0.2s;
        }
        .feature-card:hover {
            transform: translateY(-8px) scale(1.03);
        }
        .feature-card i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        @media (max-width: 991px) {
            .feature-cards {
                margin-top: 0;
            }
            .hero-title {
                font-size: 2.2rem;
            }
            .hero-title .fixed {
                font-size: 2.7rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Include -->
    <?php include('components/navigation.php'); ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="mb-4">
                        <div class="hero-title">
                            <span>SPREADING THE</span><br>
                            <span class="fixed">FIXED GEAR</span><br>
                            <span>CULTURE</span>
                        </div>
                    </div>
                    <div class="hero-desc">
                        We are connecting the heart and soul of the Fixed Gear universe, nurturing a space where passion for riding meets the spirit of community.
                    </div>
                    <div class="d-flex justify-content-center flex-wrap">
                        <a href="#" class="btn hero-btn shop me-2 mb-2">SHOP NOW <i class="fas fa-arrow-right ms-2"></i></a>
                        <a href="#" class="btn hero-btn featured mb-2">GET FEATURED <i class="fas fa-arrow-up-right-from-square ms-2"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Feature Cards Section (Updated) -->
    <section class="feature-cards container my-5">
      <div class="row g-4 justify-content-center">
        <!-- Card 1: Community Shop -->
        <div class="col-12 col-md-4">
          <div class="feature-card p-4 h-100 d-flex flex-column justify-content-between" style="background:#e6ff00; color:#181818; border-radius:18px;">
            <div>
              <i class="fas fa-shirt fa-2x mb-3"></i>
              <div class="fw-bold text-uppercase mb-0" style="font-size:1.1rem; letter-spacing:1px;">Community Shop</div>
              <div class="fw-bold text-uppercase mb-2" style="font-size:2rem; letter-spacing:1px;">Support & Style</div>
              <div class="mb-4" style="font-size:1rem;">
                Discover our unique range of stylish merchandise and bike parts designed for the true Fixed Gear enthusiast. Each purchase supports our mission to celebrate and grow the Fixed Gear community. Shop now and be a part of our journey.
              </div>
            </div>
            <a href="#" class="fw-bold text-uppercase d-inline-block mt-auto" style="color:#181818; text-decoration:none; font-size:1.1rem;">SHOP NOW <i class="fas fa-arrow-right ms-1"></i></a>
          </div>
        </div>
        <!-- Card 2: Get Featured -->
        <div class="col-12 col-md-4">
          <div class="feature-card p-4 h-100 d-flex flex-column justify-content-between" style="background:#e6ff00; color:#181818; border-radius:18px;">
            <div>
              <i class="fa-regular fa-face-smile fa-2x mb-3"></i>
              <div class="fw-bold text-uppercase mb-0" style="font-size:1.1rem; letter-spacing:1px;">For Riders</div>
              <div class="fw-bold text-uppercase mb-2" style="font-size:2rem; letter-spacing:1px;">Get Featured</div>
              <div class="mb-4" style="font-size:1rem;">
                Are you a rider with a story to tell or a skill to showcase? Share your experiences and show off your style with us. Submit your content and become a featured part of our thriving community.
              </div>
            </div>
            <a href="#" class="fw-bold text-uppercase d-inline-block mt-auto" style="color:#181818; text-decoration:none; font-size:1.1rem;">SUBMIT NOW <i class="fas fa-arrow-right ms-1"></i></a>
          </div>
        </div>
        <!-- Card 3: Start a Campaign -->
        <div class="col-12 col-md-4">
          <div class="feature-card p-4 h-100 d-flex flex-column justify-content-between" style="background:#e6ff00; color:#181818; border-radius:18px;">
            <div>
              <i class="fas fa-bullhorn fa-2x mb-3"></i>
              <div class="fw-bold text-uppercase mb-0" style="font-size:1.1rem; letter-spacing:1px;">For Brands</div>
              <div class="fw-bold text-uppercase mb-2" style="font-size:2rem; letter-spacing:1px;">Start a Campaign</div>
              <div class="mb-4" style="font-size:1rem;">
                Join us in driving the Fixed Gear movement forward. Collaborate with us to reach a passionate and engaged audience. Contact us today to launch your brand campaign with Fixed Gear Cult.
              </div>
            </div>
            <a href="#" class="fw-bold text-uppercase d-inline-block mt-auto" style="color:#181818; text-decoration:none; font-size:1.1rem;">CONTACT US <i class="fas fa-arrow-right ms-1"></i></a>
          </div>
        </div>
      </div>
    </section>

    <!-- Social & Culture Section (Inspired by Screenshot) -->
    <section class="container-fluid py-5" style="background:#14161b;">
      <div class="container">
        <div class="row align-items-center justify-content-center">
          <!-- Left: 2x2 Image Grid -->
          <div class="col-12 col-lg-6 mb-4 mb-lg-0">
            <div class="row g-3">
              <div class="col-6">
                <img src="assets/images/fgc_social_foto_3.jpg" class="img-fluid rounded-4 w-100 mb-3" style="aspect-ratio:1/1;object-fit:cover;" alt="Fixed Gear 1">
                <img src="assets/images/fgc_social_foto_5.jpg" class="img-fluid rounded-4 w-100" style="aspect-ratio:1/1;object-fit:cover;" alt="Fixed Gear 3">
              </div>
              <div class="col-6">
                <img src="assets/images/fgc_social_foto_4.jpg" class="img-fluid rounded-4 w-100 mb-3" style="aspect-ratio:1/1;object-fit:cover;" alt="Fixed Gear 2">
                <img src="assets/images/fgc_social_foto_6.jpg" class="img-fluid rounded-4 w-100" style="aspect-ratio:1/1;object-fit:cover;" alt="Fixed Gear 4">
              </div>
            </div>
          </div>
          <!-- Right: Text & Social Links -->
          <div class="col-12 col-lg-6 d-flex flex-column align-items-start justify-content-center">
            <div class="ps-lg-5 w-100">
              <div class="text-uppercase fw-bold text-white-50 mb-2" style="letter-spacing:2px;">Fixed Gear</div>
              <div class="display-4 fw-bold text-white mb-4" style="line-height:1.1;">A<br>JOURNEY,<br>A CULTURE</div>
              <div class="social-links-list">
                <div class="d-flex align-items-center mb-3 pb-2 border-bottom border-secondary">
                  <i class="fab fa-instagram fa-lg me-3 text-white-50"></i>
                  <span class="fw-bold text-white">@FIXEDGEARCULT</span>
                  <span class="ms-auto text-white-50"><i class="fas fa-arrow-up-right-from-square"></i></span>
                </div>
                <div class="d-flex align-items-center mb-3 pb-2 border-bottom border-secondary">
                  <i class="fab fa-youtube fa-lg me-3 text-white-50"></i>
                  <span class="fw-bold text-white">@FIXEDGEARCULT</span>
                  <span class="ms-auto text-white-50"><i class="fas fa-arrow-up-right-from-square"></i></span>
                </div>
                <div class="d-flex align-items-center mb-3 pb-2 border-bottom border-secondary">
                  <i class="fab fa-facebook fa-lg me-3 text-white-50"></i>
                  <span class="fw-bold text-white">FIXEDGEARCULTURE</span>
                  <span class="ms-auto text-white-50"><i class="fas fa-arrow-up-right-from-square"></i></span>
                </div>
                <div class="d-flex align-items-center mb-3 pb-2 border-bottom border-secondary">
                  <i class="fab fa-tiktok fa-lg me-3 text-white-50"></i>
                  <span class="fw-bold text-white">@FIXEDGEARCULT</span>
                  <span class="ms-auto text-white-50"><i class="fas fa-arrow-up-right-from-square"></i></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Floating Scroll-to-Top Button -->
      <button onclick="window.scrollTo({top:0,behavior:'smooth'});" class="btn position-fixed" style="bottom:32px;right:32px;z-index:999;background:#e6ff00;color:#181818;font-size:2rem;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,0.15);"><i class="fas fa-chevron-up"></i></button>
    </section>

    <!-- Community Shop Section (Neon Yellow) -->
    <section class="container-fluid py-5" style="background:#e6ff00;">
      <div class="container">
        <div class="row align-items-center justify-content-center">
          <!-- Left: Shop Image -->
          <div class="col-12 col-lg-6 d-flex justify-content-center mb-4 mb-lg-0">
            <img src="assets/images/fgc_shop_kv.png" alt="Community Shop" class="img-fluid" style="max-width:420px; width:100%; height:auto;">
          </div>
          <!-- Right: Headline, Description, Button -->
          <div class="col-12 col-lg-6 d-flex flex-column align-items-start justify-content-center">
            <div class="ps-lg-5 w-100">
              <div class="text-uppercase fw-bold mb-1" style="letter-spacing:2px; font-size:1.1rem; color:#181818;">Support & Style</div>
              <div class="fw-bold" style="font-size:3.5rem; line-height:1; color:#181818; letter-spacing:1px;">COMMUNITY<br>SHOP</div>
              <div class="mt-4 mb-4" style="color:#181818; font-size:1.2rem; max-width:420px;">
                Discover our unique range of stylish merchandise and bike parts designed for the true Fixed Gear enthusiast. Each purchase supports our mission to celebrate and grow the Fixed Gear community. Shop now and be a part of our journey.
              </div>
              <a href="#" class="btn btn-dark btn-lg px-5 py-3 rounded-pill fw-bold" style="font-size:1.2rem; letter-spacing:1px;">SHOP NOW <i class="fas fa-arrow-right ms-2"></i></a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include('components/footer.php'); ?>
</body>
</html>