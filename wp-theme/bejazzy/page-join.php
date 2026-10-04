<?php
/**
 * Template Name: Pridruži se
 */
if (!defined('ABSPATH')) {
    exit;
}
get_header();

$bj_image = bj_lang() === 'en' ? 'images/Avdicija-eng-final.png' : 'images/Avdicija-slo-new.png';
?>
<div class="join-footer">
    <main class="join-page">
        <div class="join-page-copy">
            <p class="eyebrow"><?php echo esc_html(bj_t('joinPageKicker')); ?> <span class="line"></span></p>
            <h1><?php echo esc_html(bj_t('joinPageTitle')); ?></h1>
            <p><?php echo esc_html(bj_t('joinPageText')); ?></p>
            <a class="button primary" href="mailto:<?php echo esc_attr(bj_email()); ?>"><?php echo esc_html(bj_t('joinPageContact')); ?> <span>↗</span></a>
            <a class="text-link" href="<?php echo esc_url(bj_home_url()); ?>"><?php echo esc_html(bj_t('joinPageBack')); ?> <span>↗</span></a>
        </div>
        <div class="join-page-image">
            <img src="<?php echo bj_img($bj_image); ?>" alt="<?php echo esc_attr(bj_t('joinPageTitle')); ?>" />
        </div>
    </main>
    <?php bj_footer(); ?>
</div>
<?php get_footer(); ?>
