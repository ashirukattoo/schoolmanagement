<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>STMS | <?= $pageTitle ?? "System" ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Local CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome/css/all.min.css') ?>">
    <link href="<?= base_url('assets/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/datatables/buttons.bootstrap4.min.css') ?>">
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">

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
</head>
<body>
<div class="container mt-5">
    <div class="card col-md-6 mx-auto p-4 shadow-lg">
        <h3 class="text-center mb-3">🧾 Register User</h3>
        <form action="<?= base_url('attemptRegister') ?>" method="post">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>First Name</label>
                    <input type="text" name="fname" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Middle Name</label>
                    <input type="text" name="mname" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Last Name</label>
                    <input type="text" name="lname" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Sex</label>
                <select name="sex" class="form-control">
                    <option>Male</option>
                    <option>Female</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Occupation</label>
                <input type="text" name="occupation" class="form-control">
            </div>
            <div class="mb-3">
                <label>Role</label>
                <select name="role" class="form-control">
                    <option value="employee">Employee</option>
                    <option value="guardian">Guardian</option>
                </select>
            </div>
            <button class="btn btn-success w-25">Register</button>
            <p class="text-center mt-3">Already registered? <a href="<?= base_url('login') ?>">Login</a></p>
        </form>
    </div>
</div>
</body>
</html>