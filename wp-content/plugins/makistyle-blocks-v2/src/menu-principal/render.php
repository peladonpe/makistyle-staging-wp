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

            <div class="mn-navbar__actions">
                <?php
                // Enlace a Mi cuenta / Login
                $myaccount_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/mi-cuenta/');
                if (is_user_logged_in()) {
                    $current_user = wp_get_current_user();
                    $user_initial = strtoupper(substr($current_user->display_name ? $current_user->display_name : $current_user->user_login, 0, 1));
                    ?>
                    <a href="<?php echo esc_url($myaccount_url); ?>" class="mn-navbar__action mn-navbar__action--user" aria-label="Mi cuenta">
                        <span class="mn-navbar__user-initial"><?php echo esc_html($user_initial); ?></span>
                    </a>
                    <?php
                } else {
                    ?>
                    <a href="<?php echo esc_url($myaccount_url); ?>" class="mn-navbar__action mn-navbar__action--login" aria-label="Iniciar sesión">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </a>
                    <?php
                }

                // Enlace al Carrito
                $cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/carrito/');
                $cart_count = (function_exists('WC') && WC()->cart) ? WC()->cart->get_cart_contents_count() : 0;
                ?>
                <a href="<?php echo esc_url($cart_url); ?>" class="mn-navbar__action mn-navbar__action--cart" aria-label="Carrito">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    <?php if ($cart_count > 0) : ?>
                        <span class="mn-navbar__cart-count"><?php echo esc_html($cart_count); ?></span>
                    <?php endif; ?>
                </a>
            </div>
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