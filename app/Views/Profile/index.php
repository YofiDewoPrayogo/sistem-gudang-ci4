<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Profile</h1>
</div>

<div class="row">
    <div class="col-md-6">
        <form action="<?= base_url('profile/update') ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= esc($user['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= esc($user['username']) ?>" required>
            </div>

            <hr>
            <p class="text-muted">Isi bagian di bawah ini hanya jika Anda ingin mengubah password.</p>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="pass_confirm" class="form-label">Konfirmasi Password Baru</label>
                <input type="password" class="form-control" id="pass_confirm" name="pass_confirm">
            </div>

            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>