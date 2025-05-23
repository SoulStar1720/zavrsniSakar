<?php
include('../../includes/auth.php');
include('../../includes/db_connection.php');
/*include('../../includes/header.php');*/
requireAdmin();
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
<a href="../../logout_admin.php" class="btn btn-outline-light">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="d-none d-md-inline">Odjava</span>
                    </a>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="mb-0">
                    <i class="bi bi-speedometer2"></i> Admin Dashboard
                </h3>
            </div>

            <div class="card-body">
                <div class="row g-4">
                    <!-- Članovi Card -->
                    <div class="col-md-4">
                        <div class="card h-100 border-primary">
                            <div class="card-body text-center">
                                <h2 class="card-title">
                                    <i class="bi bi-people-fill text-primary"></i>
                                </h2>
                                <h4 class="card-subtitle mb-3">Upravljanje članovima</h4>
                                <a href="../clanovi/index.php" class="btn btn-primary w-100">
                                    <i class="bi bi-arrow-right-circle"></i> Pregled članova
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Knjige Card -->
                    <div class="col-md-4">
                        <div class="card h-100 border-success">
                            <div class="card-body text-center">
                                <h2 class="card-title">
                                    <i class="bi bi-book-fill text-success"></i>
                                </h2>
                                <h4 class="card-subtitle mb-3">Upravljanje knjigama</h4>
                                <a href="../knjige/index.php" class="btn btn-success w-100">
                                    <i class="bi bi-arrow-right-circle"></i> Pregled knjiga
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Posudbe Card -->
                    <div class="col-md-4">
                        <div class="card h-100 border-warning">
                            <div class="card-body text-center">
                                <h2 class="card-title">
                                    <i class="bi bi-arrow-left-right text-warning"></i>
                                </h2>
                                <h4 class="card-subtitle mb-3">Upravljanje posudbama</h4>
                                <a href="../posudbe/index.php" class="btn btn-warning w-100">
                                    <i class="bi bi-arrow-right-circle"></i> Pregled posudbi
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>