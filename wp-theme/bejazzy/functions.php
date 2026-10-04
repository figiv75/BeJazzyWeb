<?php
if (!defined('ABSPATH')) {
    exit;
}

const BJ_VERSION = '1.0.0';

function bj_copy(): array {
    static $copy = null;
    if ($copy === null) {
        $copy = json_decode((string) file_get_contents(get_theme_file_path('inc/copy.json')), true) ?: [];
    }
    return $copy;
}

function bj_lang(): string {
    return function_exists('pll_current_language') ? (pll_current_language() ?: 'sl') : 'sl';
}

function bj_t(string $key) {
    $copy = bj_copy();
    $lang = bj_lang();
    return $copy[$lang][$key] ?? $copy['sl'][$key] ?? '';
}

function bj_img(string $path): string {
    return esc_url(get_theme_file_uri('assets/' . $path));
}

function bj_home_url(): string {
    return function_exists('pll_home_url') ? pll_home_url() : home_url('/');
}

function bj_page_url(string $template): string {
    $ids = get_posts([
        'post_type' => 'page',
        'post_status' => 'publish',
        'numberposts' => 1,
        'fields' => 'ids',
        'meta_key' => '_wp_page_template',
        'meta_value' => $template,
    ]);
    if (!$ids) {
        return bj_home_url();
    }
    $id = $ids[0];
    if (function_exists('pll_get_post')) {
        $translated = pll_get_post($id);
        if ($translated) {
            $id = $translated;
        }
    }
    return get_permalink($id);
}

function bj_brand_html(): string {
    return '<span class="brand">Be<span class="logo-j">J</span>azzy</span>';
}

function bj_wrap_brand(string $html): string {
    return preg_replace_callback(
        '/(<[^>]*>)|BeJazzy/u',
        static function (array $m): string {
            return !empty($m[1]) ? $m[1] : bj_brand_html();
        },
        $html
    );
}

function bj_kses(string $html): string {
    return wp_kses_post($html);
}

function bj_footer(): void {
    ?>
    <footer>
        <a class="wordmark" href="<?php echo esc_url(bj_home_url()); ?>"><img class="logo" src="<?php echo bj_img('images/bejazzy-logo-2023.png'); ?>" alt="BeJazzy" /></a>
        <p><?php echo esc_html(bj_t('footer')); ?></p>
        <div class="footer-funders">
            <span><?php echo esc_html(bj_t('funders')); ?></span>
            <a href="https://www.ljubljana.si/" target="_blank" rel="noopener noreferrer"><img src="<?php echo bj_img('images/logo_ljubljana.png'); ?>" alt="Mestna občina Ljubljana" /></a>
            <a href="https://www.jskd.si/" target="_blank" rel="noopener noreferrer"><img src="<?php echo bj_img('images/logo_jskd_new.png'); ?>" alt="JSKD" /></a>
        </div>
    </footer>
    <?php
}

function bj_event_list(WP_Query $query, bool $is_past = false): void {
    if (!$query->have_posts()) {
        echo '<p class="event-main">' . esc_html(bj_t('empty')) . '</p>';
        return;
    }
    $symbols = ['♩', '♫', '♬', '♭'];
    $i = 0;
    while ($query->have_posts()) {
        $query->the_post();
        $id = get_the_ID();
        $iso = (string) get_post_meta($id, 'bj_date', true);
        $date = $iso ? date('j. n. Y', strtotime($iso)) : '';
        $time = (string) get_post_meta($id, 'bj_time', true);
        $place = (string) get_post_meta($id, 'bj_place', true);
        $ticket = (string) get_post_meta($id, 'bj_ticket', true);
        $free = (bool) get_post_meta($id, 'bj_free', true);
        ?>
        <article class="event-row<?php echo $is_past ? ' past' : ''; ?>">
            <span class="event-index"><?php echo esc_html($symbols[$i] ?? '♪'); ?></span>
            <span class="event-date"><?php echo esc_html($date); ?></span>
            <div class="event-main">
                <h3><?php echo esc_html(get_the_title()); ?></h3>
                <p><?php echo esc_html($place); ?></p>
            </div>
            <span class="event-time"><?php echo esc_html($time); ?></span>
            <?php if ($is_past) : ?>
                <span></span>
            <?php elseif ($free) : ?>
                <span class="event-free"><?php echo esc_html(bj_t('freeEntry')); ?></span>
            <?php elseif ($ticket) : ?>
                <a class="arrow-link" href="<?php echo esc_url($ticket); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html(bj_t('ticket')); ?> <span>↗</span></a>
            <?php else : ?>
                <span></span>
            <?php endif; ?>
        </article>
        <?php
        $i++;
    }
    wp_reset_postdata();
}

function bj_concerts(bool $past): WP_Query {
    $past_clause = $past
        ? ['key' => 'bj_past', 'value' => '1']
        : ['relation' => 'OR', ['key' => 'bj_past', 'compare' => 'NOT EXISTS'], ['key' => 'bj_past', 'value' => '1', 'compare' => '!=']];
    return new WP_Query([
        'post_type' => 'concert',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'no_found_rows' => true,
        'meta_key' => 'bj_date',
        'orderby' => 'meta_value',
        'order' => $past ? 'DESC' : 'ASC',
        'meta_query' => [$past_clause],
    ]);
}

function bj_register_concerts(): void {
    register_post_type('concert', [
        'labels' => [
            'name' => 'Koncerti',
            'singular_name' => 'Koncert',
            'add_new_item' => 'Dodaj koncert',
            'edit_item' => 'Uredi koncert',
            'all_items' => 'Vsi koncerti',
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => ['title'],
    ]);
}
add_action('init', 'bj_register_concerts');

function bj_concert_fields(): array {
    return [
        'bj_date' => 'Datum (YYYY-MM-DD)',
        'bj_time' => 'Ura (HH:MM)',
        'bj_place' => 'Lokacija',
        'bj_ticket' => 'Povezava do vstopnic (http/https)',
    ];
}

function bj_add_concert_metabox(): void {
    add_meta_box('bj_concert_details', 'Podatki o koncertu', 'bj_render_concert_metabox', 'concert', 'normal', 'high');
}
add_action('add_meta_boxes', 'bj_add_concert_metabox');

function bj_render_concert_metabox(WP_Post $post): void {
    wp_nonce_field('bj_concert_save', 'bj_concert_nonce');
    foreach (bj_concert_fields() as $key => $label) {
        $value = (string) get_post_meta($post->ID, $key, true);
        printf(
            '<p><label for="%1$s"><strong>%2$s</strong></label><br /><input type="%3$s" id="%1$s" name="%1$s" value="%4$s" class="widefat" /></p>',
            esc_attr($key),
            esc_html($label),
            $key === 'bj_date' ? 'date' : ($key === 'bj_time' ? 'time' : 'text'),
            esc_attr($value)
        );
    }
    printf(
        '<p><label><input type="checkbox" name="bj_past" value="1" %s /> Pretekel koncert</label></p>',
        checked((string) get_post_meta($post->ID, 'bj_past', true), '1', false)
    );
    printf(
        '<p><label><input type="checkbox" name="bj_free" value="1" %s /> Vstop prost (namesto vstopnic)</label></p>',
        checked((string) get_post_meta($post->ID, 'bj_free', true), '1', false)
    );
}

function bj_save_concert(int $post_id): void {
    if (!isset($_POST['bj_concert_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['bj_concert_nonce'])), 'bj_concert_save')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $date = isset($_POST['bj_date']) ? sanitize_text_field(wp_unslash($_POST['bj_date'])) : '';
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $date = '';
    }
    $time = isset($_POST['bj_time']) ? sanitize_text_field(wp_unslash($_POST['bj_time'])) : '';
    if (!preg_match('/^\d{2}:\d{2}$/', $time)) {
        $time = '';
    }
    $ticket = isset($_POST['bj_ticket']) ? esc_url_raw(trim(wp_unslash($_POST['bj_ticket']))) : '';
    if (!preg_match('#^https?://#i', $ticket)) {
        $ticket = '';
    }

    update_post_meta($post_id, 'bj_date', $date);
    update_post_meta($post_id, 'bj_time', $time);
    update_post_meta($post_id, 'bj_place', isset($_POST['bj_place']) ? sanitize_text_field(wp_unslash($_POST['bj_place'])) : '');
    update_post_meta($post_id, 'bj_ticket', $ticket);
    update_post_meta($post_id, 'bj_past', isset($_POST['bj_past']) ? '1' : '0');
    update_post_meta($post_id, 'bj_free', isset($_POST['bj_free']) ? '1' : '0');
}
add_action('save_post_concert', 'bj_save_concert');

function bj_seed_concerts(): void {
    if (get_option('bj_seeded')) {
        return;
    }
    $seed = json_decode((string) file_get_contents(get_theme_file_path('inc/seed-events.json')), true) ?: [];
    foreach ($seed as $event) {
        if (!preg_match('/(\d+)\.\s*(\d+)\.\s*(\d{4})/', (string) $event['date'], $m)) {
            continue;
        }
        $post_id = wp_insert_post([
            'post_type' => 'concert',
            'post_status' => 'publish',
            'post_title' => $event['title'],
        ]);
        if (!$post_id || is_wp_error($post_id)) {
            continue;
        }
        update_post_meta($post_id, 'bj_date', sprintf('%04d-%02d-%02d', (int) $m[3], (int) $m[2], (int) $m[1]));
        update_post_meta($post_id, 'bj_time', (string) ($event['time'] ?? ''));
        update_post_meta($post_id, 'bj_place', (string) ($event['place'] ?? ''));
        update_post_meta($post_id, 'bj_ticket', '');
        update_post_meta($post_id, 'bj_past', !empty($event['past']) ? '1' : '0');
        update_post_meta($post_id, 'bj_free', !empty($event['freeEntry']) ? '1' : '0');
    }
    update_option('bj_seeded', 1);
}
add_action('init', 'bj_seed_concerts', 20);

function bj_setup(): void {
    add_theme_support('title-tag');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style']);
}
add_action('after_setup_theme', 'bj_setup');

function bj_enqueue(): void {
    wp_enqueue_style('bj-fonts', 'https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap', [], null);
    wp_enqueue_style('bj-styles', get_theme_file_uri('assets/styles.css'), ['bj-fonts'], BJ_VERSION);
    wp_enqueue_style('bj-logo', get_theme_file_uri('assets/logo-overrides.css'), ['bj-styles'], BJ_VERSION);
    wp_enqueue_style('bj-join', get_theme_file_uri('assets/join-page.css'), ['bj-logo'], BJ_VERSION);
    wp_enqueue_style('bj-brand-wave', get_theme_file_uri('assets/brand-wave-step1.css'), ['bj-join'], BJ_VERSION);
    wp_enqueue_style('bj-theme', get_theme_file_uri('assets/theme.css'), ['bj-brand-wave'], BJ_VERSION);
    wp_enqueue_script('bj-site', get_theme_file_uri('assets/site.js'), [], BJ_VERSION, true);
}
add_action('wp_enqueue_scripts', 'bj_enqueue');

function bj_lang_switch(): array {
    if (function_exists('pll_the_languages')) {
        $langs = pll_the_languages(['raw' => 1, 'hide_if_no_translation' => 0]) ?: [];
        foreach ($langs as $lang) {
            if (empty($lang['current_lang'])) {
                return ['url' => $lang['url'], 'label' => strtoupper($lang['slug'])];
            }
        }
    }
    return ['url' => bj_home_url(), 'label' => 'EN'];
}

function bj_about_tab_url(string $id): string {
    return esc_url(add_query_arg('tab', $id, get_permalink()));
}

const BJ_DEFAULT_EMAIL = 'vs.bejazzy@gmail.com';

function bj_email(): string {
    $email = (string) get_option('bj_email', BJ_DEFAULT_EMAIL);
    return is_email($email) ? $email : BJ_DEFAULT_EMAIL;
}

function bj_default_about_body(array $subsection): string {
    $blocks = [];
    foreach (($subsection['sections'] ?? []) as $section) {
        $block = !empty($section['heading']) ? '<h2>' . $section['heading'] . '</h2>' : '';
        $blocks[] = $block . ($section['body'] ?? '');
    }
    if (!empty($subsection['todayHeading'])) {
        $blocks[] = '<h3>' . $subsection['todayHeading'] . '</h3>' . ($subsection['todayBody'] ?? '');
    }
    return implode("\n---\n", $blocks);
}

function bj_default_about_subsection(string $lang, string $id): array {
    foreach ((bj_copy()[$lang]['aboutPage']['subsections'] ?? []) as $sub) {
        if (($sub['id'] ?? '') === $id) {
            return [
                'navTitle' => $sub['navTitle'] ?? '',
                'title' => $sub['title'] ?? '',
                'lead' => $sub['lead'] ?? '',
                'body' => bj_default_about_body($sub),
                'closing' => $sub['closing'] ?? '',
                'final' => $sub['final'] ?? '',
            ];
        }
    }
    return [];
}

function bj_about_subsection_ids(): array {
    return ['band', 'leader', 'org'];
}

function bj_about_subsections(string $lang): array {
    $stored = get_option('bj_texts', []);
    $result = [];
    foreach ((bj_copy()[$lang]['aboutPage']['subsections'] ?? []) as $sub) {
        $id = $sub['id'] ?? '';
        $defaults = bj_default_about_subsection($lang, $id);
        $override = $stored[$lang][$id] ?? [];
        $result[] = [
            'id' => $id,
            'navTitle' => $override['navTitle'] ?? $defaults['navTitle'],
            'title' => $override['title'] ?? $defaults['title'],
            'lead' => $override['lead'] ?? $defaults['lead'],
            'body' => $override['body'] ?? $defaults['body'],
            'closing' => $override['closing'] ?? $defaults['closing'],
            'final' => $override['final'] ?? $defaults['final'],
            'image' => $sub['image'] ?? '',
            'imageAlt' => $sub['imageAlt'] ?? '',
        ];
    }
    return $result;
}

function bj_sanitize_text_fields(array $input): array {
    $out = [];
    foreach (['navTitle', 'title', 'lead', 'closing', 'final'] as $field) {
        $out[$field] = isset($input[$field]) ? sanitize_text_field($input[$field]) : '';
    }
    $out['body'] = isset($input['body']) ? wp_kses_post($input['body']) : '';
    return $out;
}

function bj_add_settings_page(): void {
    add_options_page('BeJazzy vsebina', 'BeJazzy', 'manage_options', 'bejazzy-settings', 'bj_render_settings_page');
}
add_action('admin_menu', 'bj_add_settings_page');

function bj_render_settings_page(): void {
    if (!current_user_can('manage_options')) {
        return;
    }
    $langs = ['sl' => 'Slovenščina', 'en' => 'English'];
    ?>
    <div class="wrap">
        <h1>BeJazzy vsebina</h1>
        <?php if (!empty($_GET['updated'])) : ?>
            <div class="notice notice-success is-dismissible"><p>Shranjeno.</p></div>
        <?php endif; ?>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="bj_save_settings" />
            <?php wp_nonce_field('bj_save_settings', 'bj_settings_nonce'); ?>

            <h2>Kontakt</h2>
            <table class="form-table">
                <tr>
                    <th><label for="bj_email">E-pošta</label></th>
                    <td>
                        <input type="email" id="bj_email" name="bj_email" value="<?php echo esc_attr(bj_email()); ?>" class="regular-text" />
                        <p class="description">Prikazuje se v kontaktu na naslovnici in v povezavah mailto na strani "Pridruži se".</p>
                    </td>
                </tr>
            </table>

            <?php foreach ($langs as $lang => $lang_label) : ?>
                <h2>O nas — <?php echo esc_html($lang_label); ?></h2>
                <?php foreach (bj_about_subsections($lang) as $sub) :
                    $prefix = 'texts[' . $lang . '][' . $sub['id'] . ']';
                    ?>
                    <details<?php echo $sub['id'] === 'band' ? ' open' : ''; ?> style="margin-bottom:16px;background:#fff;padding:12px 16px;border:1px solid #ccd0d4;">
                        <summary><strong><?php echo esc_html($sub['navTitle']); ?></strong> (<?php echo esc_html($sub['id']); ?>)</summary>
                        <table class="form-table">
                            <tr><th>Naziv zavihka</th><td><input type="text" name="<?php echo esc_attr($prefix); ?>[navTitle]" value="<?php echo esc_attr($sub['navTitle']); ?>" class="regular-text" /></td></tr>
                            <tr><th>Naslov</th><td><input type="text" name="<?php echo esc_attr($prefix); ?>[title]" value="<?php echo esc_attr($sub['title']); ?>" class="large-text" /></td></tr>
                            <tr><th>Uvod</th><td><textarea name="<?php echo esc_attr($prefix); ?>[lead]" rows="2" class="large-text"><?php echo esc_textarea($sub['lead']); ?></textarea></td></tr>
                            <tr>
                                <th>Vsebina (HTML)</th>
                                <td>
                                    <textarea name="<?php echo esc_attr($prefix); ?>[body]" rows="18" class="large-text code"><?php echo esc_textarea($sub['body']); ?></textarea>
                                    <p class="description">Bloki ločite z vrstico <code>---</code>. Naslov bloka: <code>&lt;h2&gt;Naslov&lt;/h2&gt;</code>, podnaslov: <code>&lt;h3&gt;</code>, odstavki: <code>&lt;p&gt;</code>.</p>
                                </td>
                            </tr>
                            <tr><th>Zaključek</th><td><textarea name="<?php echo esc_attr($prefix); ?>[closing]" rows="2" class="large-text"><?php echo esc_textarea($sub['closing']); ?></textarea></td></tr>
                            <tr><th>Zadnja vrstica</th><td><input type="text" name="<?php echo esc_attr($prefix); ?>[final]" value="<?php echo esc_attr($sub['final']); ?>" class="large-text" /></td></tr>
                        </table>
                    </details>
                <?php endforeach; ?>
            <?php endforeach; ?>

            <?php submit_button('Shrani'); ?>
        </form>
    </div>
    <?php
}

function bj_save_settings(): void {
    if (!current_user_can('manage_options')) {
        wp_die('Nimate dovoljenja.');
    }
    check_admin_referer('bj_save_settings', 'bj_settings_nonce');

    $email = isset($_POST['bj_email']) ? sanitize_email(wp_unslash($_POST['bj_email'])) : '';
    update_option('bj_email', is_email($email) ? $email : BJ_DEFAULT_EMAIL);

    $texts = [];
    $input = isset($_POST['texts']) && is_array($_POST['texts']) ? wp_unslash($_POST['texts']) : [];
    foreach (['sl', 'en'] as $lang) {
        foreach (bj_about_subsection_ids() as $id) {
            if (isset($input[$lang][$id]) && is_array($input[$lang][$id])) {
                $texts[$lang][$id] = bj_sanitize_text_fields($input[$lang][$id]);
            }
        }
    }
    update_option('bj_texts', $texts);

    wp_safe_redirect(add_query_arg(['page' => 'bejazzy-settings', 'updated' => 1], admin_url('options-general.php')));
    exit;
}
add_action('admin_post_bj_save_settings', 'bj_save_settings');
