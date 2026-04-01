<?php
/**
 * Plugin Name: Cafe Moxie Site Kit
 * Description: Enterprise-ready brand and storefront kit for Twenty Twenty-Five with Cafe Moxie design tokens, reusable patterns, and Secure Custom Fields powered Edge Tool templates.
 * Version: 2.0.0
 * Author: Fabled Sky Research
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Cafe_Moxie_Site_Kit {
	const OPTION  = 'cafe_moxie_site_kit_settings';
	const VERSION = '2.0.0';

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
		add_action( 'init', array( __CLASS__, 'register_patterns' ) );
		add_action( 'init', array( __CLASS__, 'register_shortcodes' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
		add_filter( 'template_include', array( __CLASS__, 'template_include' ), 99 );
		add_filter( 'body_class', array( __CLASS__, 'body_classes' ) );
		add_action( 'admin_post_cafe_moxie_create_starter_pages', array( __CLASS__, 'create_starter_pages' ) );

		if ( false === get_option( self::OPTION ) ) {
			update_option( self::OPTION, self::defaults() );
		}
	}

	public static function defaults() {
		return array(
			'load_google_fonts'      => 1,
			'enable_motion'          => 1,
			'show_archive_filters'   => 1,
			'featured_tools_count'   => 3,
			'archive_items_per_page' => 9,

			'logo_width'       => 320,
			'header_height'    => 82,
			'section_max_width'=> 1220,
			'hero_min_height'  => 640,
			'card_image_ratio' => '16:10',
			'glow_intensity'   => 1.0,
			'border_radius'    => 22,
			'button_scale'     => 1.0,

			'color_ink'            => '#05070D',
			'color_midnight'       => '#0A1020',
			'color_oil'            => '#121A2B',
			'color_gunmetal'       => '#2A3140',
			'color_cyan'           => '#35D6FF',
			'color_teal'           => '#1FB8B2',
			'color_arcade'         => '#5AA9FF',
			'color_magenta'        => '#FF4FA3',
			'color_amber'          => '#F6B35C',
			'color_gold'           => '#D9A441',
			'color_cream'          => '#F5E6C8',
			'color_rust'           => '#8E5A3C',
			'color_signal_red'     => '#E64848',
			'color_warning_yellow' => '#F2C94C',

			'display_logo_image' => '',
			'home_hero_image'    => '',
			'home_story_image'   => '',
			'about_story_image'  => '',

			'site_kicker'          => 'Cafe Moxie',
			'footer_copy'          => 'Tools for people who actually do the work.',
			'home_primary_cta'     => 'Browse the Counter',
			'home_primary_url'     => '/edge-tools/',
			'home_secondary_cta'   => 'See What Runs Local',
			'home_secondary_url'   => '/about/',
			'about_primary_cta'    => 'See the Tool Counter',
			'about_primary_url'    => '/edge-tools/',
		);
	}

	public static function settings() {
		return wp_parse_args( get_option( self::OPTION, array() ), self::defaults() );
	}

	public static function admin_menu() {
		add_menu_page(
			'Cafe Moxie',
			'Cafe Moxie',
			'manage_options',
			'cafe-moxie-site-kit',
			array( __CLASS__, 'settings_page' ),
			'dashicons-art',
			61
		);
	}

	public static function register_settings() {
		register_setting( 'cafe_moxie_site_kit_group', self::OPTION, array( __CLASS__, 'sanitize_settings' ) );
	}

	public static function sanitize_settings( $input ) {
		$d = self::defaults();
		$bool_keys = array(
			'load_google_fonts',
			'enable_motion',
			'show_archive_filters',
		);
		$color_keys = array(
			'color_ink',
			'color_midnight',
			'color_oil',
			'color_gunmetal',
			'color_cyan',
			'color_teal',
			'color_arcade',
			'color_magenta',
			'color_amber',
			'color_gold',
			'color_cream',
			'color_rust',
			'color_signal_red',
			'color_warning_yellow',
		);
		$out = array();

		foreach ( $bool_keys as $key ) {
			$out[ $key ] = empty( $input[ $key ] ) ? 0 : 1;
		}

		$out['featured_tools_count']   = max( 1, min( 12, intval( $input['featured_tools_count'] ?? $d['featured_tools_count'] ) ) );
		$out['archive_items_per_page'] = max( 3, min( 24, intval( $input['archive_items_per_page'] ?? $d['archive_items_per_page'] ) ) );
		$out['logo_width']             = max( 120, min( 640, intval( $input['logo_width'] ?? $d['logo_width'] ) ) );
		$out['header_height']          = max( 60, min( 160, intval( $input['header_height'] ?? $d['header_height'] ) ) );
		$out['section_max_width']      = max( 960, min( 1600, intval( $input['section_max_width'] ?? $d['section_max_width'] ) ) );
		$out['hero_min_height']        = max( 420, min( 980, intval( $input['hero_min_height'] ?? $d['hero_min_height'] ) ) );
		$out['glow_intensity']         = max( 0.2, min( 2.5, floatval( $input['glow_intensity'] ?? $d['glow_intensity'] ) ) );
		$out['border_radius']          = max( 8, min( 40, intval( $input['border_radius'] ?? $d['border_radius'] ) ) );
		$out['button_scale']           = max( 0.8, min( 1.4, floatval( $input['button_scale'] ?? $d['button_scale'] ) ) );
		$out['card_image_ratio']       = sanitize_text_field( $input['card_image_ratio'] ?? $d['card_image_ratio'] );

		foreach ( $color_keys as $key ) {
			$sanitized = sanitize_hex_color( $input[ $key ] ?? '' );
			$out[ $key ] = $sanitized ? $sanitized : $d[ $key ];
		}

		$out['display_logo_image'] = self::sanitize_url_or_path( $input['display_logo_image'] ?? '' );
		$out['home_hero_image']    = self::sanitize_url_or_path( $input['home_hero_image'] ?? '' );
		$out['home_story_image']   = self::sanitize_url_or_path( $input['home_story_image'] ?? '' );
		$out['about_story_image']  = self::sanitize_url_or_path( $input['about_story_image'] ?? '' );

		$out['site_kicker']        = sanitize_text_field( $input['site_kicker'] ?? $d['site_kicker'] );
		$out['footer_copy']        = sanitize_text_field( $input['footer_copy'] ?? $d['footer_copy'] );
		$out['home_primary_cta']   = sanitize_text_field( $input['home_primary_cta'] ?? $d['home_primary_cta'] );
		$out['home_primary_url']   = self::sanitize_url_or_path( $input['home_primary_url'] ?? $d['home_primary_url'] );
		$out['home_secondary_cta'] = sanitize_text_field( $input['home_secondary_cta'] ?? $d['home_secondary_cta'] );
		$out['home_secondary_url'] = self::sanitize_url_or_path( $input['home_secondary_url'] ?? $d['home_secondary_url'] );
		$out['about_primary_cta']  = sanitize_text_field( $input['about_primary_cta'] ?? $d['about_primary_cta'] );
		$out['about_primary_url']  = self::sanitize_url_or_path( $input['about_primary_url'] ?? $d['about_primary_url'] );

		return $out;
	}

	private static function sanitize_url_or_path( $value ) {
		$value = trim( (string) $value );
		if ( '' === $value ) {
			return '';
		}
		if ( 0 === strpos( $value, '/' ) ) {
			return '/' . ltrim( $value, '/' );
		}
		if ( preg_match( '#^(https?:)?//#i', $value ) || 0 === strpos( $value, 'mailto:' ) || 0 === strpos( $value, 'tel:' ) ) {
			return esc_url_raw( $value );
		}
		return sanitize_text_field( $value );
	}

	public static function resolve_url( $value ) {
		$value = trim( (string) $value );
		if ( '' === $value ) {
			return '';
		}
		if ( preg_match( '#^(https?:)?//#i', $value ) || 0 === strpos( $value, 'mailto:' ) || 0 === strpos( $value, 'tel:' ) ) {
			return esc_url( $value );
		}
		if ( 0 === strpos( $value, '/' ) ) {
			return esc_url( home_url( $value ) );
		}
		return esc_url( home_url( '/' . ltrim( $value, '/' ) ) );
	}

	private static function text_row( $key, $label, $type = 'text', $hint = '' ) {
		$s = self::settings();
		echo '<tr><th scope="row"><label for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label></th><td>';
		echo '<input class="regular-text" type="' . esc_attr( $type ) . '" id="' . esc_attr( $key ) . '" name="' . esc_attr( self::OPTION ) . '[' . esc_attr( $key ) . ']" value="' . esc_attr( $s[ $key ] ?? '' ) . '">';
		if ( $hint ) {
			echo '<p class="description">' . esc_html( $hint ) . '</p>';
		}
		echo '</td></tr>';
	}

	private static function checkbox_row( $key, $label, $hint = '' ) {
		$s = self::settings();
		echo '<tr><th scope="row">' . esc_html( $label ) . '</th><td>';
		echo '<label><input type="checkbox" name="' . esc_attr( self::OPTION ) . '[' . esc_attr( $key ) . ']" value="1" ' . checked( ! empty( $s[ $key ] ), true, false ) . '> ' . esc_html__( 'Enabled', 'cafe-moxie-site-kit' ) . '</label>';
		if ( $hint ) {
			echo '<p class="description">' . esc_html( $hint ) . '</p>';
		}
		echo '</td></tr>';
	}

	private static function color_row( $key, $label ) {
		$s = self::settings();
		echo '<tr><th scope="row"><label for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label></th><td>';
		echo '<input type="color" id="' . esc_attr( $key ) . '" name="' . esc_attr( self::OPTION ) . '[' . esc_attr( $key ) . ']" value="' . esc_attr( $s[ $key ] ?? '' ) . '">';
		echo '<code style="margin-left:10px;">' . esc_html( strtoupper( $s[ $key ] ?? '' ) ) . '</code>';
		echo '</td></tr>';
	}

	public static function settings_page() {
		$url = wp_nonce_url( admin_url( 'admin-post.php?action=cafe_moxie_create_starter_pages' ), 'cafe_moxie_create_starter_pages' );
		?>
		<div class="wrap">
			<h1>Cafe Moxie Site Kit</h1>
			<p>Enterprise-ready storefront layer for Twenty Twenty-Five. The design system stays lean, the templates stay editable, and the Edge Tool storefront reads directly from Secure Custom Fields.</p>
			<p><a class="button button-primary" href="<?php echo esc_url( $url ); ?>">Create / Refresh Starter Pages</a></p>
			<form method="post" action="options.php">
				<?php settings_fields( 'cafe_moxie_site_kit_group' ); ?>
				<h2>Storefront</h2>
				<table class="form-table" role="presentation">
					<?php
					self::text_row( 'site_kicker', 'Brand kicker' );
					self::text_row( 'featured_tools_count', 'Featured tools on home', 'number' );
					self::text_row( 'archive_items_per_page', 'Archive items per page', 'number' );
					self::checkbox_row( 'show_archive_filters', 'Show archive filters', 'Adds a compact filter bar to the Edge Tool archive.' );
					?>
				</table>

				<h2>Layout + Motion</h2>
				<table class="form-table" role="presentation">
					<?php
					self::checkbox_row( 'load_google_fonts', 'Load Google Fonts', 'Turn this off if you plan to self-host Chathura and IBM Plex Sans.' );
					self::checkbox_row( 'enable_motion', 'Enable motion accents', 'Subtle glow, sign flicker, and tactile hover states.' );
					self::text_row( 'logo_width', 'Brand mark width (px)', 'number' );
					self::text_row( 'header_height', 'Header minimum height (px)', 'number' );
					self::text_row( 'section_max_width', 'Section max width (px)', 'number' );
					self::text_row( 'hero_min_height', 'Hero min height (px)', 'number' );
					self::text_row( 'card_image_ratio', 'Card image ratio', 'text', 'Example 16:10 or 4:3' );
					self::text_row( 'glow_intensity', 'Glow intensity', 'number' );
					self::text_row( 'border_radius', 'Corner radius (px)', 'number' );
					self::text_row( 'button_scale', 'Button scale', 'number' );
					?>
				</table>

				<h2>Brand Media</h2>
				<table class="form-table" role="presentation">
					<?php
					self::text_row( 'display_logo_image', 'Brand mark image URL', 'url', 'Used in starter pages and tool templates. Set Site Logo separately in WordPress if you want it in the global header.' );
					self::text_row( 'home_hero_image', 'Home hero image URL', 'url' );
					self::text_row( 'home_story_image', 'Home story image URL', 'url' );
					self::text_row( 'about_story_image', 'About story image URL', 'url' );
					?>
				</table>

				<h2>Calls to Action</h2>
				<table class="form-table" role="presentation">
					<?php
					self::text_row( 'home_primary_cta', 'Home primary CTA label' );
					self::text_row( 'home_primary_url', 'Home primary CTA URL', 'text' );
					self::text_row( 'home_secondary_cta', 'Home secondary CTA label' );
					self::text_row( 'home_secondary_url', 'Home secondary CTA URL', 'text' );
					self::text_row( 'about_primary_cta', 'About CTA label' );
					self::text_row( 'about_primary_url', 'About CTA URL', 'text' );
					self::text_row( 'footer_copy', 'Footer copy' );
					?>
				</table>

				<h2>Color Tokens</h2>
				<table class="form-table" role="presentation">
					<?php
					self::color_row( 'color_ink', 'Ink' );
					self::color_row( 'color_midnight', 'Midnight' );
					self::color_row( 'color_oil', 'Oil' );
					self::color_row( 'color_gunmetal', 'Gunmetal' );
					self::color_row( 'color_cyan', 'Cyan' );
					self::color_row( 'color_teal', 'Teal' );
					self::color_row( 'color_arcade', 'Arcade' );
					self::color_row( 'color_magenta', 'Magenta' );
					self::color_row( 'color_amber', 'Amber' );
					self::color_row( 'color_gold', 'Gold' );
					self::color_row( 'color_cream', 'Cream' );
					self::color_row( 'color_rust', 'Rust' );
					self::color_row( 'color_signal_red', 'Signal red' );
					self::color_row( 'color_warning_yellow', 'Warning yellow' );
					?>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	public static function body_classes( $classes ) {
		$s = self::settings();
		$classes[] = 'cm-moxie-site';
		$classes[] = ! empty( $s['enable_motion'] ) ? 'cm-motion-on' : 'cm-motion-off';
		return $classes;
	}

	public static function enqueue_assets() {
		$s = self::settings();

		if ( ! empty( $s['load_google_fonts'] ) ) {
			wp_enqueue_style(
				'cafe-moxie-site-kit-fonts',
				'https://fonts.googleapis.com/css2?family=Chathura:wght@400;700;800&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap',
				array(),
				null
			);
		}

		wp_register_style( 'cafe-moxie-site-kit', false, array(), self::VERSION );
		wp_enqueue_style( 'cafe-moxie-site-kit' );
		wp_add_inline_style( 'cafe-moxie-site-kit', self::build_css() );
	}

	public static function build_css() {
		$s = self::settings();
		$glow = floatval( $s['glow_intensity'] );
		$ratio = self::ratio_to_padding( $s['card_image_ratio'] );
		$motion = ! empty( $s['enable_motion'] ) ? 1 : 0;
		$text_rgba = self::hex_to_rgba( $s['color_cream'], 0.94 );
		$muted_rgba = self::hex_to_rgba( $s['color_cream'], 0.72 );
		$line_rgba = self::hex_to_rgba( $s['color_cyan'], 0.18 );
		$line_soft_rgba = self::hex_to_rgba( $s['color_cyan'], 0.12 );
		$cyan_glow_rgba = self::hex_to_rgba( $s['color_cyan'], 0.22 );
		$amber_glow_rgba = self::hex_to_rgba( $s['color_amber'], 0.20 );
		$magenta_glow_rgba = self::hex_to_rgba( $s['color_magenta'], 0.18 );

		return "
:root{
--moxie-ink:{$s['color_ink']};
--moxie-midnight:{$s['color_midnight']};
--moxie-oil:{$s['color_oil']};
--moxie-gunmetal:{$s['color_gunmetal']};
--moxie-cyan:{$s['color_cyan']};
--moxie-teal:{$s['color_teal']};
--moxie-arcade:{$s['color_arcade']};
--moxie-magenta:{$s['color_magenta']};
--moxie-amber:{$s['color_amber']};
--moxie-gold:{$s['color_gold']};
--moxie-cream:{$s['color_cream']};
--moxie-rust:{$s['color_rust']};
--moxie-signal-red:{$s['color_signal_red']};
--moxie-warning-yellow:{$s['color_warning_yellow']};
--moxie-text:{$text_rgba};
--moxie-muted:{$muted_rgba};
--moxie-line:{$line_rgba};
--moxie-line-soft:{$line_soft_rgba};
--moxie-logo-width:{$s['logo_width']}px;
--moxie-header-height:{$s['header_height']}px;
--moxie-wrap:min({$s['section_max_width']}px,calc(100% - 32px));
--moxie-radius:{$s['border_radius']}px;
--moxie-card-ratio:{$ratio};
--moxie-button-scale:{$s['button_scale']};
--moxie-glow-cyan:0 0 " . ( 18 * $glow ) . "px {$cyan_glow_rgba};
--moxie-glow-amber:0 0 " . ( 20 * $glow ) . "px {$amber_glow_rgba};
--moxie-glow-magenta:0 0 " . ( 18 * $glow ) . "px {$magenta_glow_rgba};
--moxie-shadow:0 18px 48px rgba(0,0,0,.40);
}
html{scroll-behavior:smooth}
body.cm-moxie-site{background:radial-gradient(circle at top right, rgba(31,184,178,.08), transparent 24%),radial-gradient(circle at top left, rgba(53,214,255,.09), transparent 18%),linear-gradient(180deg,var(--moxie-ink) 0%,var(--moxie-midnight) 42%,var(--moxie-oil) 100%);color:var(--moxie-text)}
body.cm-moxie-site,body.cm-moxie-site button,body.cm-moxie-site input,body.cm-moxie-site select,body.cm-moxie-site textarea,body.cm-moxie-site .wp-block-button__link{font-family:'IBM Plex Sans',system-ui,sans-serif}
body.cm-moxie-site h1,body.cm-moxie-site h2,body.cm-moxie-site h3,body.cm-moxie-site h4,body.cm-moxie-site h5,body.cm-moxie-site h6,body.cm-moxie-site .cm-sign-title,body.cm-moxie-site .wp-block-site-title{font-family:'Chathura','IBM Plex Sans Condensed','IBM Plex Sans',sans-serif;color:var(--moxie-cream);font-weight:700;letter-spacing:.02em;line-height:.9}
body.cm-moxie-site h1{font-size:56px}body.cm-moxie-site h2{font-size:50px}body.cm-moxie-site h3{font-size:44px}body.cm-moxie-site h4{font-size:40px}body.cm-moxie-site h5{font-size:36px}body.cm-moxie-site h6{font-size:32px}
body.cm-moxie-site p,body.cm-moxie-site li,body.cm-moxie-site td,body.cm-moxie-site th{color:var(--moxie-text)}
body.cm-moxie-site a{color:var(--moxie-cyan)}
body.cm-moxie-site a:hover,body.cm-moxie-site a:focus{color:var(--moxie-amber)}
body.cm-moxie-site .wp-site-blocks > header,body.cm-moxie-site .wp-block-template-part{position:relative;z-index:10}
body.cm-moxie-site .wp-block-template-part .wp-block-group{min-height:var(--moxie-header-height)}
body.cm-moxie-site .wp-block-site-logo img,body.cm-moxie-site .custom-logo{width:min(var(--moxie-logo-width),100%);height:auto}
body.cm-moxie-site .wp-block-navigation a{color:var(--moxie-cream);text-decoration:none}
body.cm-moxie-site .wp-block-navigation a:hover{color:var(--moxie-cyan)}
.cm-wrap{width:var(--moxie-wrap);margin-inline:auto}
.cm-section{margin-block:28px}
.cm-panel,.cm-card,.is-style-cm-panel{position:relative;overflow:hidden;padding:24px;border-radius:var(--moxie-radius);border:1px solid var(--moxie-line);background:linear-gradient(180deg,rgba(18,26,43,.96),rgba(10,16,32,.98));box-shadow:var(--moxie-shadow)}
.cm-panel:before,.cm-card:before,.is-style-cm-panel:before{content:'';position:absolute;inset:-1px auto auto -1px;width:180px;height:180px;background:radial-gradient(circle at top left, rgba(53,214,255,.14), transparent 70%);pointer-events:none}
.cm-brand-mark{display:inline-flex;align-items:center;gap:14px;margin-bottom:14px}
.cm-brand-mark img{display:block;width:min(var(--moxie-logo-width),100%);height:auto;filter:drop-shadow(0 0 12px rgba(53,214,255,.18))}
.cm-brand-mark__fallback{display:inline-flex;align-items:center;gap:12px;min-height:40px;padding:10px 16px;border-radius:999px;border:1px solid rgba(53,214,255,.24);background:rgba(53,214,255,.06);font-size:12px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--moxie-cyan)}
.cm-badge,.cm-chip,.cm-status{display:inline-flex;align-items:center;gap:8px;min-height:34px;padding:7px 12px;border-radius:999px;border:1px solid rgba(53,214,255,.22);background:rgba(53,214,255,.06);color:var(--moxie-cyan);font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}
.cm-chip{letter-spacing:0;text-transform:none;color:var(--moxie-cream)}
.cm-status--warm{color:var(--moxie-amber);border-color:rgba(246,179,92,.28);background:rgba(246,179,92,.08);box-shadow:var(--moxie-glow-amber)}
.cm-status--compute{color:var(--moxie-magenta);border-color:rgba(255,79,163,.28);background:rgba(255,79,163,.08);box-shadow:var(--moxie-glow-magenta)}
.cm-status--alert{color:var(--moxie-signal-red);border-color:rgba(230,72,72,.32);background:rgba(230,72,72,.08)}
.cm-chip-list{display:flex;flex-wrap:wrap;gap:10px}
.cm-button,.wp-element-button,.wp-block-button__link{display:inline-flex;align-items:center;justify-content:center;gap:10px;min-height:48px;padding:calc(12px * var(--moxie-button-scale)) calc(18px * var(--moxie-button-scale));border-radius:14px;border:1px solid rgba(53,214,255,.24);background:linear-gradient(180deg,rgba(53,214,255,.18),rgba(31,184,178,.16));color:var(--moxie-cream)!important;text-decoration:none;font-weight:700;box-shadow:var(--moxie-glow-cyan)}
.cm-button--secondary{background:rgba(246,179,92,.08)!important;color:var(--moxie-amber)!important;border-color:rgba(246,179,92,.28)!important;box-shadow:var(--moxie-glow-amber)}
.cm-button--subtle{background:rgba(255,255,255,.04)!important;border-color:rgba(255,255,255,.10)!important;box-shadow:none}
.cm-sign-title{position:relative;display:inline-block;padding-bottom:6px}
.cm-sign-title:after{content:'';position:absolute;left:0;bottom:0;width:82px;height:3px;border-radius:999px;background:linear-gradient(90deg,var(--moxie-cyan),var(--moxie-teal));box-shadow:var(--moxie-glow-cyan)}
.cm-subtle{color:var(--moxie-muted)}
.cm-note{margin-top:16px;padding:14px 16px;border-radius:16px;border:1px solid rgba(246,179,92,.24);background:rgba(246,179,92,.08);color:var(--moxie-cream)}
.cm-grid-2{display:grid;grid-template-columns:1.1fr .9fr;gap:24px}
.cm-grid-3{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:20px}
.cm-grid-4{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px}
.cm-hero{min-height:{$s['hero_min_height']}px;align-items:stretch}
.cm-placeholder{min-height:320px;display:flex;flex-direction:column;justify-content:center;align-items:flex-start;padding:28px;border:1px dashed rgba(53,214,255,.28);border-radius:18px;background:rgba(53,214,255,.04)}
.cm-kv-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
.cm-kv{padding:14px;border:1px solid rgba(53,214,255,.12);border-radius:16px;background:rgba(5,7,13,.26)}
.cm-kv__label{font-size:11px;font-weight:700;letter-spacing:.10em;text-transform:uppercase;color:var(--moxie-cyan);margin-bottom:6px}
.cm-kv__value{color:var(--moxie-cream);font-weight:700;line-height:1.4}
.cm-trust-list,.cm-list{margin:0;padding-left:18px}
.cm-trust-list li,.cm-list li{margin:0 0 8px}
.cm-stat-band{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin-top:18px}
.cm-stat{padding:14px;border-radius:16px;border:1px solid rgba(53,214,255,.14);background:rgba(5,7,13,.24)}
.cm-stat__label{font-size:11px;font-weight:700;letter-spacing:.10em;text-transform:uppercase;color:var(--moxie-cyan)}
.cm-stat__value{margin-top:6px;color:var(--moxie-cream);font-weight:700}
.cm-card-link{text-decoration:none;color:inherit}
.cm-media-frame{position:relative;overflow:hidden;border-radius:calc(var(--moxie-radius) - 6px);border:1px solid rgba(53,214,255,.14);background:linear-gradient(180deg,rgba(10,16,32,.96),rgba(18,26,43,.98))}
.cm-media-frame--ratio:before{content:'';display:block;padding-top:var(--moxie-card-ratio)}
.cm-media-frame--ratio > img,.cm-media-frame--ratio > .cm-media-frame__placeholder{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.cm-media-frame__placeholder{display:flex;align-items:center;justify-content:center;padding:18px;color:var(--moxie-muted);text-align:center;background:radial-gradient(circle at top left, rgba(53,214,255,.10), transparent 58%)}
.cm-tool-card{display:flex;flex-direction:column;gap:16px;height:100%}
.cm-tool-card__body{display:flex;flex-direction:column;gap:14px;flex:1}
.cm-tool-card__meta{display:flex;flex-wrap:wrap;gap:8px}
.cm-tool-card__footer{display:flex;align-items:center;justify-content:space-between;gap:14px;margin-top:auto}
.cm-price{font-weight:700;color:var(--moxie-amber)}
.cm-eyebrow{font-size:12px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--moxie-cyan);margin-bottom:10px}
.cm-archive-page,.cm-single-page{padding-block:28px 52px}
.cm-archive-tools{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:20px;margin-top:22px}
.cm-filter-bar{display:grid;grid-template-columns:2fr repeat(5,minmax(0,1fr));gap:12px;align-items:end}
.cm-filter-bar label{display:block;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--moxie-cyan);margin-bottom:6px}
.cm-filter-bar input,.cm-filter-bar select{width:100%;min-height:44px;border-radius:14px;border:1px solid rgba(53,214,255,.16);background:rgba(5,7,13,.40);color:var(--moxie-cream);padding:10px 12px}
.cm-filter-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:12px}
.cm-empty-state{padding:40px 24px;text-align:left}
.cm-meta-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:24px}
.cm-meta-row{display:grid;grid-template-columns:180px 1fr;gap:16px;padding:12px 0;border-bottom:1px solid rgba(255,255,255,.06)}
.cm-meta-row:last-child{border-bottom:0}
.cm-meta-label{color:var(--moxie-cyan);font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.10em}
.cm-meta-value{color:var(--moxie-cream);line-height:1.7}
.cm-section-stack{display:grid;gap:24px}
.cm-gallery{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px}
.cm-gallery figure{margin:0}
.cm-gallery img,.cm-before-after img{display:block;width:100%;height:100%;object-fit:cover}
.cm-before-after{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}
.cm-video-wrap iframe{width:100%;min-height:420px;border:0;border-radius:calc(var(--moxie-radius) - 6px)}
.cm-footer-line{display:inline-block;color:var(--moxie-cream);padding-top:8px;border-top:1px solid rgba(53,214,255,.18)}
.cm-query-summary{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:18px}
.cm-pagination{margin-top:28px}
.cm-pagination .page-numbers{display:inline-flex;align-items:center;justify-content:center;min-width:42px;min-height:42px;margin-right:8px;border-radius:12px;border:1px solid rgba(53,214,255,.14);background:rgba(255,255,255,.04);color:var(--moxie-cream);text-decoration:none}
.cm-pagination .page-numbers.current{background:rgba(53,214,255,.12);color:var(--moxie-cyan);box-shadow:var(--moxie-glow-cyan)}
body.cm-motion-on .cm-button,body.cm-motion-on .cm-card,body.cm-motion-on .cm-panel,body.cm-motion-on .cm-tool-card{transition:transform .22s ease, box-shadow .22s ease, border-color .22s ease, background .22s ease}
body.cm-motion-on .cm-button:hover,body.cm-motion-on .cm-button:focus{transform:translateY(-1px)}
body.cm-motion-on .cm-card:hover,body.cm-motion-on .cm-panel:hover{transform:translateY(-2px);border-color:rgba(53,214,255,.28)}
body.cm-motion-on .cm-sign-flicker{animation:cmSignFlicker " . ( $motion ? '6.2s' : '0s' ) . " ease-in-out infinite}
@keyframes cmSignFlicker{0%,100%{opacity:1;filter:drop-shadow(0 0 0 rgba(53,214,255,0))}2%{opacity:.88}4%{opacity:1}48%{opacity:1}50%{opacity:.9}51%{opacity:1}}
@media (max-width:1160px){.cm-filter-bar{grid-template-columns:1fr 1fr 1fr}.cm-grid-4,.cm-gallery{grid-template-columns:repeat(2,minmax(0,1fr))}.cm-archive-tools{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media (max-width:920px){.cm-grid-2,.cm-grid-3,.cm-stat-band,.cm-meta-grid{grid-template-columns:1fr}.cm-before-after{grid-template-columns:1fr}.cm-filter-bar{grid-template-columns:1fr 1fr}.cm-query-summary{flex-direction:column;align-items:flex-start}}
@media (max-width:640px){body.cm-moxie-site h1{font-size:46px}body.cm-moxie-site h2{font-size:42px}body.cm-moxie-site h3{font-size:38px}.cm-grid-4,.cm-gallery,.cm-kv-grid,.cm-archive-tools,.cm-filter-bar{grid-template-columns:1fr}.cm-meta-row{grid-template-columns:1fr;gap:6px}.cm-video-wrap iframe{min-height:280px}}
";
	}

	private static function hex_to_rgba( $hex, $alpha = 1 ) {
		$hex = ltrim( (string) $hex, '#' );
		if ( 3 === strlen( $hex ) ) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
		}
		if ( 6 !== strlen( $hex ) ) {
			return 'rgba(255,255,255,' . floatval( $alpha ) . ')';
		}
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );
		return 'rgba(' . $r . ',' . $g . ',' . $b . ',' . floatval( $alpha ) . ')';
	}

	private static function ratio_to_padding( $ratio_string ) {
		$ratio = '62.5%';
		$parts = array_map( 'trim', explode( ':', (string) $ratio_string ) );
		if ( 2 === count( $parts ) && floatval( $parts[0] ) > 0 && floatval( $parts[1] ) > 0 ) {
			$ratio = ( floatval( $parts[1] ) / floatval( $parts[0] ) ) * 100 . '%';
		}
		return $ratio;
	}

	public static function register_patterns() {
		if ( ! function_exists( 'register_block_pattern' ) ) {
			return;
		}
		if ( function_exists( 'register_block_pattern_category' ) ) {
			register_block_pattern_category( 'cafe-moxie', array( 'label' => __( 'Cafe Moxie', 'cafe-moxie-site-kit' ) ) );
		}

		foreach ( array( 'home' => 'Cafe Moxie Homepage', 'about' => 'Cafe Moxie About Page' ) as $slug => $title ) {
			$file = plugin_dir_path( __FILE__ ) . 'patterns/' . $slug . '.php';
			if ( file_exists( $file ) ) {
				ob_start();
				include $file;
				register_block_pattern(
					'cafe-moxie/' . $slug,
					array(
						'title'      => $title,
						'categories' => array( 'cafe-moxie' ),
						'content'    => ob_get_clean(),
					)
				);
			}
		}
	}

	public static function register_shortcodes() {
		add_shortcode( 'cafe_moxie_featured_edge_tools', array( __CLASS__, 'featured_edge_tools_shortcode' ) );
	}

	public static function featured_edge_tools_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'count' => self::settings()['featured_tools_count'],
			),
			$atts,
			'cafe_moxie_featured_edge_tools'
		);

		$count = max( 1, min( 12, intval( $atts['count'] ) ) );
		$query = new WP_Query(
			array(
				'post_type'      => 'edge_tool',
				'post_status'    => 'publish',
				'posts_per_page' => $count,
				'meta_key'       => 'featured_tool',
				'meta_value'     => '1',
			)
		);

		if ( ! $query->have_posts() ) {
			$query = new WP_Query(
				array(
					'post_type'      => 'edge_tool',
					'post_status'    => 'publish',
					'posts_per_page' => $count,
				)
			);
		}

		ob_start();
		echo '<section class="cm-section">';
		echo '<div class="cm-panel">';
		echo '<div class="cm-eyebrow">Fresh at the counter</div>';
		echo '<h2 class="cm-sign-title">Featured tools</h2>';
		echo '<p class="cm-subtle">Built to feel like a storefront, not a plugin pile. Featured tools pull directly from your Edge Tool posts and Secure Custom Fields.</p>';
		echo '</div>';
		if ( $query->have_posts() ) {
			echo '<div class="cm-archive-tools">';
			while ( $query->have_posts() ) {
				$query->the_post();
				echo self::render_tool_card( get_the_ID() );
			}
			echo '</div>';
		} else {
			echo '<div class="cm-panel cm-empty-state"><p class="cm-subtle">Add Edge Tool posts and they will appear here automatically.</p></div>';
		}
		echo '</section>';
		wp_reset_postdata();
		return ob_get_clean();
	}

	public static function create_or_update_page( $slug, $title, $content ) {
		$existing = get_page_by_path( $slug, OBJECT, 'page' );
		$data = array(
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_content' => $content,
		);
		if ( $existing ) {
			$data['ID'] = $existing->ID;
			return wp_update_post( $data, true );
		}
		return wp_insert_post( $data, true );
	}

	public static function create_starter_pages() {
		check_admin_referer( 'cafe_moxie_create_starter_pages' );
		foreach ( array( 'home' => 'Home', 'about' => 'About' ) as $slug => $title ) {
			ob_start();
			include plugin_dir_path( __FILE__ ) . 'patterns/' . $slug . '.php';
			self::create_or_update_page( $slug, $title, ob_get_clean() );
		}
		wp_safe_redirect( admin_url( 'admin.php?page=cafe-moxie-site-kit&created=1' ) );
		exit;
	}

	public static function template_include( $template ) {
		if ( is_singular( 'edge_tool' ) ) {
			$file = plugin_dir_path( __FILE__ ) . 'templates/single-edge_tool.php';
			if ( file_exists( $file ) ) {
				return $file;
			}
		}
		if ( is_post_type_archive( 'edge_tool' ) ) {
			$file = plugin_dir_path( __FILE__ ) . 'templates/archive-edge_tool.php';
			if ( file_exists( $file ) ) {
				return $file;
			}
		}
		return $template;
	}

	public static function get_field( $field_name, $post_id = null, $default = '' ) {
		$value = null;
		if ( function_exists( 'get_field' ) ) {
			$value = get_field( $field_name, $post_id );
		}
		if ( null !== $value && false !== $value && '' !== $value ) {
			return $value;
		}
		if ( $post_id ) {
			$meta = get_post_meta( $post_id, $field_name, true );
			if ( null !== $meta && '' !== $meta ) {
				return $meta;
			}
		}
		return $default;
	}

	public static function get_term_names( $taxonomy, $post_id = null ) {
		$post_id = $post_id ? $post_id : get_the_ID();
		$terms = get_the_terms( $post_id, $taxonomy );
		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return array();
		}
		return wp_list_pluck( $terms, 'name' );
	}

	public static function flatten_repeater_items( $rows, $sub_key = 'item' ) {
		$out = array();
		if ( empty( $rows ) || ! is_array( $rows ) ) {
			return $out;
		}

		$stack = array();
		foreach ( $rows as $row ) {
			$value = null;

			if ( is_scalar( $row ) ) {
				$value = $row;
			} elseif ( is_array( $row ) && array_key_exists( $sub_key, $row ) ) {
				$value = $row[ $sub_key ];
			} elseif ( is_array( $row ) ) {
				$value = $row;
			}

			if ( is_array( $value ) ) {
				foreach ( new RecursiveIteratorIterator( new RecursiveArrayIterator( $value ) ) as $leaf ) {
					if ( is_scalar( $leaf ) ) {
						$stack[] = trim( (string) $leaf );
					}
				}
			} elseif ( is_scalar( $value ) ) {
				$stack[] = trim( (string) $value );
			}
		}

		foreach ( $stack as $item ) {
			if ( '' !== $item ) {
				$out[] = $item;
			}
		}

		return array_values( array_unique( $out ) );
	}

	public static function flatten_format_rows( $rows ) {
		$out = array();
		if ( empty( $rows ) || ! is_array( $rows ) ) {
			return $out;
		}
		foreach ( $rows as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$label = trim( (string) ( $row['format_label'] ?? '' ) );
			$mime  = trim( (string) ( $row['mime_or_extension'] ?? '' ) );
			if ( $label && $mime ) {
				$out[] = $label . ' (' . $mime . ')';
			} elseif ( $label ) {
				$out[] = $label;
			} elseif ( $mime ) {
				$out[] = $mime;
			}
		}
		return $out;
	}

	public static function flatten_service_rows( $rows ) {
		$out = array();
		if ( empty( $rows ) || ! is_array( $rows ) ) {
			return $out;
		}
		foreach ( $rows as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$name = trim( (string) ( $row['service_name'] ?? '' ) );
			$link = trim( (string) ( $row['service_link'] ?? '' ) );
			if ( ! $name && ! $link ) {
				continue;
			}
			$out[] = $link ? sprintf( '%s (%s)', $name ? $name : $link, $link ) : $name;
		}
		return $out;
	}

	public static function flatten_image_urls( $items, $size = 'large' ) {
		$urls = array();
		if ( empty( $items ) || ! is_array( $items ) ) {
			return $urls;
		}
		foreach ( $items as $item ) {
			$url = self::image_url( $item, $size );
			if ( $url ) {
				$urls[] = $url;
			}
		}
		return $urls;
	}

	public static function image_url( $value, $size = 'large' ) {
		if ( empty( $value ) ) {
			return '';
		}
		if ( is_numeric( $value ) ) {
			return wp_get_attachment_image_url( intval( $value ), $size );
		}
		if ( is_string( $value ) ) {
			return esc_url( $value );
		}
		if ( is_array( $value ) ) {
			if ( ! empty( $value['ID'] ) ) {
				return wp_get_attachment_image_url( intval( $value['ID'] ), $size );
			}
			if ( ! empty( $value['id'] ) ) {
				return wp_get_attachment_image_url( intval( $value['id'] ), $size );
			}
			if ( ! empty( $value['sizes'][ $size ] ) ) {
				return esc_url( $value['sizes'][ $size ] );
			}
			if ( ! empty( $value['url'] ) ) {
				return esc_url( $value['url'] );
			}
		}
		return '';
	}

	public static function render_brand_mark() {
		$s = self::settings();
		$image = self::resolve_url( $s['display_logo_image'] );
		if ( $image ) {
			return '<div class="cm-brand-mark cm-sign-flicker"><img src="' . esc_url( $image ) . '" alt="Cafe Moxie"></div>';
		}
		return '<div class="cm-brand-mark"><span class="cm-brand-mark__fallback">' . esc_html( $s['site_kicker'] ) . '</span></div>';
	}

	public static function tool_data( $post_id ) {
		$post_id = intval( $post_id );
		$featured_image = get_the_post_thumbnail_url( $post_id, 'large' );
		$hero_image     = self::image_url( self::get_field( 'hero_image', $post_id ), 'large' );
		$gallery        = self::flatten_image_urls( self::get_field( 'gallery', $post_id, array() ), 'large' );
		$before_after   = self::get_field( 'before_after_examples', $post_id, array() );
		$best_for       = self::flatten_repeater_items( self::get_field( 'best_for', $post_id, array() ) );
		$not_for        = self::flatten_repeater_items( self::get_field( 'not_for', $post_id, array() ) );
		$secondary      = self::flatten_repeater_items( self::get_field( 'secondary_tasks', $post_id, array() ), 'task' );
		$linux_distros  = self::flatten_repeater_items( self::get_field( 'linux_distros', $post_id, array() ), 'distro' );
		$input_formats  = self::flatten_format_rows( self::get_field( 'input_formats', $post_id, array() ) );
		$output_formats = self::flatten_format_rows( self::get_field( 'output_formats', $post_id, array() ) );
		$services       = self::flatten_service_rows( self::get_field( 'third_party_services', $post_id, array() ) );

		$data = array(
			'post_id'           => $post_id,
			'title'             => get_the_title( $post_id ),
			'permalink'         => get_permalink( $post_id ),
			'excerpt'           => get_the_excerpt( $post_id ),
			'content'           => apply_filters( 'the_content', get_post_field( 'post_content', $post_id ) ),
			'featured_image'    => $featured_image ? $featured_image : '',
			'hero_image'        => $hero_image ? $hero_image : ( $featured_image ? $featured_image : '' ),
			'gallery'           => $gallery,
			'before_after'      => is_array( $before_after ) ? $before_after : array(),
			'demo_video'        => self::get_field( 'demo_video', $post_id ),
			'short_tagline'     => self::get_field( 'short_tagline', $post_id ),
			'one_line_value'    => self::get_field( 'one_line_value', $post_id ),
			'tool_summary'      => self::get_field( 'tool_summary', $post_id ),
			'primary_task'      => self::get_field( 'primary_task', $post_id ),
			'secondary_tasks'   => $secondary,
			'buying_model'      => self::get_field( 'buying_model_label', $post_id ),
			'requires_compute'  => ! empty( self::get_field( 'requires_compute', $post_id ) ),
			'featured_tool'     => ! empty( self::get_field( 'featured_tool', $post_id ) ),
			'supported_os'      => self::get_field( 'supported_os', $post_id, array() ),
			'windows_versions'  => self::get_field( 'windows_versions', $post_id, array() ),
			'mac_versions'      => self::get_field( 'mac_versions', $post_id ),
			'linux_distros'     => $linux_distros,
			'cpu_architecture'  => self::get_field( 'cpu_architecture', $post_id, array() ),
			'runs_local'        => ! empty( self::get_field( 'runs_local', $post_id ) ),
			'internet_required' => ! empty( self::get_field( 'internet_required', $post_id ) ),
			'admin_required'    => ! empty( self::get_field( 'admin_rights_required', $post_id ) ),
			'portable_tool'     => ! empty( self::get_field( 'portable_tool', $post_id ) ),
			'install_method'    => self::get_field( 'install_method', $post_id ),
			'shell_type'        => self::get_field( 'shell_type', $post_id ),
			'accepts_drag_drop' => ! empty( self::get_field( 'accepts_drag_drop', $post_id ) ),
			'input_formats'     => $input_formats,
			'output_formats'    => $output_formats,
			'max_file_size'     => self::get_field( 'max_file_size', $post_id ),
			'batch_processing'  => ! empty( self::get_field( 'batch_processing', $post_id ) ),
			'folder_processing' => ! empty( self::get_field( 'folder_processing', $post_id ) ),
			'preserves_metadata'=> ! empty( self::get_field( 'preserves_metadata', $post_id ) ),
			'destructive'       => ! empty( self::get_field( 'destructive_operation', $post_id ) ),
			'creates_backup'    => ! empty( self::get_field( 'creates_backup', $post_id ) ),
			'how_it_works'      => self::get_field( 'how_it_works', $post_id ),
			'best_for'          => $best_for,
			'not_for'           => $not_for,
			'human_review'      => ! empty( self::get_field( 'human_review_needed', $post_id ) ),
			'human_review_note' => self::get_field( 'human_review_note', $post_id ),
			'typical_runtime'   => self::get_field( 'typical_runtime', $post_id ),
			'steps_required'    => self::get_field( 'steps_required', $post_id ),
			'automation_level'  => self::get_field( 'automation_level', $post_id ),
			'processes_locally' => ! empty( self::get_field( 'processes_locally', $post_id ) ),
			'uploads_to_cloud'  => ! empty( self::get_field( 'uploads_to_cloud', $post_id ) ),
			'stores_user_files' => ! empty( self::get_field( 'stores_user_files', $post_id ) ),
			'data_retention'    => self::get_field( 'data_retention_note', $post_id ),
			'privacy_note'      => self::get_field( 'privacy_note', $post_id ),
			'sensitive_warning' => ! empty( self::get_field( 'sensitive_content_warning', $post_id ) ),
			'requires_api_key'  => ! empty( self::get_field( 'requires_api_key', $post_id ) ),
			'third_party'       => $services,
			'price_type'        => self::get_field( 'price_type', $post_id ),
			'price_display'     => self::get_field( 'price_display', $post_id ),
			'credit_cost'       => self::get_field( 'credit_cost', $post_id ),
			'trial_available'   => ! empty( self::get_field( 'trial_available', $post_id ) ),
			'download_url'      => self::resolve_url( self::get_field( 'download_url', $post_id ) ),
			'compute_run_url'   => self::resolve_url( self::get_field( 'compute_run_url', $post_id ) ),
			'version'           => self::get_field( 'version_number', $post_id ),
			'release_status'    => self::get_field( 'release_status', $post_id ),
			'taxonomies'        => array(
				'tool_type'       => self::get_term_names( 'tool_type', $post_id ),
				'platform'        => self::get_term_names( 'platform', $post_id ),
				'execution_model' => self::get_term_names( 'execution_model', $post_id ),
				'input_type'      => self::get_term_names( 'input_type', $post_id ),
				'output_type'     => self::get_term_names( 'output_type', $post_id ),
				'workflow_area'   => self::get_term_names( 'workflow_area', $post_id ),
			),
		);

		$data['execution_mode'] = self::derive_execution_mode( $data );
		$data['trust_cue']      = self::derive_trust_cue( $data );
		$data['platform_line']  = self::derive_platform_line( $data );

		return apply_filters( 'cafe_moxie_tool_data', $data, $post_id );
	}

	private static function derive_execution_mode( $data ) {
		if ( ! empty( $data['runs_local'] ) && ! empty( $data['requires_compute'] ) ) {
			return 'Local + Optional Compute';
		}
		if ( ! empty( $data['requires_compute'] ) ) {
			return 'Uses Compute Credits';
		}
		if ( ! empty( $data['runs_local'] ) || ! empty( $data['processes_locally'] ) ) {
			return 'Runs Local';
		}
		if ( ! empty( $data['uploads_to_cloud'] ) ) {
			return 'Uses Compute Credits';
		}
		return 'See details';
	}

	private static function derive_trust_cue( $data ) {
		if ( ! empty( $data['human_review_note'] ) ) {
			return $data['human_review_note'];
		}
		if ( ! empty( $data['human_review'] ) ) {
			return 'A human eye is still recommended before final delivery.';
		}
		if ( ! empty( $data['processes_locally'] ) && empty( $data['uploads_to_cloud'] ) ) {
			return 'Processes locally. Keeps judgment with the operator.';
		}
		return 'Check the tool details to confirm what still needs review.';
	}

	private static function derive_platform_line( $data ) {
		$parts = array();
		if ( ! empty( $data['supported_os'] ) && is_array( $data['supported_os'] ) ) {
			$parts[] = implode( ', ', $data['supported_os'] );
		}
		if ( ! empty( $data['cpu_architecture'] ) && is_array( $data['cpu_architecture'] ) ) {
			$parts[] = implode( ', ', $data['cpu_architecture'] );
		}
		return implode( ' · ', array_filter( $parts ) );
	}

	public static function render_bool( $value, $true = 'Yes', $false = 'No' ) {
		return $value ? $true : $false;
	}

	public static function render_chip_list( $items, $extra_class = '' ) {
		$items = array_values( array_filter( (array) $items ) );
		if ( empty( $items ) ) {
			return '';
		}
		$html = '<div class="cm-chip-list ' . esc_attr( $extra_class ) . '">';
		foreach ( $items as $item ) {
			$html .= '<span class="cm-chip">' . esc_html( $item ) . '</span>';
		}
		$html .= '</div>';
		return $html;
	}

	public static function render_list( $items, $class = 'cm-list' ) {
		$items = array_values( array_filter( (array) $items ) );
		if ( empty( $items ) ) {
			return '';
		}
		$html = '<ul class="' . esc_attr( $class ) . '">';
		foreach ( $items as $item ) {
			$html .= '<li>' . esc_html( $item ) . '</li>';
		}
		$html .= '</ul>';
		return $html;
	}

	public static function render_meta_row( $label, $value ) {
		if ( '' === trim( wp_strip_all_tags( (string) $value ) ) ) {
			return '';
		}
		return '<div class="cm-meta-row"><div class="cm-meta-label">' . esc_html( $label ) . '</div><div class="cm-meta-value">' . $value . '</div></div>';
	}

	public static function render_tool_card( $post_id ) {
		$d = self::tool_data( $post_id );
		$meta = array();
		if ( $d['buying_model'] ) {
			$meta[] = $d['buying_model'];
		}
		if ( $d['execution_mode'] ) {
			$meta[] = $d['execution_mode'];
		}
		if ( $d['featured_tool'] ) {
			$meta[] = 'Worker Favorite';
		}

		$image_markup = '';
		if ( $d['hero_image'] ) {
			$image_markup = '<div class="cm-media-frame cm-media-frame--ratio"><img src="' . esc_url( $d['hero_image'] ) . '" alt="' . esc_attr( $d['title'] ) . '"></div>';
		} else {
			$image_markup = '<div class="cm-media-frame cm-media-frame--ratio"><div class="cm-media-frame__placeholder">Add a featured image or SCF hero image to elevate the card.</div></div>';
		}

		$html  = '<article class="cm-card cm-tool-card">';
		$html .= '<a class="cm-card-link" href="' . esc_url( $d['permalink'] ) . '">' . $image_markup . '</a>';
		$html .= '<div class="cm-tool-card__body">';
		$html .= '<div class="cm-tool-card__meta">';
		foreach ( $meta as $item ) {
			$class = false !== stripos( $item, 'Compute' ) ? 'cm-status cm-status--compute' : 'cm-badge';
			if ( 'Worker Favorite' === $item ) {
				$class = 'cm-status cm-status--warm';
			}
			$html .= '<span class="' . esc_attr( $class ) . '">' . esc_html( $item ) . '</span>';
		}
		$html .= '</div>';
		$html .= '<h3 class="cm-sign-title"><a href="' . esc_url( $d['permalink'] ) . '" style="color:var(--moxie-cream)">' . esc_html( $d['title'] ) . '</a></h3>';
		if ( $d['short_tagline'] ) {
			$html .= '<div style="color:var(--moxie-amber);font-weight:700">' . esc_html( $d['short_tagline'] ) . '</div>';
		}
		$html .= '<p class="cm-subtle">' . esc_html( $d['tool_summary'] ? $d['tool_summary'] : $d['excerpt'] ) . '</p>';
		if ( ! empty( $d['taxonomies']['workflow_area'] ) ) {
			$html .= self::render_chip_list( array_slice( $d['taxonomies']['workflow_area'], 0, 3 ) );
		}
		$html .= '<div class="cm-tool-card__footer">';
		$html .= '<div><div class="cm-price">' . esc_html( $d['price_display'] ? $d['price_display'] : 'See details' ) . '</div><div class="cm-subtle" style="font-size:13px;max-width:30ch;">' . esc_html( $d['trust_cue'] ) . '</div></div>';
		$html .= '<a class="cm-button" href="' . esc_url( $d['permalink'] ) . '">View Tool</a>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</article>';
		return $html;
	}

	public static function archive_filters() {
		return array(
			'tool_type'       => 'Tool Type',
			'workflow_area'   => 'Workflow Area',
			'platform'        => 'Platform',
			'execution_model' => 'Execution Model',
		);
	}

	public static function request_value( $key ) {
		return sanitize_text_field( wp_unslash( $_GET[ $key ] ?? '' ) );
	}

	public static function archive_query() {
		$s     = self::settings();
		$paged = max( 1, intval( get_query_var( 'paged' ) ?: get_query_var( 'page' ) ?: 1 ) );
		$args  = array(
			'post_type'      => 'edge_tool',
			'post_status'    => 'publish',
			'paged'          => $paged,
			'posts_per_page' => intval( $s['archive_items_per_page'] ),
		);

		$search = self::request_value( 'cm_search' );
		if ( $search ) {
			$args['s'] = $search;
		}

		$tax_query = array();
		foreach ( self::archive_filters() as $taxonomy => $label ) {
			$value = self::request_value( 'cm_' . $taxonomy );
			if ( $value ) {
				$tax_query[] = array(
					'taxonomy' => $taxonomy,
					'field'    => 'slug',
					'terms'    => sanitize_title( $value ),
				);
			}
		}
		if ( ! empty( $tax_query ) ) {
			if ( count( $tax_query ) > 1 ) {
				$tax_query = array_merge( array( 'relation' => 'AND' ), $tax_query );
			}
			$args['tax_query'] = $tax_query;
		}

		$meta_query = array();
		$mode = self::request_value( 'cm_mode' );
		if ( 'local' === $mode ) {
			$meta_query[] = array(
				'key'     => 'runs_local',
				'value'   => '1',
				'compare' => '=',
			);
		} elseif ( 'compute' === $mode ) {
			$meta_query[] = array(
				'key'     => 'requires_compute',
				'value'   => '1',
				'compare' => '=',
			);
		} elseif ( 'hybrid' === $mode ) {
			$meta_query[] = array(
				'relation' => 'AND',
				array(
					'key'     => 'runs_local',
					'value'   => '1',
					'compare' => '=',
				),
				array(
					'key'     => 'requires_compute',
					'value'   => '1',
					'compare' => '=',
				),
			);
		}
		if ( ! empty( self::request_value( 'cm_featured' ) ) ) {
			$meta_query[] = array(
				'key'     => 'featured_tool',
				'value'   => '1',
				'compare' => '=',
			);
		}
		if ( ! empty( $meta_query ) ) {
			if ( count( $meta_query ) > 1 ) {
				$meta_query = array_merge( array( 'relation' => 'AND' ), $meta_query );
			}
			$args['meta_query'] = $meta_query;
		}

		return new WP_Query( apply_filters( 'cafe_moxie_edge_tool_archive_query_args', $args ) );
	}

	public static function archive_filter_select( $taxonomy, $label ) {
		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => true,
			)
		);
		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return '';
		}
		$current = self::request_value( 'cm_' . $taxonomy );
		$html = '<div><label for="cm_' . esc_attr( $taxonomy ) . '">' . esc_html( $label ) . '</label><select id="cm_' . esc_attr( $taxonomy ) . '" name="cm_' . esc_attr( $taxonomy ) . '"><option value="">All</option>';
		foreach ( $terms as $term ) {
			$html .= '<option value="' . esc_attr( $term->slug ) . '" ' . selected( $current, $term->slug, false ) . '>' . esc_html( $term->name ) . '</option>';
		}
		$html .= '</select></div>';
		return $html;
	}

	public static function pagination_links( $query ) {
		if ( ! $query || intval( $query->max_num_pages ) < 2 ) {
			return '';
		}
		$big = 999999999;
		return paginate_links(
			array(
				'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'    => '?paged=%#%',
				'current'   => max( 1, intval( get_query_var( 'paged' ) ?: get_query_var( 'page' ) ?: 1 ) ),
				'total'     => intval( $query->max_num_pages ),
				'type'      => 'list',
				'prev_text' => '&larr;',
				'next_text' => '&rarr;',
				'add_args'  => self::current_archive_args(),
			)
		);
	}

	public static function current_archive_args() {
		$args = array();
		foreach ( array( 'cm_search', 'cm_tool_type', 'cm_workflow_area', 'cm_platform', 'cm_execution_model', 'cm_mode', 'cm_featured' ) as $key ) {
			$value = self::request_value( $key );
			if ( '' !== $value ) {
				$args[ $key ] = $value;
			}
		}
		return $args;
	}
}

Cafe_Moxie_Site_Kit::init();
