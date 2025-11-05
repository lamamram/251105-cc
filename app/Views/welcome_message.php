<?= $this->extend('default') ?>

<?= $this->section('content') ?>
<?php if (isset($client)): ?>
    <h1>Bonjour <?= $client["name"] ?> !</h1>
    <p>tel: <?= $client["phone_number"] ?> </p>
    <p>depuis: <?= $client["created_at"] ?> </p>
<?php endif ?>
<?= $this->endSection() ?>
