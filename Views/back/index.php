<?php
$pageTitle = "Accueil Admin";
$currentPage = "index";
require_once '../../Controllers/front/includes/session.php';
require_once '../../Controllers/front/utilisateurC.php';
require_once '../../Controllers/back/statC.php';
// admin kahaw ichouf page
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../front/login.php');
    exit;
} 

//importation liste des utilisateurs lkolhom
$uc = new utilisateurC();
$liste = $uc->listeUtilisateur()->fetchAll(); 

//calcul lel les widget stat
$stat = new statC();
$cets   = count($stat->filtrer($liste, '', '', 'semaine'));
$en_att = count($stat->filtrer($liste, '', 'en_attente'));
$susp   = count($stat->filtrer($liste, '', 'suspendu'));
$mact   = count($stat->filtrer($liste, 'medecin', 'actif'));
$matt   = count($stat->filtrer($liste, 'medecin', 'en_attente'));
$pact   = count($stat->filtrer($liste, 'pharmacie', 'actif'));
$patt   = count($stat->filtrer($liste, 'pharmacie', 'en_attente'));

?>

<!DOCTYPE html>
<html lang="fr">

  <?php require_once './includes/head.php'; ?>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">
    <?php require_once './includes/header.php'; ?>
    <?php require_once './includes/sidebar.php'; ?>

    <main class="app-main">
      <div class="app-content"><div class="container-fluid">
          
        <!--begin::App Header-->
          <div class="app-content-header">
              <!--begin::Container-->
              <div class="container-fluid">
                  <!--begin::Row-->
                  <div class="row">
                      <div class="col-sm-6">
                          <h1 class="mb-0 fs-3">Tableau de bord</h1>
                      </div>
                      <div class="col-sm-6">
                          <nav aria-label="breadcrumb">
                              <ol class="breadcrumb float-sm-end">
                                  <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                                  <li class="breadcrumb-item active" aria-current="page">Tableau de bord</li>
                              </ol>
                          </nav>
                      </div>
                  </div>
                  <!--end::Row-->
              </div>
              <!--end::Container-->
          </div>
        <!--fin::App Header-->

        <!--begin::App Content-->
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--debut: stat widget-->
            <div class="row">
              <!--debut: Small Box compte cree-->
                <div class="col-lg-3 col-6">
                  <div class="small-box text-bg-primary">
                    <div class="inner">
                      <h3><?= count($liste) ?></h3>
                      <p>Compte creé</p>
                    </div>
                    <svg
                      class="small-box-icon"
                      fill="currentColor"
                      viewBox="0 0 24 24"
                      xmlns="http://www.w3.org/2000/svg"
                      aria-hidden="true">
                      <path
                        d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z">
                      </path>
                    </svg>
                    <a
                      href="liste_user.php"
                      class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                      voir plus <i class="bi bi-link-45deg"></i>
                    </a>
                  </div>
                </div>
              <!--debut: Small Box compte cree semaine-->
                <div class="col-lg-3 col-6">
                  <div class="small-box text-bg-primary">
                    <div class="inner">
                      <h3><?= $cets?></h3>
                      <p>Compte cree cette semaine</p>
                    </div>
                    <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                      <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M7 2a1 1 0 011 1v1h8V3a1 1 0 112 0v1h1a3 3 0 013 3v12a3 3 0 01-3 3H5a3 3 0 01-3-3V7a3 3 0 013-3h1V3a1 1 0 011-1zM4 10v9a1 1 0 001 1h14a1 1 0 001-1v-9H4z" />
                      <path d="M6.5 12h2.5v2.5H6.5zM10.75 12h2.5v2.5h-2.5zM15 12h2.5v2.5H15zM6.5 16h2.5v2.5H6.5z" />
                    </svg>
                    <a
                      href="liste_user.php?periode=semaine"
                      class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                      voir plus <i class="bi bi-link-45deg"></i>
                    </a>
                  </div>
                </div>
              <!--debut: Small Box compte en attente-->
                <div class="col-lg-3 col-6">
                  <div class="small-box text-bg-primary">
                    <div class="inner">
                      <h3><?= $en_att?></h3>
                      <p>Compte en attente</p>
                    </div>
                    <svg
                      class="small-box-icon"
                      fill="currentColor"
                      viewBox="0 0 24 24"
                      xmlns="http://www.w3.org/2000/svg"
                      aria-hidden="true">
                      <path
                        d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122z"
                      />
                      <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M19 4.5a4.5 4.5 0 100 9 4.5 4.5 0 000-9zM19.5 6.75a.75.75 0 00-1.5 0V9c0 .2.08.39.22.53l1.25 1.25a.75.75 0 101.06-1.06L19.5 8.69V6.75z"
                      />
                    </svg>
                    <a
                      href="demnd_pro.php"
                      class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                      voir plus <i class="bi bi-link-45deg"></i>
                    </a>
                  </div>
                </div>
              <!--debut: Small Box compte suspendu-->
                <div class="col-lg-3 col-6">
                  <div class="small-box text-bg-primary">
                    <div class="inner">
                      <h3><?= $susp?></h3>
                      <p>Compte suspendu</p>
                    </div>
                    <svg
                      class="small-box-icon"
                      fill="currentColor"
                      viewBox="0 0 24 24"
                      xmlns="http://www.w3.org/2000/svg"
                      aria-hidden="true">
                      <path
                        d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122z"
                      />
                      <path
                        stroke="currentColor"
                        stroke-width="2.2"
                        stroke-linecap="round"
                        d="M17 4.5l4 4m0-4l-4 4"
                      />
                    </svg>
                    <a
                      href="liste_user.php?statut=suspendu"
                      class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                      voir plus <i class="bi bi-link-45deg"></i>
                    </a>
                  </div>
                </div>
              <!--debut: Small Box compte medecin-->
                <div class="col-lg-3 col-6">
                  <div class="small-box text-bg-primary">
                    <div class="inner">
                      <h3><?= $mact?></h3>
                      <p>Compte medecin actif</p>
                    </div>
                    <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                      <path
                        d="M6 3a1 1 0 011 1v4.5a3.5 3.5 0 007 0V4a1 1 0 112 0v4.5a5.5 5.5 0 01-5 5.47V16a4 4 0 008 0v-2.18a2.5 2.5 0 112 0V16a6 6 0 01-12 0v-2.03A5.5 5.5 0 015 8.5V4a1 1 0 011-1z" />
                    </svg>
                    <a
                      href="liste_user.php?role=medecin&amp;statut=actif"
                      class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                      voir plus <i class="bi bi-link-45deg"></i>
                    </a>
                  </div>
                </div>
              <!--debut: Small Box medecin en attente-->
                <div class="col-lg-3 col-6">
                  <div class="small-box text-bg-primary">
                    <div class="inner">
                      <h3><?= $matt?></h3>
                      <p>Medecin en attente</p>
                    </div>
                    <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                      <path
                        d="M7.5 6a1.5 1.5 0 00-1.5 1.5v2.5H3.5A1.5 1.5 0 002 11.5v3A1.5 1.5 0 003.5 16H6v2.5A1.5 1.5 0 007.5 20h3a1.5 1.5 0 001.5-1.5V16h2.5a1.5 1.5 0 001.5-1.5v-3A1.5 1.5 0 0014.5 10H12V7.5A1.5 1.5 0 0010.5 6h-3z" />
                      <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M19.2 2.8a3.6 3.6 0 100 7.2 3.6 3.6 0 000-7.2zm.4 1.75a.6.6 0 00-1.2 0v2.05c0 .16.06.31.18.42l1 1a.6.6 0 10.85-.85l-.83-.83V4.55z" />
                    </svg>
                    <a
                      href="demnd_pro.php?role=medecin"
                      class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                      voir plus <i class="bi bi-link-45deg"></i>
                    </a>
                  </div>
                </div>
              <!--debut: Small Box compte pharmacie-->
                <div class="col-lg-3 col-6">
                  <div class="small-box text-bg-primary">
                    <div class="inner">
                      <h3><?= $pact?></h3>
                      <p>Compte pharmacie actif</p>
                    </div>
                    <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                      <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2zm5.5 4a.5.5 0 00-.5.5V10H7.5a.5.5 0 00-.5.5v3a.5.5 0 00.5.5H10v2.5a.5.5 0 00.5.5h3a.5.5 0 00.5-.5V14h2.5a.5.5 0 00.5-.5v-3a.5.5 0 00-.5-.5H14V7.5a.5.5 0 00-.5-.5h-3z" />
                    </svg>
                    <a
                      href="liste_user.php?role=pharmacie&amp;statut=actif"
                      class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                      voir plus <i class="bi bi-link-45deg"></i>
                    </a>
                  </div>
                </div>
              <!--debut: Small Box pharmacie en attente-->
                <div class="col-lg-3 col-6">
                  <div class="small-box text-bg-primary">
                    <div class="inner">
                      <h3><?= $patt?></h3>
                      <p>Pharmacie en attente</p>
                    </div>
                    <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                      <path
                        d="M15.6 13.4a6.6 6.6 0 01-8.8-8.8 7.5 7.5 0 108.8 8.8z" />
                      <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M19.2 2.8a3.6 3.6 0 100 7.2 3.6 3.6 0 000-7.2zm.4 1.75a.6.6 0 00-1.2 0v2.05c0 .16.06.31.18.42l1 1a.6.6 0 10.85-.85l-.83-.83V4.55z" />
                    </svg>
                    <a
                      href="demnd_pro.php?role=pharmacie"
                      class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                      voir plus <i class="bi bi-link-45deg"></i>
                    </a>
                  </div>
                </div>
            </div>
            <!-- fin stat widget -->

            <!--debut:courbe-->
            <div class="row">
              <div class="col-md-12">
                <div class="card mb-4">
                  <div class="card-header">
                    <h5 class="card-title">Rapport mensuel</h5>
                    <div class="card-tools">
                      <button
                        type="button"
                        class="btn btn-tool"
                        data-lte-toggle="card-collapse"
                        aria-label="Collapse card">
                        <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                        <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                      </button>
                    </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <!--begin::Row-->
                    <div class="row">
                      <div class="col-md-8">
                        <p class="text-center">
                          <strong>
                            <?= htmlspecialchars((new DateTimeImmutable('now'))->modify('-1 month')->format('d M'), ENT_QUOTES, 'UTF-8') ?>
                            Jusqu'a
                            <?= htmlspecialchars((new DateTimeImmutable('now'))->format('d M'), ENT_QUOTES, 'UTF-8') ?>
                          </strong>
                        </p>
                        <div class="position-relative" style="height: 180px">
                          <canvas
                            id="sales-chart"
                            role="img"
                            aria-label="Area chart of digital goods and electronics sales, January to July 2023"
                          ></canvas>
                        </div>
                      </div>
                      <!-- /.col -->
                      <div class="col-md-4">
                        <p class="text-center">
                          <strong>Goal Completion</strong>
                        </p>

                        <div class="progress-group">
                          Add Products to Cart
                          <span class="float-end"><b>160</b>/200</span>
                          <div class="progress progress-sm">
                            <div class="progress-bar text-bg-primary" style="width: 80%"></div>
                          </div>
                        </div>
                        <!-- /.progress-group -->

                        <div class="progress-group">
                          Complete Purchase
                          <span class="float-end"><b>310</b>/400</span>
                          <div class="progress progress-sm">
                            <div class="progress-bar text-bg-danger" style="width: 75%"></div>
                          </div>
                        </div>

                        <!-- /.progress-group -->
                        <div class="progress-group">
                          <span class="progress-text">Visit Premium Page</span>
                          <span class="float-end"><b>480</b>/800</span>
                          <div class="progress progress-sm">
                            <div class="progress-bar text-bg-success" style="width: 60%"></div>
                          </div>
                        </div>

                        <!-- /.progress-group -->
                        <div class="progress-group">
                          Send Inquiries
                          <span class="float-end"><b>250</b>/500</span>
                          <div class="progress progress-sm">
                            <div class="progress-bar text-bg-warning" style="width: 50%"></div>
                          </div>
                        </div>
                        <!-- /.progress-group -->
                      </div>
                      <!-- /.col -->
                    </div>
                    <!--end::Row-->
                  </div>
                  <!-- ./card-body -->
                  <div class="card-footer bg-body">
                    <!--begin::Row-->
                    <div class="row">
                      <div class="col-md-3 col-6">
                        <div class="text-center border-end">
                          <span class="text-success-emphasis">
                            <i class="bi bi-caret-up-fill"></i> 17%
                          </span>
                          <h5 class="fw-bold mb-0">$35,210.43</h5>
                          <span class="text-uppercase">TOTAL REVENUE</span>
                        </div>
                      </div>

                      <!-- /.col -->
                      <div class="col-md-3 col-6">
                        <div class="text-center border-end">
                          <span class="text-info-emphasis">
                            <i class="bi bi-caret-left-fill"></i> 0%
                          </span>
                          <h5 class="fw-bold mb-0">$10,390.90</h5>
                          <span class="text-uppercase">TOTAL COST</span>
                        </div>
                      </div>
                      <!-- /.col -->
                      <div class="col-md-3 col-6">
                        <div class="text-center border-end">
                          <span class="text-success-emphasis">
                            <i class="bi bi-caret-up-fill"></i> 20%
                          </span>
                          <h5 class="fw-bold mb-0">$24,813.53</h5>
                          <span class="text-uppercase">TOTAL PROFIT</span>
                        </div>
                      </div>
                      <!-- /.col -->
                      <div class="col-md-3 col-6">
                        <div class="text-center">
                          <span class="text-danger-emphasis">
                            <i class="bi bi-caret-down-fill"></i> 18%
                          </span>
                          <h5 class="fw-bold mb-0">1200</h5>
                          <span class="text-uppercase">GOAL COMPLETIONS</span>
                        </div>
                      </div>
                    </div>
                    <!--end::Row-->
                  </div>
                  <!-- /.card-footer -->
                </div>
                <!-- /.card -->
              </div>
              <!-- /.col -->
            </div>
            <!--fin :courbe-->


          </div>
          <!--fin Container-->
        </div>
        <!--fin App Content-->

      </div>
    </main>


    <?php require_once './includes/footer.php'; ?>
  </div>
  <?php require_once './includes/scripts.php'; ?>
  <!-- lel courbe -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.6.1/dist/chart.umd.min.js" integrity="sha256-hzZMwrKnamaEkp59a4Cxiol3lNs4QAyv8fzxeRxpJdI=" crossorigin="anonymous"></script>
  <script src="../assets/js/courbe_back.js"></script>
</body>

</html>

