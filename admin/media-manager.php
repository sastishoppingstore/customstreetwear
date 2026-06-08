<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Media Manager';
$allowedDirs = ['settings', 'home-sections', 'sliders', 'categories', 'products', 'pages', 'blogs', 'locations'];
$uploadDir = $_POST['upload_dir'] ?? ($_GET['dir'] ?? 'settings');
if (!in_array($uploadDir, $allowedDirs, true)) {
    $uploadDir = 'settings';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    if (isset($_FILES['media_file']) && $_FILES['media_file']['error'] === UPLOAD_ERR_OK) {
        $result = uploadFile($_FILES['media_file'], $uploadDir, array_merge(ALLOWED_IMAGE_TYPES, ['ico']));
        if ($result['success']) {
            setFlash('success', 'Uploaded: ' . $result['path']);
            redirect('media-manager.php?dir=' . urlencode($uploadDir));
        }
        setFlash('error', $result['error']);
    } else {
        setFlash('error', 'Choose a file to upload.');
    }
}

$baseDir = UPLOADS_PATH . '/' . $uploadDir;
$files = [];
if (is_dir($baseDir)) {
    foreach (glob($baseDir . '/*') ?: [] as $file) {
        if (!is_file($file)) continue;
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (!in_array($ext, array_merge(ALLOWED_IMAGE_TYPES, ['ico']), true)) continue;
        $files[] = [
            'path' => '/uploads/' . $uploadDir . '/' . basename($file),
            'name' => basename($file),
            'size' => filesize($file),
            'mtime' => filemtime($file),
        ];
    }
    usort($files, fn($a, $b) => $b['mtime'] <=> $a['mtime']);
}

include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header">
        <h1 class="content-title">Media Manager</h1>
    </div>
    <?php echo showFlash(); ?>

    <div class="admin-card" style="margin-bottom:20px;">
        <form method="POST" action="media-manager.php" enctype="multipart/form-data" style="display:grid;grid-template-columns:180px 1fr auto;gap:12px;align-items:end;">
            <?php echo csrfField(); ?>
            <div class="form-group" style="margin:0;">
                <label class="form-label">Folder</label>
                <select name="upload_dir" class="form-select">
                    <?php foreach ($allowedDirs as $dir): ?>
                    <option value="<?php echo e($dir); ?>" <?php echo $uploadDir === $dir ? 'selected' : ''; ?>><?php echo e($dir); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="margin:0;">
                <label class="form-label">Upload Image/Icon</label>
                <input type="file" name="media_file" class="form-input" accept=".jpg,.jpeg,.png,.webp,.gif,.ico" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>

    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px;">
        <?php foreach ($allowedDirs as $dir): ?>
        <a href="media-manager.php?dir=<?php echo urlencode($dir); ?>" class="btn <?php echo $uploadDir === $dir ? 'btn-primary' : 'btn-outline'; ?> btn-sm"><?php echo e($dir); ?></a>
        <?php endforeach; ?>
    </div>

    <div class="media-grid">
        <?php foreach ($files as $file): ?>
        <div class="media-card">
            <div class="media-preview">
                <img src="<?php echo e($file['path']); ?>" alt="<?php echo e($file['name']); ?>">
            </div>
            <div class="media-info">
                <strong title="<?php echo e($file['name']); ?>"><?php echo e(truncate($file['name'], 28)); ?></strong>
                <small><?php echo e(round($file['size'] / 1024, 1)); ?> KB</small>
                <input type="text" class="form-input media-path" value="<?php echo e($file['path']); ?>" readonly onclick="this.select();navigator.clipboard&&navigator.clipboard.writeText(this.value);">
            </div>
        </div>
        <?php endforeach; ?>
        <?php if (!$files): ?>
        <div class="admin-card" style="grid-column:1/-1;text-align:center;color:var(--muted);">No media found in this folder.</div>
        <?php endif; ?>
    </div>
</div>

<style>
.media-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:16px; }
.media-card { background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-md); overflow:hidden; }
.media-preview { aspect-ratio:1.4; display:flex; align-items:center; justify-content:center; background:var(--bg); border-bottom:1px solid var(--border); padding:12px; }
.media-preview img { max-width:100%; max-height:100%; object-fit:contain; }
.media-info { padding:12px; display:grid; gap:8px; }
.media-info small { color:var(--muted); }
.media-path { font-size:11px; padding:6px 8px; }
</style>
<?php include __DIR__ . '/includes/footer.php'; ?>
