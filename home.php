<?php
$welcome_content = is_file(base_app.'pages/welcome.html') ? file_get_contents(base_app.'pages/welcome.html') : '';
$welcome_images = [validate_image($_settings->info('cover'))];
$welcome_slider_dir = base_app.'uploads/welcome_slider/';
$welcome_slider_url = base_url.'uploads/welcome_slider/';

if(is_dir($welcome_slider_dir)){
    $slider_files = array_values(array_filter(scandir($welcome_slider_dir), function($file) use ($welcome_slider_dir){
        return preg_match('/\.(jpe?g|png|webp)$/i', $file) && is_file($welcome_slider_dir.$file);
    }));
    sort($slider_files, SORT_NATURAL);
    if(!empty($slider_files)){
        $welcome_images = [];
        foreach(array_slice($slider_files, 0, 5) as $file){
            $welcome_images[] = $welcome_slider_url.rawurlencode($file).'?v='.filemtime($welcome_slider_dir.$file);
        }
    }
}

if(count($welcome_images) === 1 && preg_match_all('/<img\b[^>]*src=["\']([^"\']+)["\'][^>]*>/i', $welcome_content, $matches, PREG_SET_ORDER)){
    $welcome_images = [];
    foreach(array_slice($matches, 0, 5) as $match){
        $img_src = trim($match[1]);
        if(!preg_match('/^(https?:)?\/\//i', $img_src) && !preg_match('/^data:/i', $img_src)){
            $img_src = base_url.ltrim($img_src, '/');
        }
        $welcome_images[] = $img_src;
        $welcome_content = str_replace($match[0], '', $welcome_content);
    }
}
$welcome_content = preg_replace('/<(p|h[1-6])>\s*(?:&nbsp;|\xC2\xA0|\s)*<\/\1>/i', '', $welcome_content);
$welcome_heading = 'Welcome to Uttara University Lost & Found';
$welcome_intro = 'This platform helps students, faculty members, and staff recover lost belongings quickly and securely within the university community.';
$welcome_cards = '';

if(class_exists('DOMDocument') && trim($welcome_content) !== ''){
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="utf-8" ?><div>'.$welcome_content.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();
    $xpath = new DOMXPath($dom);
    $heading_node = $xpath->query('//h1|//h2|//h3')->item(0);
    $intro_node = $xpath->query('//p[normalize-space()]')->item(0);
    if($heading_node){
        $welcome_heading = trim($heading_node->textContent);
        $heading_node->parentNode->removeChild($heading_node);
    }
    if($intro_node){
        $welcome_intro = trim($intro_node->textContent);
        $intro_node->parentNode->removeChild($intro_node);
    }
    $remaining = '';
    foreach($dom->documentElement->childNodes as $child){
        $remaining .= $dom->saveHTML($child);
    }
    $welcome_cards = trim($remaining);
}
$display_heading = trim(preg_replace('/^Uttara University\s+Lost\s*&\s*Found\s*[–-]\s*/i', '', $welcome_heading));
if($display_heading === ''){
    $display_heading = 'User Guidelines';
}
$display_intro = trim($welcome_intro);
if(preg_match('/^This platform is created to help students, faculty members, and staff recover lost belongings quickly and securely within the university community\.?$/i', $display_intro)){
    $display_intro = 'Helpful practices for reporting, finding, and recovering belongings quickly and securely within the university community.';
}
$highlighted_heading = preg_replace('/(Guidelines)$/i', '<span>$1</span>', htmlspecialchars($display_heading));
?>
<section class="welcome-showcase">
    <div class="welcome-showcase__content page-content">
        <div class="welcome-showcase__label">
            <span>Uttara University</span>
            <strong>Lost &amp; Found</strong>
        </div>
        <div class="welcome-showcase__text">
            <h2><?= $highlighted_heading ?></h2>
            <p><?= htmlspecialchars($display_intro) ?></p>
        </div>
        <div class="welcome-showcase__actions">
            <a href="<?= base_url.'?page=items' ?>" class="btn btn-primary">
                <i class="bi bi-search"></i>
                Browse Items
            </a>
            <a href="<?= base_url.'?page=found' ?>" class="btn btn-outline-primary">
                <i class="bi bi-plus-circle"></i>
                Post Lost/Found
            </a>
        </div>
        <?php if(!empty($welcome_cards)): ?>
        <div class="welcome-showcase__cards">
            <?= $welcome_cards ?>
        </div>
        <?php endif; ?>
    </div>
    <div class="welcome-showcase__media">
        <div id="welcomeImageSlider" class="carousel slide welcome-showcase__slider" data-bs-ride="carousel" data-bs-interval="4000">
            <div class="carousel-inner">
                <?php foreach($welcome_images as $i => $image): ?>
                <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                    <img src="<?= $image ?>" alt="Uttara University campus slide <?= $i + 1 ?>">
                </div>
                <?php endforeach; ?>
            </div>
            <?php if(count($welcome_images) > 1): ?>
            <div class="carousel-indicators">
                <?php foreach($welcome_images as $i => $image): ?>
                <button type="button" data-bs-target="#welcomeImageSlider" data-bs-slide-to="<?= $i ?>" class="<?= $i === 0 ? 'active' : '' ?>" aria-current="<?= $i === 0 ? 'true' : 'false' ?>" aria-label="Slide <?= $i + 1 ?>"></button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <div class="welcome-showcase__panel">
            <span>Campus Recovery Desk</span>
            <strong>Report clearly. Recover faster.</strong>
        </div>
    </div>
</section>
