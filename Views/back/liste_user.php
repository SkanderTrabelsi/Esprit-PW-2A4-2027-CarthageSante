<?php
$pageTitle = "Liste des utilisateurs";
$currentPage = "liste_user";
?>

<!DOCTYPE html>
<html lang="fr">

<?php require_once './includes/head.php'; ?>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php require_once './includes/header.php'; ?>
        <?php require_once './includes/sidebar.php'; ?>

        <main class="app-main">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-sm-6">
                            <h1 class="mb-0 fs-3">Liste de tous les utilisateurs</h1>
                        </div>
                        <div class="col-sm-6">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb float-sm-end">
                                    <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">utilisateurs</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content Header-->
            <!--begin::App Content-->
            <div class="app-content">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-12">
                            <!--begin::Card-->
                            <div class="card mb-4">
                                <!--begin::Card Header-->
                                <div class="card-header">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-12 col-md-4">
                                            <h3 class="card-title">Dictionaire des utilisateurs</h3>
                                        </div>
                                        <div class="col-12 col-md-8">
                                            <div class="d-flex flex-wrap justify-content-md-end gap-2">
                                                <div class="input-group input-group-sm w-auto">
                                                    <span class="input-group-text">
                                                        <i class="bi bi-search" aria-hidden="true"></i>
                                                    </span>
                                                    <input type="search" id="user-search" class="form-control"
                                                        placeholder="Search users" aria-label="Search users"
                                                        style="width: 180px" />
                                                </div>
                                                <select id="user-role-filter" class="form-select form-select-sm w-auto"
                                                    aria-label="Filter by role">
                                                    <option value="all" selected>tous les rôles</option>
                                                    <option value="admin">Admin</option>
                                                    <option value="client">Client</option>
                                                    <option value="medecin">Medecin</option>
                                                    <option value="pharmacie">Pharmacie</option>
                                                </select>
                                                <a href="add_user.php">
                                                    <button type="button" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-person-plus-fill me-1" aria-hidden="true"> </i>
                                                        Ajouter un utilisateur
                                                    </button>                                           
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Card Header-->
                                <!--begin::Card Body-->
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle m-0">
                                            <thead>
                                                <tr>
                                                    <th>nom</th>
                                                    <th>Email</th>
                                                    <th>Role</th>
                                                    <th>Status</th>
                                                    <th>Created</th>
                                                    <th class="text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="./assets/img/user1-128x128.jpg" alt=""
                                                                class="img-size-32 rounded-circle me-2" />
                                                            <span class="fw-medium">Alexander Pierce</span>
                                                        </div>
                                                    </td>
                                                    <td>alexander.pierce@example.com</td>
                                                    <td>
                                                        <span class="badge text-bg-danger"> Administrator </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge text-bg-success">Active</span>
                                                    </td>
                                                    <td>Mar 12, 2025</td>
                                                    <td class="text-end">
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="modif_user.php" class="btn btn-outline-secondary"
                                                                aria-label="Edit Alexander Pierce">
                                                                <i class="bi bi-pencil" aria-hidden="true"> </i>
                                                            </a>
                                                            <button type="button" class="btn btn-outline-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal-delete-user"
                                                                aria-label="Delete Alexander Pierce">
                                                                <i class="bi bi-trash" aria-hidden="true"> </i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.table-responsive -->
                                </div>
                                <!--end::Card Body-->
                                <!--begin::Card Footer-->
                                <div class="card-footer clearfix">
                                    <div class="float-start pt-1 fs-7 text-body-secondary">
                                        Showing 1 to 9 of 42 users
                                    </div>
                                    <ul class="pagination pagination-sm m-0 float-end">
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" aria-label="Previous"> &laquo; </a>
                                        </li>
                                        <li class="page-item active">
                                            <a class="page-link" href="#">1</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">2</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">3</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">4</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">5</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="#" aria-label="Next"> &raquo; </a>
                                        </li>
                                    </ul>
                                </div>
                                <!--end::Card Footer-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!--end::Row-->

                    <!--begin::Delete User Modal-->
                    <div class="modal fade" id="modal-delete-user" tabindex="-1"
                        aria-labelledby="modal-delete-user-label" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modal-delete-user-label">Delete user</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="mb-0">
                                        Are you sure you want to delete this user? All content owned by the account
                                        will be reassigned to the site administrator. This action cannot be undone.
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Cancel
                                    </button>
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                        Delete user
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Delete User Modal-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content-->
        </main>

        <?php require_once './includes/footer.php'; ?>
    </div>


    <?php require_once './includes/scripts.php'; ?>
</body>

</html>