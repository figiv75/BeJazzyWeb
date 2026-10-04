<?php
if (!defined('ABSPATH')) {
    exit;
}
$bj_nav = bj_t('nav');
$bj_lang = bj_lang();
$bj_switch = bj_lang_switch();
$bj_nav_targets = [
    ['url' => bj_page_url('page-about.php')],
    ['url' => bj_home_url() . '#events'],
    ['url' => bj_home_url() . '#media'],
    ['url' => bj_home_url() . '#join'],
    ['url' => bj_home_url() . '#contact'],
];
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php ob_start('bj_wrap_brand'); ?>
<header class="topbar">
    <a class="wordmark" href="<?php echo esc_url(bj_home_url()); ?>"><img class="logo" src="<?php echo bj_img('images/bejazzy-logo-2023.png'); ?>" alt="BeJazzy" /></a>
    <nav id="site-nav" aria-label="<?php echo esc_attr($bj_lang === 'en' ? 'Main navigation' : 'Glavna navigacija'); ?>">
        <?php foreach ($bj_nav as $i => $label) : ?>
            <a href="<?php echo esc_url($bj_nav_targets[$i]['url'] ?? bj_home_url()); ?>"><?php echo esc_html($label); ?></a>
        <?php endforeach; ?>
    </nav>
    <div class="header-actions">
        <a class="lang" href="<?php echo esc_url($bj_switch['url']); ?>" hreflang="<?php echo esc_attr($bj_switch['label'] === 'EN' ? 'en' : 'sl'); ?>"><?php echo esc_html($bj_switch['label']); ?> <span>↗</span></a>
        <button class="menu" type="button" aria-label="Menu" aria-expanded="false" aria-controls="site-nav">☰</button>
    </div>
</header>
