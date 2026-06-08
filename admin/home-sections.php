<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Homepage Sections';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    $id = intval($_POST['id'] ?? 0);
    $data = [
        'title' => $_POST['title'] ?? '',
        'subtitle' => $_POST['subtitle'] ?? '',
        'description' => $_POST['description'] ?? '',
        'image' => $_POST['image'] ?? '',
        'image_alt' => $_POST['image_alt'] ?? '',
        'button_text' => $_POST['button_text'] ?? '',
        'button_link' => $_POST['button_link'] ?? '',
        'background_color' => $_POST['background_color'] ?? '',
        'background_image' => $_POST['background_image'] ?? '',
        'custom_css' => $_POST['custom_css'] ?? '',
        'custom_html' => $_POST['custom_html'] ?? '',
        'visibility' => $_POST['visibility'] ?? 'visible',
        'sort_order' => intval($_POST['sort_order'] ?? 0),
    ];

    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['image_file'], 'home-sections');
        if ($upload['success']) {
            $data['image'] = $upload['path'];
        } else {
            setFlash('error', 'Image upload failed: ' . $upload['error']);
            redirect('home-sections.php');
        }
    }

    if (isset($_FILES['background_image_file']) && $_FILES['background_image_file']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['background_image_file'], 'home-sections');
        if ($upload['success']) {
            $data['background_image'] = $upload['path'];
        } else {
            setFlash('error', 'Background upload failed: ' . $upload['error']);
            redirect('home-sections.php');
        }
    }

    if ($id) {
        dbExecute("UPDATE home_sections SET title=?, subtitle=?, description=?, image=?, image_alt=?, button_text=?, button_link=?, background_color=?, background_image=?, custom_css=?, custom_html=?, visibility=?, sort_order=? WHERE id=?",
            [$data['title'], $data['subtitle'], $data['description'], $data['image'], $data['image_alt'], $data['button_text'], $data['button_link'], $data['background_color'], $data['background_image'], $data['custom_css'], $data['custom_html'], $data['visibility'], $data['sort_order'], $id]);
    }
    refreshSettings();
    setFlash('success', 'Section updated successfully.');
    redirect('home-sections.php');
}

$sections = dbFetchAll("SELECT * FROM home_sections ORDER BY sort_order");
include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header">
        <h1 class="content-title">Homepage Sections Manager</h1>
    </div>
    <?php echo showFlash(); ?>

    <div style="display:grid;gap:16px;">
        <?php foreach ($sections as $sec): ?>
        <div class="admin-card" style="border-left:3px solid <?php echo $sec['visibility'] === 'visible' ? 'var(--success, #39ff14)' : 'var(--danger, #ff4444)'; ?>;">
            <form method="POST" action="home-sections.php" enctype="multipart/form-data">
                <?php echo csrfField(); ?>
                <input type="hidden" name="id" value="<?php echo $sec['id']; ?>">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;flex-wrap:wrap;gap:8px;">
                    <div>
                        <strong style="font-size:16px;"><?php echo e($sec['section_name']); ?></strong>
                        <span class="badge <?php echo $sec['visibility']==='visible'?'badge-new':'badge-rejected';?>" style="margin-left:8px;">
                            <?php echo $sec['visibility']==='visible' ? 'Visible' : 'Hidden'; ?>
                        </span>
                        <span style="font-size:12px;color:var(--muted);margin-left:8px;"><?php echo e($sec['section_key']); ?></span>
                    </div>
                    <div style="display:flex;gap:8px;">
                        <select name="visibility" class="form-select" style="width:auto;padding:4px 8px;font-size:12px;">
                            <option value="visible" <?php echo $sec['visibility']==='visible'?'selected':'';?>>Visible</option>
                            <option value="hidden" <?php echo $sec['visibility']==='hidden'?'selected':'';?>>Hidden</option>
                        </select>
                        <input type="number" name="sort_order" class="form-input" style="width:60px;padding:4px 8px;font-size:12px;" value="<?php echo intval($sec['sort_order']); ?>" min="0">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div class="form-group" style="margin:0;">
                        <label class="form-label" style="font-size:12px;">Title</label>
                        <input type="text" name="title" class="form-input" style="font-size:13px;padding:6px 10px;" value="<?php echo e($sec['title'] ?: $sec['section_name']); ?>">
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label" style="font-size:12px;">Subtitle/Label</label>
                        <input type="text" name="subtitle" class="form-input" style="font-size:13px;padding:6px 10px;" value="<?php echo e($sec['subtitle'] ?? ''); ?>">
                    </div>
                    <div class="form-group" style="margin:0;grid-column:1/-1;">
                        <label class="form-label" style="font-size:12px;">Description</label>
                        <textarea name="description" class="form-textarea" style="font-size:13px;padding:6px 10px;min-height:60px;"><?php echo e($sec['description'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label" style="font-size:12px;">Button Text</label>
                        <input type="text" name="button_text" class="form-input" style="font-size:13px;padding:6px 10px;" value="<?php echo e($sec['button_text'] ?? ''); ?>">
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label" style="font-size:12px;">Button Link</label>
                        <input type="text" name="button_link" class="form-input" style="font-size:13px;padding:6px 10px;" value="<?php echo e($sec['button_link'] ?? ''); ?>">
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label" style="font-size:12px;">Image Upload</label>
                        <input type="hidden" name="image" value="<?php echo e($sec['image'] ?? ''); ?>">
                        <?php if (!empty($sec['image'])): ?>
                        <div style="margin-bottom:6px;"><img src="<?php echo e($sec['image']); ?>" style="width:120px;height:70px;object-fit:cover;border-radius:6px;" onerror="this.style.display='none';"></div>
                        <?php endif; ?>
                        <input type="file" name="image_file" class="form-input" style="font-size:12px;padding:6px 10px;margin-top:6px;" accept=".jpg,.jpeg,.png,.webp,.gif">
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label" style="font-size:12px;">Image Alt</label>
                        <input type="text" name="image_alt" class="form-input" style="font-size:13px;padding:6px 10px;" value="<?php echo e($sec['image_alt'] ?? ''); ?>">
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label" style="font-size:12px;">Background Color</label>
                        <input type="text" name="background_color" class="form-input" style="font-size:13px;padding:6px 10px;" value="<?php echo e($sec['background_color'] ?? ''); ?>" placeholder="#0a0a0a">
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label" style="font-size:12px;">Background Image Upload</label>
                        <input type="hidden" name="background_image" value="<?php echo e($sec['background_image'] ?? ''); ?>">
                        <?php if (!empty($sec['background_image'])): ?>
                        <div style="margin-bottom:6px;"><img src="<?php echo e($sec['background_image']); ?>" style="width:120px;height:70px;object-fit:cover;border-radius:6px;" onerror="this.style.display='none';"></div>
                        <?php endif; ?>
                        <input type="file" name="background_image_file" class="form-input" style="font-size:12px;padding:6px 10px;margin-top:6px;" accept=".jpg,.jpeg,.png,.webp,.gif">
                    </div>
                    <div class="form-group" style="margin:0;grid-column:1/-1;">
                        <label class="form-label" style="font-size:12px;">Custom CSS</label>
                        <textarea name="custom_css" class="form-textarea" style="font-size:13px;padding:6px 10px;min-height:60px;"><?php echo e($sec['custom_css'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group" style="margin:0;grid-column:1/-1;">
                        <label class="form-label" style="font-size:12px;">Custom HTML</label>
                        <textarea name="custom_html" class="form-textarea" style="font-size:13px;padding:6px 10px;min-height:80px;"><?php echo e($sec['custom_html'] ?? ''); ?></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-sm" style="margin-top:12px;">Save Section</button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.admin-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 20px; }
.content-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
.content-title { font-size: 24px; font-weight: 700; }
.form-label { display: block; font-weight: 600; margin-bottom: 4px; color: var(--text); }
.form-input, .form-select, .form-textarea { width: 100%; background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius-sm); color: var(--text); }
.form-textarea { resize: vertical; }
.badge-new { background: #39ff14; color: #000; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; }
.badge-rejected { background: #ff4444; color: #fff; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; }
</style>
<?php include __DIR__ . '/includes/footer.php'; ?>
