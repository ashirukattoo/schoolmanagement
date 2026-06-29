<!DOCTYPE html>
<html>
<head>

    <title>SMS History</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>
<body>

<div class="container mt-4">

    <h3>SMS History</h3>

    <table class="table table-bordered table-striped">

        <thead>

            <tr>
                <th>#</th>
                <th>Phone</th>
                <th>Message</th>
                <th>Status</th>
                <th>Date</th>
            </tr>

        </thead>

        <tbody>

        <?php foreach($sms as $row): ?>

            <tr>

                <td><?= $row['id'] ?></td>

                <td><?= $row['phone'] ?></td>

                <td><?= $row['message'] ?></td>

                <td>

                    <?php if($row['status'] == 'Sent'): ?>

                        <span class="badge bg-success">
                            Sent
                        </span>

                    <?php else: ?>

                        <span class="badge bg-danger">
                            Failed
                        </span>

                    <?php endif; ?>

                </td>

                <td><?= $row['sent_at'] ?></td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

</div>

</body>
</html>