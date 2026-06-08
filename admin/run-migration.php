<?php
/**
 * Run v2 Migration - Add new tables and columns
 * Access: /admin/run-migration.php
 */
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Run Database Migration';
$output = '';
$migrationFiles = [
    'migration-new-tables.sql' => 'Core new tables: FAQs, delivery charges, orders',
    'migration-v2-seo-settings.sql' => 'SEO v2, redirects, reviews, FAQ categories, home sections, location SEO',
    'migration-homepage-settings.sql' => 'Homepage editable content and target SEO defaults',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['run'])) {
    requireCsrf();
    if (trim($_POST['confirm_text'] ?? '') !== 'RUN') {
        setFlash('error', 'Type RUN to confirm migration execution.');
        redirect('run-migration.php');
    }

    $selectedFiles = $_POST['migration_files'] ?? [];
    if (!is_array($selectedFiles) || empty($selectedFiles)) {
        setFlash('error', 'Select at least one migration file.');
        redirect('run-migration.php');
    }

    $success = 0;
    $errors = [];
    $executedFiles = [];
    
    foreach ($selectedFiles as $file) {
        if (!isset($migrationFiles[$file])) {
            $errors[] = "Skipped unknown migration file: " . e($file);
            continue;
        }
        $path = __DIR__ . '/../' . $file;
        if (!file_exists($path)) {
            $errors[] = "Missing migration file: " . e($file);
            continue;
        }
        $sql = preg_replace('/^\s*--.*$/m', '', file_get_contents($path));
        $statements = explode(';', $sql);
        $executedFiles[] = $file;
        
        foreach ($statements as $stmt) {
            $stmt = trim($stmt);
            if (empty($stmt)) continue;
            
            try {
                if (preg_match('/^ALTER TABLE.*ADD COLUMN IF NOT EXISTS/i', $stmt)) {
                    preg_match('/ADD COLUMN IF NOT EXISTS\s+`?(\w+)`?\s/i', $stmt, $m);
                    $colName = $m[1] ?? '';
                    preg_match('/ALTER TABLE\s+`?(\w+)`?\s/i', $stmt, $m2);
                    $tableName = $m2[1] ?? '';
                    
                    if ($colName && $tableName) {
                        try {
                            $check = dbFetchOne("SHOW COLUMNS FROM `{$tableName}` LIKE '{$colName}'");
                            if (!$check) {
                                $cleanStmt = preg_replace('/IF NOT EXISTS/i', '', $stmt);
                                dbExecute($cleanStmt);
                            }
                            $success++;
                        } catch (Exception $e) {
                            $errors[] = "{$file}: {$tableName}.{$colName}: " . $e->getMessage();
                        }
                    }
                } else {
                    dbExecute($stmt);
                    $success++;
                }
            } catch (Exception $e) {
                $errors[] = "{$file}: " . substr($stmt, 0, 60) . '... : ' . $e->getMessage();
            }
        }
    }
    
    refreshSettings();
    $output = '<div class="alert alert-success">Migration completed. ' . $success . ' statements executed successfully.<br><small>Files: ' . e(implode(', ', $executedFiles)) . '</small></div>';
    if ($errors) {
        $output .= '<div class="alert alert-warning">' . count($errors) . ' non-critical errors (likely already exists):<br><small>' . implode('<br>', array_slice($errors, 0, 10)) . '</small></div>';
    }
}

include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header">
        <h1 class="content-title">Database Migration</h1>
    </div>
    <?php echo $output; ?>
    <?php echo showFlash(); ?>
    
    <div class="admin-card" style="max-width:600px;">
        <h3 style="margin-bottom:12px;">Database Migrations</h3>
        <p style="color:var(--muted);margin-bottom:16px;font-size:14px;">Run the required migrations after uploading code. Back up the database first.</p>
        <form method="POST" action="run-migration.php">
            <?php echo csrfField(); ?>
            <input type="hidden" name="run" value="1">
            <div style="display:grid;gap:10px;margin-bottom:16px;">
                <?php foreach ($migrationFiles as $file => $desc): ?>
                <label style="display:flex;gap:10px;align-items:flex-start;padding:10px;border:1px solid var(--border);border-radius:var(--radius-sm);">
                    <input type="checkbox" name="migration_files[]" value="<?php echo e($file); ?>" checked>
                    <span><strong><?php echo e($file); ?></strong><br><small style="color:var(--muted);"><?php echo e($desc); ?></small></span>
                </label>
                <?php endforeach; ?>
            </div>
            <div class="form-group">
                <label class="form-label">Type RUN to confirm</label>
                <input type="text" name="confirm_text" class="form-input" autocomplete="off" required>
            </div>
            <button type="submit" class="btn btn-primary" onclick="return confirm('Run selected migrations? Make sure you have backed up your database first.')">Run Selected Migrations</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
