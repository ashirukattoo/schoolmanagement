<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LSS | <?= $pageTitle ?? "System" ?></title>
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
    <link rel="stylesheet" href="<?= base_url('adminLite/css/adminlte.css') ?>">

    <style>
        /* Sidebar styles */
        .sidebar {
            background: #0dcaf0;
            color: #fff;
            height: 100vh;
            width: 250px;
            padding-top: 20px;
            transition: all 0.3s;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            overflow-y: hidden;
        }
        .sidebar a {
            color: #000000;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #0dccff;
            color: #fff;
        }
        .sidebar.collapsed {
            width: 70px;
        }
        .sidebar.collapsed a span {
            display: none;
        }
        .sidebar.collapsed h4 {
            display: none;
        }
        .sidebar.collapsed .menu-text {
            display: none;
        }
        .sidebar.collapsed i.fas{
            margin-right: 0;
        }

        /* Hover expand on collapsed */
        .sidebar.collapsed:hover{
            width: 250px;
        }
        .sidebar.collapsed:hover .menu-text {
            display: inline;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: #000;
            text-decoration: none;
        }
        .sidebar-menu li a:hover, .sidebar-menu li a:active{
            background-color: #0dccff;
            color: #fff;
        }
        .submenu{
            display: none;
        }
        .submenu.show{
            display: block;
        }
        .has-submenu > a{
            justify-content: space-between;
        }

        /* Main content */
        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s;
        }
        .sidebar.collapsed .main-content {
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
        /*logo container */
        .logo-container{
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow-x: hidden;
            display: fle;
            aign-itemsw: center;
            background-color: #ffffff;
            box-shadow:  4px 10px rgba(0, 0, 0, 0.2);
        }
        /*logo image */
        .sidebar-logo{
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        /* Centered text below logo */
        .sidebar-title{
            font-weight: bold;
            color: #fff;
            font-size: 14px;
            text-align: center;
        }

        /* When sidebar is collapsed, hide the title, keep only logo */
        .sidebar.collapsed .sidebar-tiltle{
            display: none;
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