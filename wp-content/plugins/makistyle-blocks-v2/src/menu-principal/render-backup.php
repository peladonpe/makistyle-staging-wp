<?php
$menu_items = isset($attributes['menuItems']) ? $attributes['menuItems'] : [];

// Filtrar items que tienen texto
$valid_menu_items = array_filter($menu_items, function ($item) {
    return ! empty($item['text']) && trim($item['text']) !== '';
});
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
    <!-- menu -->
    <nav class="navbar navbar-principal" id="navbar-principal" data-bs-theme="dark">
        <div class="navbar__zona-superior">
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasMenu"
                aria-controls="offcanvasMenu"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div
            class="collapse navbar-collapse show navbar__contenido-colapsable"
            id="navbar__contenido-colapsable"></div>
    </nav>
    <!-- fin menu -->
    <!-- offcanvas -->
    <div
        class="offcanvas offcanvas-start"
        tabindex="-1"
        id="offcanvasMenu"
        aria-labelledby="offcanvasMenuLabel"
        data-bs-theme="dark">
        <div class="offcanvas-header" data-bs-theme="dark">
            <div class="nav-item offcanvas__logo" data-bs-dismiss="offcanvas">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="nav-link">
                    <?php
                    $custom_logo_id  = get_theme_mod('custom_logo');
                    $custom_logo_url = $custom_logo_id ? wp_get_attachment_image_url($custom_logo_id, 'full') : '';
                    $logo_style      = $custom_logo_url ? '' : 'visibility: hidden;';
                    ?>
                    <img
                        class="offcanvas-logo__img"
                        src="<?php echo esc_url($custom_logo_url); ?>"
                        alt="icono logo"
                        <?php if ($logo_style) : ?>style="<?php echo esc_attr($logo_style); ?>" <?php endif; ?> />
                </a>
            </div>
            <button
                type="button"
                class="btn-close offcanvas-header__btn-close"
                id="offcanvas-header__btn-close"
                data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <nav class="navbar-offcanvas">
                <ul id="menu-menu-general" class="navbar-nav offcanvas-body__ul">
                    <?php foreach ($valid_menu_items as $item) : ?>
                        <li class="menu-item nav-item offcanvas-body__li" data-bs-dismiss="offcanvas">
                            <a href="<?php echo esc_url($item['url'] ?? ''); ?>" class="nav-link">
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