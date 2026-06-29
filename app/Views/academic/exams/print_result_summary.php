<!DOCTYPE html>
<html>
<head>
    <title>Result Summary</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <style>
        body {
            padding: 20px;
            font-size: 14px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 12px;
            color: gray;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="text-center mb-3">
    <h5><?= strtoupper($info['exam'].' - '.$info['class'].' '.$info['year']); ?></h5>
    <h6>DIVISION PERFORMANCE SUMMARY</h6>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>SEX</th>
            <th>I</th>
            <th>II</th>
            <th>III</th>
            <th>IV</th>
            <th>0</th>
            <th>Inc</th>
            <th>TOTAL</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($general_results as $sex => $divisions): ?>
        <tr>
            <td><?= $sex ?></td>
            <td><?= $divisions['I'] ?></td>
            <td><?= $divisions['II'] ?></td>
            <td><?= $divisions['III'] ?></td>
            <td><?= $divisions['IV'] ?></td>
            <td><?= $divisions['0'] ?></td>
            <td><?= $divisions['Inc'] ?></td>
            <td><strong><?= $divisions['TOTAL'] ?></strong></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td><strong>GRAND TOTAL</strong></td>
            <td><?= $grand_total['I'] ?></td>
            <td><?= $grand_total['II'] ?></td>
            <td><?= $grand_total['III'] ?></td>
            <td><?= $grand_total['IV'] ?></td>
            <td><?= $grand_total['0'] ?></td>
            <td><?= $grand_total['Inc'] ?></td>
            <td><?= $grand_total['TOTAL'] ?></td>
        </tr>
    </tfoot>
</table>

<!-- ✅ Footer -->
<div class="footer">
    Printed on: <?= date('d M Y H:i:s') ?>
</div>

</body>
</html>