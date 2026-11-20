<?php
if (!isset($currentPage)) {
    $currentPage = '';
}

function navActive($page, $currentPage) {
    return $page === $currentPage ? 'active' : '';
}
?>

<aside class="app-sidebar shadow" data-bs-theme="dark">
  <!--begin::Sidebar Brand-->
  <div class="sidebar-brand">
    <!--begin::Brand Link-->
    <a href="./index.php" class="brand-link">
      <!--begin::Brand Image-->
      <img src="../assets/img/logo carthage sante.jpeg" alt="carthage Logo" class="brand-image  shadow" />
      <!--end::Brand Image-->
      <!--begin::Brand Text-->
      <span class="brand-text fw-light">Carthage Sante</span>
      <!--end::Brand Text-->
    </a>
    <!--end::Brand Link-->
  </div>
  <!--end::Sidebar Brand-->
  <!--begin::Sidebar Search-->
  <div class="sidebar-search" role="search">
    <label for="sidebar-search-input" class="visually-hidden">Filter menu</label>
    <input type="search" id="sidebar-search-input" class="form-control form-control-sm" placeholder="Filter menu…"
      autocomplete="off" data-lte-toggle="sidebar-search" data-lte-target="#navigation" />
    <p class="fs-7 text-secondary mt-2 mb-0" data-lte-search-empty role="status" hidden>
      No matching pages.
    </p>
  </div>
  <!--end::Sidebar Search-->

  <!--begin::Sidebar Wrapper-->
  <div class="sidebar-wrapper">
    <nav class="mt-2" aria-label="Main navigation">
      <!--begin::Sidebar Menu-->
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" data-accordion="false" id="navigation">
        <!--begin: dashboard-->
        <li class="nav-item">
          <a href="#" class="nav-link active">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>
              Dashboard
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="./index.html" class="nav-link active">
                <i class="nav-icon bi bi-circle"></i>
                <p>Dashboard v1</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="./index2.html" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Dashboard v2</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-header">PAGES</li>
        <!--begin: users-->
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-people"></i>
            <p>Users
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="liste_user.php" class="nav-link active">
                <i class="nav-icon bi bi-circle"></i>
                <p>Liste des utilisateurs</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="add_user.php" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Ajouter un utilisateur</p>
              </a>
            </li>
            </li>
            <li class="nav-item">
              <a href="demnd_pro.php" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Demande comptes pro</p>
              </a>
            </li>
          </ul>
        </li>
      <!--end::Sidebar Menu-->
    </nav>
  </div>
  <!--end::Sidebar Wrapper-->

</aside>