<?php
/**
 * Template Name: O nas
 */
if (!defined('ABSPATH')) {
    exit;
}
get_header();

$bj_subsections = bj_about_subsections(bj_lang());
$bj_tab = sanitize_key(wp_unslash($_GET['tab'] ?? ''));
$bj_active = $bj_subsections[0] ?? [];
foreach ($bj_subsections as $bj_section) {
    if ($bj_section['id'] === $bj_tab) {
        $bj_active = $bj_section;
        break;
    }
}
$bj_active_id = $bj_active['id'] ?? 'band';
$bj_blocks = preg_split('/^\s*---\s*$/m', (string) ($bj_active['body'] ?? ''));
?>
<div class="join-footer">
    <main class="about-page">
        <div class="section-heading">
            <div>
                <p class="eyebrow"><?php echo esc_html(bj_t('aboutKicker')); ?></p>
                <h1><?php echo esc_html($bj_active['title'] ?? ''); ?></h1>
            </div>
            <a class="text-link" href="<?php echo esc_url(bj_home_url()); ?>"><?php echo esc_html(bj_t('joinPageBack')); ?> <span>↗</span></a>
        </div>

        <nav class="about-subnav" aria-label="<?php echo esc_attr(bj_lang() === 'en' ? 'About sections' : 'Podrazdelki O nas'); ?>">
            <?php foreach ($bj_subsections as $bj_section) :
                $bj_is_active = $bj_section['id'] === $bj_active_id;
                ?>
                <a class="about-tab<?php echo $bj_is_active ? ' active' : ''; ?>"
                   href="<?php echo esc_url(add_query_arg('tab', $bj_section['id'], get_permalink())); ?>"
                   <?php echo $bj_is_active ? 'aria-current="page"' : ''; ?>><?php echo esc_html($bj_section['navTitle']); ?></a>
            <?php endforeach; ?>
        </nav>

        <?php if (!empty($bj_active['image'])) : ?>
            <img class="about-photo" src="<?php echo bj_img($bj_active['image']); ?>" alt="<?php echo esc_attr($bj_active['imageAlt']); ?>" />
        <?php endif; ?>

        <?php if (!empty($bj_active['lead'])) : ?>
            <p class="about-lead"><?php echo esc_html($bj_active['lead']); ?></p>
        <?php endif; ?>

        <?php foreach ($bj_blocks as $bj_block) :
            if (trim($bj_block) === '') {
                continue;
            }
            ?>
            <section class="about-block">
                <?php echo bj_kses(trim($bj_block)); ?>
            </section>
        <?php endforeach; ?>

        <?php if (!empty($bj_active['closing'])) : ?>
            <p class="about-closing"><?php echo esc_html($bj_active['closing']); ?></p>
        <?php endif; ?>

        <?php if (!empty($bj_active['final'])) : ?>
            <p class="about-final"><?php echo esc_html($bj_active['final']); ?></p>
        <?php endif; ?>
    </main>
    <?php bj_footer(); ?>
</div>
<?php get_footer(); ?>
