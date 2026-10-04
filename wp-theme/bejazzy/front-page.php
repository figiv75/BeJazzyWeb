<?php
if (!defined('ABSPATH')) {
    exit;
}
get_header();

$bj_upcoming = bj_concerts(false);
$bj_next = $bj_upcoming->posts[0] ?? null;
$bj_about_url = bj_page_url('page-about.php');
$bj_concerts_url = bj_page_url('page-concerts.php');
$bj_join_url = bj_page_url('page-join.php');
?>
<main id="home">
    <section class="hero">
        <div class="hero-copy">
            <p class="eyebrow"><?php echo esc_html(bj_t('heroKicker')); ?> <span class="line"></span></p>
            <h1><?php echo esc_html(bj_t('heroTitle')); ?></h1>
            <p class="hero-text"><?php echo esc_html(bj_t('heroText')); ?></p>
            <div class="hero-actions">
                <a class="button primary" href="#events"><?php echo esc_html(bj_t('heroCta')); ?> <span>↗</span></a>
                <a class="text-link" href="<?php echo esc_url($bj_about_url); ?>"><?php echo esc_html(bj_t('secondary')); ?> <span>↗</span></a>
            </div>
        </div>
        <div class="hero-art">
            <div class="sound-ring ring-one"></div>
            <div class="sound-ring ring-two"></div>
            <div class="sound-ring ring-three"></div>
            <div class="note-note">♪</div>
            <div class="vertical-label"><?php echo esc_html(bj_t('location')); ?></div>
        </div>
        <div class="hero-foot">
            <span>♪ · ♫</span>
            <span class="scroll-note"><?php echo esc_html(bj_t('scroll')); ?> <i>↓</i></span>
        </div>
    </section>

    <section class="next-event">
        <?php if ($bj_next) :
            $bj_iso = (string) get_post_meta($bj_next->ID, 'bj_date', true);
            $bj_date = $bj_iso ? date('j. n. Y', strtotime($bj_iso)) : '';
            $bj_place = (string) get_post_meta($bj_next->ID, 'bj_place', true);
            $bj_ticket = (string) get_post_meta($bj_next->ID, 'bj_ticket', true);
            $bj_free = (bool) get_post_meta($bj_next->ID, 'bj_free', true);
            ?>
            <div>
                <p class="eyebrow dark"><?php echo esc_html(bj_t('next')); ?></p>
                <h2><?php echo esc_html($bj_next->post_title); ?></h2>
                <p><?php echo esc_html($bj_date . ' · ' . $bj_place); ?></p>
            </div>
            <?php if ($bj_free) : ?>
                <span class="free-entry"><?php echo esc_html(bj_t('freeEntry')); ?></span>
            <?php elseif ($bj_ticket) : ?>
                <a class="button outline" href="<?php echo esc_url($bj_ticket); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html(bj_t('ticket')); ?> <span>↗</span></a>
            <?php endif; ?>
        <?php else : ?>
            <div>
                <p class="eyebrow dark"><?php echo esc_html(bj_t('next')); ?></p>
                <h2><?php echo esc_html(bj_t('empty')); ?></h2>
            </div>
        <?php endif; ?>
    </section>

    <section class="about section" id="about">
        <div class="section-aside">
            <p class="eyebrow"><?php echo esc_html(bj_t('aboutKicker')); ?></p>
            <span class="big-number">♪</span>
        </div>
        <div class="about-content">
            <h2><?php echo esc_html(bj_t('aboutTitle')); ?></h2>
            <p><?php echo esc_html(bj_t('aboutText')); ?></p>
            <a class="text-link" href="<?php echo esc_url($bj_about_url); ?>"><?php echo esc_html(bj_t('aboutCta')); ?> <span>↗</span></a>
        </div>
        <div class="about-visual">
            <div class="visual-card">
                <strong class="card-wordmark">Be<span class="logo-j">J</span>azzy</strong>
                <small>EST. 2014 · <?php echo esc_html(bj_t('country')); ?></small>
            </div>
        </div>
    </section>

    <section class="events section" id="events">
        <div class="section-heading">
            <div>
                <p class="eyebrow"><?php echo esc_html(bj_t('eventsKicker')); ?></p>
                <h2><?php echo esc_html(bj_t('eventsTitle')); ?></h2>
            </div>
            <a class="text-link" href="<?php echo esc_url($bj_concerts_url); ?>"><?php echo esc_html(bj_t('allEvents')); ?> <span>↗</span></a>
        </div>
        <div class="event-list">
            <?php bj_event_list($bj_upcoming); ?>
        </div>
    </section>

    <section class="media section" id="media">
        <div class="media-copy">
            <p class="eyebrow"><?php echo esc_html(bj_t('mediaKicker')); ?></p>
            <h2><?php echo esc_html(bj_t('mediaTitle')); ?></h2>
            <a class="text-link light" href="#contact"><?php echo esc_html(bj_t('mediaCta')); ?> <span>↗</span></a>
        </div>
        <div class="gallery">
            <div class="gallery-tile tile-a"><span>♩</span><b><?php echo bj_kses(bj_t('galleryOne')); ?></b></div>
            <div class="gallery-tile tile-b"><span>♫</span><b><?php echo bj_kses(bj_t('galleryTwo')); ?></b></div>
            <div class="gallery-tile tile-c"><span>♬</span><b><?php echo bj_kses(bj_t('galleryThree')); ?></b></div>
        </div>
    </section>

    <section class="join section" id="join">
        <div class="join-figures">
            <img src="<?php echo esc_url(bj_image('join_home')['url']); ?>" alt="" aria-hidden="true" />
        </div>
        <div class="join-content">
            <p class="eyebrow"><?php echo esc_html(bj_t('joinKicker')); ?></p>
            <h2><?php echo esc_html(bj_t('joinTitle')); ?></h2>
            <p><?php echo esc_html(bj_t('joinText')); ?></p>
            <a class="button primary" href="<?php echo esc_url($bj_join_url); ?>"><?php echo esc_html(bj_t('joinCta')); ?> <span>↗</span></a>
        </div>
    </section>
</main>

<div class="contact-footer">
    <section class="contact section" id="contact">
        <div>
            <p class="eyebrow"><?php echo esc_html(bj_t('contact')); ?></p>
            <h2><a href="mailto:<?php echo esc_attr(bj_email()); ?>"><?php echo esc_html(bj_email()); ?></a></h2>
        </div>
        <div class="contact-meta">
            <div class="socials">
                <a href="https://www.instagram.com/bejazzy_vocal_group/" target="_blank" rel="noopener noreferrer" aria-label="Instagram" title="Instagram"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5A5.5 5.5 0 1 1 6.5 13 5.5 5.5 0 0 1 12 7.5zm0 2A3.5 3.5 0 1 0 15.5 13 3.5 3.5 0 0 0 12 9.5zm5.25-3.25a1.25 1.25 0 1 1-1.25 1.25 1.25 1.25 0 0 1 1.25-1.25z"/></svg></a>
                <a href="https://www.youtube.com/@bejazzyvokalnaskupina9854" target="_blank" rel="noopener noreferrer" aria-label="YouTube" title="YouTube"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.6 7.2a2.9 2.9 0 0 0-2-2.1C17.8 4.7 12 4.7 12 4.7s-5.8 0-7.6.4A2.9 2.9 0 0 0 2.4 7.2 29.6 29.6 0 0 0 2 12a29.6 29.6 0 0 0 .4 4.8 2.9 2.9 0 0 0 2 2.1c1.8.4 7.6.4 7.6.4s5.8 0 7.6-.4a2.9 2.9 0 0 0 2-2.1A29.6 29.6 0 0 0 22 12a29.6 29.6 0 0 0-.4-4.8zM10 15.5v-7l6 3.5-6 3.5z"/></svg></a>
                <a href="https://www.facebook.com/vs.bejazzy" target="_blank" rel="noopener noreferrer" aria-label="Facebook" title="Facebook"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 22v-8h2.5l.5-3h-3V7.5c0-.9.4-1.5 1.5-1.5H16V3.1c-.5-.1-1.8-.2-3.1-.2-3 0-5.1 1.8-5.1 5.1V11H5.5v3h2.3v8h5.7z"/></svg></a>
                <a href="https://soundcloud.com/user-406223523" target="_blank" rel="noopener noreferrer" aria-label="SoundCloud" title="SoundCloud"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="1" y="15" width="1.6" height="4" rx="0.8"/><rect x="3.4" y="12" width="1.6" height="7" rx="0.8"/><rect x="5.8" y="9.5" width="1.6" height="9.5" rx="0.8"/><rect x="8.2" y="11" width="1.6" height="8" rx="0.8"/><circle cx="14" cy="13" r="5.2"/><circle cx="18.5" cy="14.5" r="3.6"/><rect x="9" y="14" width="12" height="5" rx="2.5"/></svg></a>
            </div>
        </div>
    </section>
    <?php bj_footer(); ?>
</div>
<?php get_footer(); ?>
