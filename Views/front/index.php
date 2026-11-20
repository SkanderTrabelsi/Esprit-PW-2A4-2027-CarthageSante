<?php
$pageTitle = "Accueil";
$currentPage = "index";
?>

<!DOCTYPE html>
<html lang="fr">

  <?php require_once './includes/head.php'; ?>

<body class="index-page">
  <?php require_once './includes/spinner.php'; ?>
  <?php require_once './includes/navbar.php'; ?>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row align-items-center">
          <div class="col-lg-6">
            <div class="hero-content">
              <div class="trust-badges mb-4" data-aos="fade-right" data-aos-delay="200">
                <div class="badge-item">
                  <i class="bi bi-shield-check"></i>
                  <span>Accredited</span>
                </div>
                <div class="badge-item">
                  <i class="bi bi-clock"></i>
                  <span>24/7 Emergency</span>
                </div>
                <div class="badge-item">
                  <i class="bi bi-star-fill"></i>
                  <span>4.9/5 Rating</span>
                </div>
              </div>

              <h1 data-aos="fade-right" data-aos-delay="300">
                Excellence in <span class="highlight">Healthcare</span> With Compassionate Care
              </h1>

              <p class="hero-description" data-aos="fade-right" data-aos-delay="400">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.
              </p>

              <div class="hero-stats mb-4" data-aos="fade-right" data-aos-delay="500">
                <div class="stat-item">
                  <h3><span data-purecounter-start="0" data-purecounter-end="15" data-purecounter-duration="2"
                      class="purecounter"></span>+</h3>
                  <p>Years Experience</p>
                </div>
                <div class="stat-item">
                  <h3><span data-purecounter-start="0" data-purecounter-end="5000" data-purecounter-duration="2"
                      class="purecounter"></span>+</h3>
                  <p>Patients Treated</p>
                </div>
                <div class="stat-item">
                  <h3><span data-purecounter-start="0" data-purecounter-end="50" data-purecounter-duration="2"
                      class="purecounter"></span>+</h3>
                  <p>Medical Experts</p>
                </div>
              </div>

              <div class="hero-actions" data-aos="fade-right" data-aos-delay="600">
                <a href="appointment.html" class="btn btn-primary">Book Appointment</a>
                <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="btn btn-outline glightbox">
                  <i class="bi bi-play-circle me-2"></i>
                  Watch Our Story
                </a>
              </div>

              <div class="emergency-contact" data-aos="fade-right" data-aos-delay="700">
                <div class="emergency-icon">
                  <i class="bi bi-telephone-fill"></i>
                </div>
                <div class="emergency-info">
                  <small>Emergency Hotline</small>
                  <strong>+216 20 042 692</strong>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="hero-visual" data-aos="fade-left" data-aos-delay="400">
              <div class="main-image">
                <img src="../assets/img/health/staff-10.webp" alt="Modern Healthcare Facility" class="img-fluid">
                <div class="floating-card appointment-card">
                  <div class="card-icon">
                    <i class="bi bi-calendar-check"></i>
                  </div>
                  <div class="card-content">
                    <h6>Next Available</h6>
                    <p>Today 2:30 PM</p>
                    <small>Dr. Sarah Johnson</small>
                  </div>
                </div>
                <div class="floating-card rating-card">
                  <div class="card-content">
                    <div class="rating-stars">
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                    </div>
                    <h6>4.9/5</h6>
                    <small>1,234 Reviews</small>
                  </div>
                </div>
              </div>
              <div class="background-elements">
                <div class="element element-1"></div>
                <div class="element element-2"></div>
                <div class="element element-3"></div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Hero Section -->

    <!-- Featured Services Section -->
    <section id="featured-services" class="featured-services section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Featured Services</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-0">

          <div class="col-lg-8" data-aos="fade-right" data-aos-delay="200">
            <div class="featured-service-main">
              <div class="service-image-wrapper">
                <img src="../assets/img/health/consultation-4.webp" alt="Premier Healthcare Services" class="img-fluid"
                  loading="lazy">
                <div class="service-overlay">
                  <div class="service-badge">
                    <i class="bi bi-heart-pulse"></i>
                    <span>Emergency Care</span>
                  </div>
                </div>
              </div>
              <div class="service-details">
                <h2>Comprehensive Healthcare Excellence</h2>
                <p>Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Vestibulum ante ipsum primis in faucibus
                  orci luctus et ultrices posuere cubilia curae donec velit neque.</p>
                <a href="#!" class="main-cta">Explore Our Services</a>
              </div>
            </div>
          </div>

          <div class="col-lg-4" data-aos="fade-left" data-aos-delay="300">
            <div class="services-sidebar">

              <div class="service-item" data-aos="fade-up" data-aos-delay="400">
                <div class="service-icon-wrapper">
                  <i class="bi bi-capsule"></i>
                </div>
                <div class="service-info">
                  <h4>Dermatology Clinic</h4>
                  <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p>
                  <a href="#!" class="service-link">Learn More</a>
                </div>
              </div>

              <div class="service-item" data-aos="fade-up" data-aos-delay="500">
                <div class="service-icon-wrapper">
                  <i class="bi bi-bandaid"></i>
                </div>
                <div class="service-info">
                  <h4>Surgery Center</h4>
                  <p>Donec rutrum congue leo eget malesuada curabitur arcu erat accumsan id imperdiet et porttitor at
                    sem.</p>
                  <a href="#!" class="service-link">Learn More</a>
                </div>
              </div>

              <div class="service-item" data-aos="fade-up" data-aos-delay="600">
                <div class="service-icon-wrapper">
                  <i class="bi bi-activity"></i>
                </div>
                <div class="service-info">
                  <h4>Diagnostics Lab</h4>
                  <p>Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui cras ultricies ligula sed
                    magna.</p>
                  <a href="#!" class="service-link">Learn More</a>
                </div>
              </div>

            </div>
          </div>

        </div>

        <div class="specialties-grid" data-aos="fade-up" data-aos-delay="300">
          <div class="row align-items-center">

            <div class="col-lg-3 col-md-6">
              <div class="specialty-card">
                <div class="specialty-image">
                  <img src="../assets/img/health/maternal-2.webp" alt="Maternal Care" class="img-fluid" loading="lazy">
                </div>
                <div class="specialty-content">
                  <h5>Maternal Care</h5>
                  <span>Expert pregnancy &amp; delivery support</span>
                </div>
              </div>
            </div>

            <div class="col-lg-3 col-md-6">
              <div class="specialty-card">
                <div class="specialty-image">
                  <img src="../assets/img/health/vaccination-3.webp" alt="Vaccination" class="img-fluid" loading="lazy">
                </div>
                <div class="specialty-content">
                  <h5>Vaccination</h5>
                  <span>Complete immunization programs</span>
                </div>
              </div>
            </div>

            <div class="col-lg-3 col-md-6">
              <div class="specialty-card">
                <div class="specialty-image">
                  <img src="../assets/img/health/emergency-1.webp" alt="Emergency Care" class="img-fluid" loading="lazy">
                </div>
                <div class="specialty-content">
                  <h5>Emergency Care</h5>
                  <span>24/7 critical care services</span>
                </div>
              </div>
            </div>

            <div class="col-lg-3 col-md-6">
              <div class="specialty-card">
                <div class="specialty-image">
                  <img src="../assets/img/health/facilities-6.webp" alt="Advanced Tech" class="img-fluid" loading="lazy">
                </div>
                <div class="specialty-content">
                  <h5>Advanced Technology</h5>
                  <span>State-of-the-art medical equipment</span>
                </div>
              </div>
            </div>

          </div>
        </div>

      </div>

    </section><!-- /Featured Services Section -->

    <!-- Find A Doctor Section -->
    <section id="find-a-doctor" class="find-a-doctor section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Find A Doctor</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row justify-content-center mb-5" data-aos="fade-up" data-aos-delay="200">
          <div class="col-lg-8 text-center">
            <div class="search-section">
              <h3 class="search-title">Find Your Perfect Healthcare Provider</h3>
              <p class="search-subtitle">Search through our comprehensive directory of experienced medical professionals
              </p>
              <form class="search-form" action="#!" method="#">
                <div class="search-input-group">
                  <div class="input-wrapper">
                    <i class="bi bi-person"></i>
                    <input type="text" class="form-control" name="doctor_name" placeholder="Enter doctor name">
                  </div>
                  <div class="select-wrapper">
                    <i class="bi bi-heart-pulse"></i>
                    <select class="form-select" name="specialty">
                      <option value="">All Specialties</option>
                      <option value="cardiology">Cardiology</option>
                      <option value="neurology">Neurology</option>
                      <option value="orthopedics">Orthopedics</option>
                      <option value="pediatrics">Pediatrics</option>
                      <option value="dermatology">Dermatology</option>
                      <option value="oncology">Oncology</option>
                    </select>
                  </div>
                  <button type="submit" class="search-btn">
                    <i class="bi bi-search"></i>
                    Find Doctors
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="doctors-grid" data-aos="fade-up" data-aos-delay="300">
          <div class="doctor-profile" data-aos="zoom-in" data-aos-delay="100">
            <div class="profile-header">
              <div class="doctor-avatar">
                <img src="../assets/img/health/staff-2.webp" alt="Dr. Amanda Foster" class="img-fluid">
                <div class="status-indicator available"></div>
              </div>
              <div class="doctor-details">
                <h4>Dr. Amanda Foster</h4>
                <span class="specialty-tag">Cardiology Specialist</span>
                <div class="experience-info">
                  <i class="bi bi-award"></i>
                  <span>14 years experience</span>
                </div>
              </div>
            </div>
            <div class="rating-section">
              <div class="stars">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
              </div>
              <span class="rating-score">4.9</span>
              <span class="review-count">(127 reviews)</span>
            </div>
            <div class="action-buttons">
              <a href="#!" class="btn-secondary">View Details</a>
              <a href="#!" class="btn-primary">Book Now</a>
            </div>
          </div><!-- End Doctor Profile -->

          <div class="doctor-profile" data-aos="zoom-in" data-aos-delay="200">
            <div class="profile-header">
              <div class="doctor-avatar">
                <img src="../assets/img/health/staff-6.webp" alt="Dr. Marcus Johnson" class="img-fluid">
                <div class="status-indicator busy"></div>
              </div>
              <div class="doctor-details">
                <h4>Dr. Marcus Johnson</h4>
                <span class="specialty-tag">Neurology Expert</span>
                <div class="experience-info">
                  <i class="bi bi-award"></i>
                  <span>16 years experience</span>
                </div>
              </div>
            </div>
            <div class="rating-section">
              <div class="stars">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-half"></i>
              </div>
              <span class="rating-score">4.8</span>
              <span class="review-count">(89 reviews)</span>
            </div>
            <div class="action-buttons">
              <a href="#!" class="btn-secondary">View Details</a>
              <a href="#!" class="btn-primary">Schedule</a>
            </div>
          </div><!-- End Doctor Profile -->

          <div class="doctor-profile" data-aos="zoom-in" data-aos-delay="300">
            <div class="profile-header">
              <div class="doctor-avatar">
                <img src="../assets/img/health/staff-4.webp" alt="Dr. Rachel Williams" class="img-fluid">
                <div class="status-indicator available"></div>
              </div>
              <div class="doctor-details">
                <h4>Dr. Rachel Williams</h4>
                <span class="specialty-tag">Pediatrics Care</span>
                <div class="experience-info">
                  <i class="bi bi-award"></i>
                  <span>11 years experience</span>
                </div>
              </div>
            </div>
            <div class="rating-section">
              <div class="stars">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
              </div>
              <span class="rating-score">5.0</span>
              <span class="review-count">(203 reviews)</span>
            </div>
            <div class="action-buttons">
              <a href="#!" class="btn-secondary">View Details</a>
              <a href="#!" class="btn-primary">Book Now</a>
            </div>
          </div><!-- End Doctor Profile -->

          <div class="doctor-profile" data-aos="zoom-in" data-aos-delay="400">
            <div class="profile-header">
              <div class="doctor-avatar">
                <img src="../assets/img/health/staff-8.webp" alt="Dr. David Chen" class="img-fluid">
                <div class="status-indicator offline"></div>
              </div>
              <div class="doctor-details">
                <h4>Dr. David Chen</h4>
                <span class="specialty-tag">Orthopedic Surgery</span>
                <div class="experience-info">
                  <i class="bi bi-award"></i>
                  <span>22 years experience</span>
                </div>
              </div>
            </div>
            <div class="rating-section">
              <div class="stars">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-half"></i>
              </div>
              <span class="rating-score">4.7</span>
              <span class="review-count">(156 reviews)</span>
            </div>
            <div class="action-buttons">
              <a href="#!" class="btn-secondary">View Details</a>
              <a href="#!" class="btn-primary">Schedule</a>
            </div>
          </div><!-- End Doctor Profile -->

          <div class="doctor-profile" data-aos="zoom-in" data-aos-delay="500">
            <div class="profile-header">
              <div class="doctor-avatar">
                <img src="../assets/img/health/staff-11.webp" alt="Dr. Victoria Torres" class="img-fluid">
                <div class="status-indicator available"></div>
              </div>
              <div class="doctor-details">
                <h4>Dr. Victoria Torres</h4>
                <span class="specialty-tag">Dermatology Care</span>
                <div class="experience-info">
                  <i class="bi bi-award"></i>
                  <span>9 years experience</span>
                </div>
              </div>
            </div>
            <div class="rating-section">
              <div class="stars">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star"></i>
              </div>
              <span class="rating-score">4.5</span>
              <span class="review-count">(74 reviews)</span>
            </div>
            <div class="action-buttons">
              <a href="#!" class="btn-secondary">View Details</a>
              <a href="#!" class="btn-primary">Book Now</a>
            </div>
          </div><!-- End Doctor Profile -->

          <div class="doctor-profile" data-aos="zoom-in" data-aos-delay="600">
            <div class="profile-header">
              <div class="doctor-avatar">
                <img src="../assets/img/health/staff-14.webp" alt="Dr. Benjamin Lee" class="img-fluid">
                <div class="status-indicator available"></div>
              </div>
              <div class="doctor-details">
                <h4>Dr. Benjamin Lee</h4>
                <span class="specialty-tag">Oncology Treatment</span>
                <div class="experience-info">
                  <i class="bi bi-award"></i>
                  <span>19 years experience</span>
                </div>
              </div>
            </div>
            <div class="rating-section">
              <div class="stars">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
              </div>
              <span class="rating-score">4.9</span>
              <span class="review-count">(194 reviews)</span>
            </div>
            <div class="action-buttons">
              <a href="#!" class="btn-secondary">View Details</a>
              <a href="#!" class="btn-primary">Schedule</a>
            </div>
          </div><!-- End Doctor Profile -->

        </div>

        <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="700">
          <a href="doctors.html" class="btn-view-all">
            View All Doctors
            <i class="bi bi-arrow-right"></i>
          </a>
        </div>

      </div>

    </section><!-- /Find A Doctor Section -->

    <!-- Call To Action Section -->
    <section id="call-to-action" class="call-to-action section light-background">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="hero-content">
          <div class="row align-items-center">

            <div class="col-lg-6">
              <div class="content-wrapper" data-aos="fade-up" data-aos-delay="200">
                <h1>Excellence in Medical Care, Every Day</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore
                  et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>

                <div class="cta-wrapper">
                  <a href="appointment.html" class="primary-cta">
                    <span>Schedule Consultation</span>
                    <i class="bi bi-arrow-right"></i>
                  </a>
                  <a href="services.html" class="secondary-cta">
                    <span>Explore Services</span>
                    <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="image-container" data-aos="fade-left" data-aos-delay="300">
                <img src="../assets/img/health/facilities-9.webp" alt="Medical Excellence" class="img-fluid">
              </div>
            </div>

          </div>
        </div>

        <div class="features-section">

          <div class="row g-0">

            <div class="col-lg-4">
              <div class="feature-block" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-icon">
                  <i class="bi bi-shield-check"></i>
                </div>
                <h3>Advanced Technology</h3>
                <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est
                  laborum.</p>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="feature-block" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-icon">
                  <i class="bi bi-clock"></i>
                </div>
                <h3>24/7 Availability</h3>
                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur
                  excepteur.</p>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="feature-block" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-icon">
                  <i class="bi bi-people"></i>
                </div>
                <h3>Expert Team</h3>
                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium
                  totam rem.</p>
              </div>
            </div>

          </div>

        </div>

        <div class="contact-block">
          <div class="row">

            <div class="col-lg-8">
              <div class="contact-content" data-aos="fade-up" data-aos-delay="200">
                <h2>Need Immediate Medical Assistance?</h2>
                <p>Our emergency response team is available around the clock to provide immediate medical support when
                  you need it most.</p>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="contact-actions" data-aos="fade-up" data-aos-delay="300">
                <a href="tel:5551234567" class="emergency-call">
                  <i class="bi bi-telephone"></i>
                  <span>(555) 123-4567</span>
                </a>
                <a href="contact.html" class="contact-link">Find Location</a>
              </div>
            </div>

          </div>
        </div>

      </div>

    </section><!-- /Call To Action Section -->

  </main>

  
  <?php require_once './includes/footer.php'; ?>
  <?php require_once './includes/scripts.php'; ?>

</body>

</html>