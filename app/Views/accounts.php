<?= $this->extend('default') ?>

<?= $this->section('content') ?>
<h1>Comptes bancaires</h1>

<?php if (isset($accounts) && count($accounts) > 0): ?>
    <table class="table">
        <thead>
            <tr>
                <th>Numéro</th>
                <th>Solde</th>
                <th>Découvert autorisé</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($accounts as $account): ?>
            <tr>
                <td><?= $account->numero ?></td>
                <td><?= $account->balance ?> €</td>
                <td><?= $account->overdraft ?> €</td>
                <td>
                    <a href="/accounts/<?= $account->id ?>" class="btn btn-info">Actions</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucun compte bancaire trouvé.</p>
<?php endif; ?>
<?= $this->endSection() ?>
