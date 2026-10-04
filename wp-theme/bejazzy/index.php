<?php
if (!defined('ABSPATH')) {
    exit;
}
get_header();
?>
<main class="about-page">
    <div class="section-heading">
        <div>
            <h1><?php echo esc_html(get_bloginfo('name')); ?></h1>
        </div>
    </div>
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <section class="about-block">
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <?php the_excerpt(); ?>
            </section>
        <?php endwhile; ?>
    <?php endif; ?>
</main>
<?php bj_footer(); ?>
<?php get_footer(); ?>
