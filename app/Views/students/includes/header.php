<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>STMS | <?= $pageTitle ?? "System" ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap & FontAwesome -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome/css/all.min.css') ?>">

    <!-- DataTables -->
    <link rel="stylesheet" href="<?= base_url('assets/datatables/dataTables.bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/datatables/buttons.bootstrap4.min.css') ?>">

    <!-- Optional: CDN (remove local if using this) -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css"> -->

    <!-- Custom Styles -->
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">

    <style>
        /* Sidebar styles */
        .sidebar {
            background-color: #343a40;
            color: #fff;
            height: 100vh;
            width: 250px;
            padding-top: 20px;
            transition: width 0.3s;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            overflow-x: hidden;
        }
        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #007bff;
            color: #fff;
        }
        .sidebar.collapsed {
            width: 80px;
        }
        .sidebar.collapsed a span {
            display: none;
        }
        .sidebar.collapsed h4 {
            display: none;
        }

        /* Main content */
        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s;
        }
        .sidebar.collapsed ~ .main-content {
            margin-left: 80px;
        }

        /* Top navbar */
        .top-navbar {
            background-color: #fff;
            padding: 10px 20px;
            border-bottom: 1px solid #dee2e6;
        }
        .toggle-btn {
            font-size: 24px;
            cursor: pointer;
        }

        /* Responsive: collapse sidebar on small screens */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
            }
            .sidebar.collapsed {
                width: 250px;
            }
            .main-content {
                margin-left: 0;
            }
            .sidebar.collapsed ~ .main-content {
                margin-left: 250px;
            }
        }

        /* Cards */
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        /* Chart container */
        .chart-container {
            width: 100%;
            height: 300px;
        }
    </style>
    <!-- jQuery (must be first) -->
    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>

    <!-- Bootstrap Bundle (includes Popper) -->
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>

    <!-- jQuery Easing (optional for animations) -->
    <script src="<?= base_url('assets/jquery/jquery.easing.min.js') ?>"></script>
</head>
<body>