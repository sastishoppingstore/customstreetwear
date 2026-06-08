<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Location SEO';
$tab = $_GET['tab'] ?? 'state';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    $stateSlug = $_POST['state_slug'] ?? '';
    $citySlug = $_POST['city_slug'] ?? null;
    $type = $citySlug ? 'city' : 'state';
    
    $data = [
        'page_title' => $_POST['page_title'] ?? '',
        'meta_description' => $_POST['meta_description'] ?? '',
        'h1_heading' => $_POST['h1_heading'] ?? '',
        'content_top' => $_POST['content_top'] ?? '',
        'content_bottom' => $_POST['content_bottom'] ?? '',
        'focus_keyword' => $_POST['focus_keyword'] ?? '',
    ];
    
    $existing = dbFetchOne("SELECT id FROM location_seo WHERE location_type=? AND state_slug=? AND city_slug=?", [$type, $stateSlug, $citySlug]);
    
    if ($existing) {
        dbExecute("UPDATE location_seo SET page_title=?, meta_description=?, h1_heading=?, content_top=?, content_bottom=?, focus_keyword=? WHERE id=?",
            [$data['page_title'], $data['meta_description'], $data['h1_heading'], $data['content_top'], $data['content_bottom'], $data['focus_keyword'], $existing['id']]);
    } else {
        dbExecute("INSERT INTO location_seo (location_type, state_slug, city_slug, page_title, meta_description, h1_heading, content_top, content_bottom, focus_keyword) VALUES (?,?,?,?,?,?,?,?,?)",
            [$type, $stateSlug, $citySlug, $data['page_title'], $data['meta_description'], $data['h1_heading'], $data['content_top'], $data['content_bottom'], $data['focus_keyword']]);
    }
    
    setFlash('success', 'Location SEO saved for ' . ($citySlug ? getCityName($citySlug) . ', ' : '') . getUSAState($stateSlug)['name']);
    redirect('location-seo.php?tab=' . $tab);
}

include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header">
        <h1 class="content-title">Location SEO</h1>
    </div>
    <?php echo showFlash(); ?>
    
    <div class="admin-card">
        <div style="display:flex;gap:10px;margin-bottom:20px;">
            <a href="location-seo.php?tab=state" class="btn <?php echo $tab==='state'?'btn-primary':'btn-outline';?> btn-sm">State SEO</a>
            <a href="location-seo.php?tab=city" class="btn <?php echo $tab==='city'?'btn-primary':'btn-outline';?> btn-sm">City SEO</a>
        </div>
        
        <?php if ($tab === 'state'): ?>
        <form method="POST" action="location-seo.php">
            <?php echo csrfField(); ?>
            <input type="hidden" name="tab" value="state">
            <div class="form-group">
                <label class="form-label">Select State</label>
                <select name="state_slug" class="form-select" required onchange="this.form.submit()">
                    <option value="">Select a state...</option>
                    <?php foreach (getUSAStates() as $slug => $state): 
                        $selected = ($_POST['state_slug'] ?? $_GET['state'] ?? '') === $slug ? 'selected' : '';
                        $seo = dbFetchOne("SELECT * FROM location_seo WHERE location_type='state' AND state_slug=?", [$slug]);
                    ?>
                    <option value="<?php echo $slug; ?>" <?php echo $selected; ?>><?php echo $state['name']; ?> <?php echo $seo ? '✅' : '❌'; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
        
        <?php 
        $stateSlug = $_POST['state_slug'] ?? $_GET['state'] ?? '';
        if ($stateSlug):
            $stateName = getUSAState($stateSlug)['name'] ?? '';
            $seo = dbFetchOne("SELECT * FROM location_seo WHERE location_type='state' AND state_slug=?", [$stateSlug]);
        ?>
        <form method="POST" action="location-seo.php">
            <?php echo csrfField(); ?>
            <input type="hidden" name="tab" value="state">
            <input type="hidden" name="state_slug" value="<?php echo $stateSlug; ?>">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:20px;">
                <div class="form-group">
                    <label class="form-label">Page Title (Meta Title)</label>
                    <input type="text" name="page_title" class="form-input" value="<?php echo e($seo['page_title'] ?? "Custom Apparel Manufacturer in {$stateName}"); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Focus Keyword</label>
                    <input type="text" name="focus_keyword" class="form-input" value="<?php echo e($seo['focus_keyword'] ?? "Custom Apparel Manufacturer in {$stateName}"); ?>">
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-textarea" rows="3"><?php echo e($seo['meta_description'] ?? getLocationMetaDescription('state', $stateName)); ?></textarea>
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">H1 Heading</label>
                    <input type="text" name="h1_heading" class="form-input" value="<?php echo e($seo['h1_heading'] ?? "Custom Apparel Manufacturer in {$stateName}"); ?>">
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Top Content (HTML)</label>
                    <textarea name="content_top" class="form-textarea" rows="6"><?php echo e($seo['content_top'] ?? ''); ?></textarea>
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Bottom Content (HTML)</label>
                    <textarea name="content_bottom" class="form-textarea" rows="6"><?php echo e($seo['content_bottom'] ?? ''); ?></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save SEO for <?php echo e($stateName); ?></button>
        </form>
        <?php endif; ?>
        
        <?php else: ?>
        <form method="POST" action="location-seo.php">
            <?php echo csrfField(); ?>
            <input type="hidden" name="tab" value="city">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;">
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Select State</label>
                    <select name="state_slug" class="form-select" required onchange="this.form.submit()">
                        <option value="">Select a state...</option>
                        <?php foreach (getUSAStates() as $slug => $state): 
                            $selected = ($_POST['state_slug'] ?? $_GET['state'] ?? '') === $slug ? 'selected' : '';
                        ?>
                        <option value="<?php echo $slug; ?>" <?php echo $selected; ?>><?php echo $state['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php 
                $stateSlug = $_POST['state_slug'] ?? $_GET['state'] ?? '';
                if ($stateSlug): 
                    $cities = getStateCities($stateSlug);
                ?>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Select City</label>
                    <select name="city_slug" class="form-select" required onchange="this.form.submit()">
                        <option value="">Select a city...</option>
                        <?php foreach ($cities as $city):
                            $cityName = getCityName($city);
                            $selected = ($_POST['city_slug'] ?? $_GET['city'] ?? '') === $city ? 'selected' : '';
                            $seo = dbFetchOne("SELECT * FROM location_seo WHERE location_type='city' AND state_slug=? AND city_slug=?", [$stateSlug, $city]);
                        ?>
                        <option value="<?php echo $city; ?>" <?php echo $selected; ?>><?php echo $cityName; ?> <?php echo $seo ? '✅' : '❌'; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </form>
        
        <?php 
        $citySlug = $_POST['city_slug'] ?? $_GET['city'] ?? '';
        if ($citySlug):
            $stateName = getUSAState($stateSlug)['name'] ?? '';
            $cityName = getCityName($citySlug);
            $seo = dbFetchOne("SELECT * FROM location_seo WHERE location_type='city' AND state_slug=? AND city_slug=?", [$stateSlug, $citySlug]);
        ?>
        <form method="POST" action="location-seo.php">
            <?php echo csrfField(); ?>
            <input type="hidden" name="tab" value="city">
            <input type="hidden" name="state_slug" value="<?php echo $stateSlug; ?>">
            <input type="hidden" name="city_slug" value="<?php echo $citySlug; ?>">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <div class="form-group">
                    <label class="form-label">Page Title</label>
                    <input type="text" name="page_title" class="form-input" value="<?php echo e($seo['page_title'] ?? "Apparel Manufacturer in {$cityName}, {$stateName}"); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Focus Keyword</label>
                    <input type="text" name="focus_keyword" class="form-input" value="<?php echo e($seo['focus_keyword'] ?? "Apparel Manufacturer in {$cityName}, {$stateName}"); ?>">
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-textarea" rows="3"><?php echo e($seo['meta_description'] ?? getCitySEODescription($cityName, $stateName)); ?></textarea>
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">H1 Heading</label>
                    <input type="text" name="h1_heading" class="form-input" value="<?php echo e($seo['h1_heading'] ?? "Apparel Manufacturer in {$cityName}, {$stateName}"); ?>">
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Top Content (HTML)</label>
                    <textarea name="content_top" class="form-textarea" rows="6"><?php echo e($seo['content_top'] ?? ''); ?></textarea>
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Bottom Content (HTML)</label>
                    <textarea name="content_bottom" class="form-textarea" rows="6"><?php echo e($seo['content_bottom'] ?? ''); ?></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save SEO for <?php echo e($cityName); ?>, <?php echo e($stateName); ?></button>
        </form>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<style>
.form-label { display:block; font-size:13px; font-weight:600; margin-bottom:4px; }
.form-input, .form-select, .form-textarea { width:100%; padding:8px 12px; background:var(--bg); border:1px solid var(--border); border-radius:var(--radius-sm); color:var(--text); font-size:14px; }
.form-textarea { resize:vertical; min-height:80px; }
</style>
<?php endif; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
