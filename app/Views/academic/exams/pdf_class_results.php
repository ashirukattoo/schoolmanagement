<style>
    body { font-family: sans-serif; font-size: 11pt; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #444; padding: 5px; text-align: center; }
    th { background-color: #f0f0f0; }
    .student { text-align: left; font-weight: bold; }
</style>

<table>
    <thead>
        <tr>
            <th>Student</th>
            <?php foreach ($subjects as $sub): ?>
                <th><?= esc($sub) ?></th>
            <?php endforeach; ?>
            <th>Points</th>
            <th>Division</th>
            <th>Remark</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $r): ?>
            <?php $decoded = $r['decoded']; ?>
            <tr>
                <td class="student"><?= esc($r['student']) ?></td>
                <?php
                    $subMap = array_column($decoded['subjects'], null, 'subject');
                    foreach ($subjects as $sub):
                        $score = $subMap[$sub]['score'] ?? '-';
                ?>
                    <td><?= esc(number_format($score, 0)) ?></td>
                <?php endforeach; ?>
                <td><?= esc($decoded['points'] ?? '-') ?></td>
                <td><?= esc($decoded['division'] ?? '-') ?></td>
                <td><?= esc($decoded['remark'] ?? '-') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>