<?php
/**
 * Template Name: Koncerti
 */
if (!defined('ABSPATH')) {
    exit;
}
get_header();

$bj_upcoming = bj_concerts(false);
$bj_past = bj_concerts(true);
?>
<div class="join-footer">
    <main class="events-page">
        <div class="section-heading">
            <div>
                <p class="eyebrow"><?php echo esc_html(bj_t('eventsKicker')); ?></p>
                <h2><?php echo esc_html(bj_t('eventsTitle')); ?></h2>
            </div>
            <a class="text-link" href="<?php echo esc_url(bj_home_url()); ?>"><?php echo esc_html(bj_t('joinPageBack')); ?> <span>↗</span></a>
        </div>
        <div class="event-list">
            <?php bj_event_list($bj_upcoming); ?>
        </div>
        <?php if ($bj_past->have_posts()) : $bj_past->rewind_posts(); ?>
            <div class="past-events">
                <p class="eyebrow"><?php echo esc_html(bj_t('pastEvents')); ?></p>
                <div class="event-list">
                    <?php bj_event_list($bj_past, true); ?>
                </div>
            </div>
        <?php endif; ?>
    </main>
    <?php bj_footer(); ?>
</div>
<?php get_footer(); ?>
