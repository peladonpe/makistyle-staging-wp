<?php
$menu_items = isset($attributes['menuItems']) ? $attributes['menuItems'] : [];

// Filtrar items que tienen texto
$valid_menu_items = array_filter($menu_items, function ($item) {
    return ! empty($item['text']) && trim($item['text']) !== '';
});

$custom_logo_id  = get_theme_mod('custom_logo');
$custom_logo_url = $custom_logo_id ? wp_get_attachment_image_url($custom_logo_id, 'full') : '';
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
    <!-- menu -->
    <nav class="mn-navbar" id="mn-navbar">
        <div class="mn-navbar__superior">
            <button
                class="mn-toggler"
                type="button"
                id="mn-toggler"
                aria-controls="mn-offcanvas"
                aria-expanded="false"
                aria-label="Abrir navegación">
                <span class="mn-toggler__bar"></span>
                <span class="mn-toggler__bar"></span>
                <span class="mn-toggler__bar"></span>
            </button>
        </div>
    </nav>
    <!-- fin menu -->

    <!-- overlay -->
    <div class="mn-overlay" id="mn-overlay" aria-hidden="true"></div>

    <!-- offcanvas -->
    <div
        class="mn-offcanvas"
        id="mn-offcanvas"
        role="dialog"
        aria-labelledby="mn-offcanvas-label"
        aria-hidden="true"
        tabindex="-1">
        <div class="mn-offcanvas__header">
            <div class="mn-offcanvas__logo">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="mn-offcanvas__logo-link">
                    <?php if ($custom_logo_url) : ?>
                        <img
                            class="mn-offcanvas__logo-img"
                            src="<?php echo esc_url($custom_logo_url); ?>"
                            alt="Logo" />
                    <?php endif; ?>
                </a>
            </div>
            <button
                type="button"
                class="mn-offcanvas__btn-close"
                id="mn-offcanvas-close"
                aria-label="Cerrar navegación">&#x2715;</button>
        </div>
        <div class="mn-offcanvas__body">
            <nav class="mn-offcanvas__nav">
                <ul class="mn-offcanvas__nav-list" id="mn-nav-list">
                    <?php foreach ($valid_menu_items as $item) : ?>
                        <li class="mn-offcanvas__nav-item">
                            <a href="<?php echo esc_url($item['url'] ?? ''); ?>" class="mn-offcanvas__nav-link">
                                <?php echo esc_html($item['text']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
    </div>
    <!-- fin offcanvas -->
</div>