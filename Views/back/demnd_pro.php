<?php
$pageTitle = "compte pro";
$currentPage = "demnd_pro";
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
                            <h1 class="mb-0 fs-3">demande de compte pro</h1>
                        </div>
                        <div class="col-sm-6">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb float-sm-end">
                                    <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">compte pro</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content Header-->
        </main>

        <?php require_once './includes/footer.php'; ?>
    </div>


    <?php require_once './includes/scripts.php'; ?>
</body>

</html>