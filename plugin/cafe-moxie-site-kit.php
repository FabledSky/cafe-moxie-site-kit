<?php
/**
 * Plugin Name: Cafe Moxie Site Kit
 * Description: Reusable site system kit for Twenty Twenty-Five with brand presets, editable patterns, and Secure Custom Fields powered Edge Tool templates.
 * Version: 2.0.0
 * Author: Fabled Sky Research
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Cafe_Moxie_Site_Kit {
	const OPTION  = 'cafe_moxie_site_kit_settings';
	const VERSION = '2.0.0';
	const META_GENERATED_MARKER = '_cm_site_kit_generated';
	const META_GENERATED_TYPE   = '_cm_site_kit_generated_type';
	const META_GENERATED_AT     = '_cm_site_kit_generated_at';
	const META_GENERATED_HASH   = '_cm_site_kit_generated_hash';

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
		add_action( 'init', array( __CLASS__, 'register_patterns' ) );
		add_action( 'init', array( __CLASS__, 'register_shortcodes' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
		add_filter( 'template_include', array( __CLASS__, 'template_include' ), 99 );
		add_filter( 'body_class', array( __CLASS__, 'body_classes' ) );
		add_action( 'admin_post_cafe_moxie_create_starter_pages', array( __CLASS__, 'create_starter_pages' ) );
		add_action( 'admin_post_cafe_moxie_generate_composed_page', array( __CLASS__, 'generate_composed_page' ) );
		add_action( 'admin_post_cafe_moxie_generate_template_parts', array( __CLASS__, 'generate_template_parts' ) );
		add_action( 'admin_post_cafe_moxie_assign_front_page', array( __CLASS__, 'assign_front_page' ) );
		add_action( 'admin_post_cafe_moxie_apply_preset', array( __CLASS__, 'apply_brand_preset_action' ) );
		add_action( 'admin_post_cafe_moxie_polished_setup', array( __CLASS__, 'run_polished_setup' ) );
		add_filter( 'render_block_data', array( __CLASS__, 'filter_template_part_blocks' ), 10, 2 );

		if ( false === get_option( self::OPTION ) ) {
			update_option( self::OPTION, self::apply_brand_preset_to_settings( self::defaults(), 'cafe_moxie', true ) );
		}
	}

	public static function defaults() {
		$defaults = array();
		foreach ( self::settings_registry() as $key => $field ) {
			$defaults[ $key ] = $field['default'];
		}
		return $defaults;
	}

	public static function settings() {
		return wp_parse_args( get_option( self::OPTION, array() ), self::defaults() );
	}

	private static function preset_defaults_map() {
		return array(
			'cafe_moxie' => array(
				'enable_managed_header_footer' => 1,
				'header_footer_preset' => 'counter',
				'header_brand_treatment' => 'logo_and_name',
				'header_nav_source' => 'primary_navigation',
				'header_cta_label' => 'Browse the Counter',
				'header_cta_url' => '/edge-tools/',
				'home_primary_cta' => 'Browse the Counter',
				'home_secondary_cta' => 'See What Runs Local',
				'about_primary_cta' => 'Browse the Counter',
				'footer_copy' => 'Tools for people who actually do the work.',
				'footer_utility_copy' => 'Pull up a chair. The tools are ready.',
				'footer_content_primary' => 'Own it when you want ownership. Run it when you just need the task done.',
				'footer_content_secondary' => 'Buy once for local tools. Pay per task for compute-backed workflows.',
				'logo_width' => 240,
				'header_height' => 88,
				'header_vertical_padding' => 12,
				'footer_vertical_padding' => 24,
				'section_max_width' => 1240,
				'outer_wrapper_gutter' => 28,
				'hero_min_height' => 620,
				'hero_section_spacing' => 36,
				'border_radius' => 16,
				'button_scale' => 0.96,
				'mobile_heading_scale' => 0.94,
				'card_image_ratio' => '4:3',
				'grid_gap' => 24,
				'panel_padding' => 26,
				'content_max_width' => 720,
				'content_band_max_width' => 1160,
				'archive_rail_max_width' => 1280,
				'single_rail_max_width' => 1120,
				'responsive_breakpoint_mode' => 'early_stack',
				'page_section_density' => 'comfortable',
				'site_density_preset' => 'comfortable',
				'archive_columns' => 3,
				'tablet_columns' => 2,
				'home_hero_layout' => 'media_right_split',
				'home_story_layout' => 'media_right_split',
				'home_trust_layout' => 'stacked_on_tablet',
				'home_featured_layout' => 'stacked_on_tablet',
				'about_intro_layout' => 'media_right_split',
				'about_calibrate_layout' => 'stacked_on_tablet',
			),
			'neutral' => array(
				'enable_managed_header_footer' => 0,
				'header_cta_label' => 'Browse Tools',
				'footer_utility_copy' => 'Reliable defaults. Editable everywhere.',
			),
		);
	}

	private static function apply_brand_preset_to_settings( $settings, $preset_key, $force_all = false ) {
		$registry = self::settings_registry();
		$preset_map = self::preset_defaults_map();
		$preset_key = sanitize_key( $preset_key );
		$preset_defaults = $preset_map[ $preset_key ] ?? array();

		foreach ( $preset_defaults as $key => $value ) {
			if ( ! isset( $registry[ $key ] ) ) {
				continue;
			}
			$participates = ! empty( $registry[ $key ]['preset_participation'] );
			if ( ! $force_all && ! $participates ) {
				continue;
			}
			$settings[ $key ] = $value;
		}
		$settings['brand_preset'] = $preset_key;
		return wp_parse_args( $settings, self::defaults() );
	}

	public static function settings_groups() {
		return array(
			'global_design_tokens' => array(
				'label' => 'Global Design Tokens',
				'description' => 'Cross-site visual tokens shared by templates and components.',
				'keys' => array(
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
					'logo_width',
					'header_height',
					'section_max_width',
					'outer_wrapper_gutter',
					'hero_min_height',
					'header_vertical_padding',
					'footer_vertical_padding',
					'hero_section_spacing',
					'border_radius',
					'button_scale',
					'glow_intensity',
					'mobile_heading_scale',
					'load_google_fonts',
					'enable_motion',
				),
			),
			'component_defaults' => array(
				'label' => 'Component Defaults',
				'description' => 'Reusable defaults for cards, grids, and shared UI building blocks.',
				'keys' => array(
					'card_image_ratio',
					'card_grid_density',
					'template_surface',
					'content_max_width',
					'content_band_max_width',
					'grid_gap',
					'panel_padding',
					'archive_rail_max_width',
					'single_rail_max_width',
					'archive_columns',
					'tablet_columns',
				),
			),
			'page_template_defaults' => array(
				'label' => 'Page Template Defaults',
				'description' => 'Section composition and layout defaults used by starter and composed pages.',
				'keys' => array(
					'layout_behavior',
					'mobile_layout_mode',
					'responsive_breakpoint_mode',
					'page_section_density',
					'site_density_preset',
					'home_hero_layout',
					'home_story_layout',
					'home_trust_layout',
					'home_featured_layout',
					'about_intro_layout',
					'about_calibrate_layout',
					'show_home_story',
					'show_home_trust',
					'show_home_featured',
					'show_home_closing',
					'show_about_values',
					'show_about_calibrate',
					'composed_page_template',
					'composed_page_slug',
					'composed_page_title',
					'refresh_mode',
				),
			),
			'storefront_defaults' => array(
				'label' => 'Storefront Defaults',
				'description' => 'Catalog behavior and commerce-facing defaults for the Edge Tool storefront layer.',
				'keys' => array(
					'brand_preset',
					'site_kicker',
					'featured_tools_count',
					'archive_items_per_page',
					'show_archive_filters',
					'display_logo_image',
					'enable_managed_header_footer',
					'header_footer_preset',
					'header_brand_treatment',
					'header_nav_source',
					'header_cta_label',
					'header_cta_url',
					'footer_utility_copy',
					'home_hero_image',
					'home_story_image',
					'about_story_image',
					'home_primary_cta',
					'home_primary_url',
					'home_secondary_cta',
					'home_secondary_url',
					'about_primary_cta',
					'about_primary_url',
					'footer_content_primary',
					'footer_content_secondary',
					'footer_copy',
					'footer_meta_behavior',
					'footer_legal_text',
				),
			),
		);
	}

	public static function settings_registry() {
		$layout_modes = self::layout_mode_choices();
		$registry = array(
			'brand_preset' => array( 'label' => 'Brand preset', 'description' => 'Preset used as a baseline visual profile.', 'group' => 'storefront_defaults', 'type' => 'select', 'allowed_values' => array( 'cafe_moxie' => 'Cafe Moxie', 'neutral' => 'Generic Site System' ), 'sanitize' => 'preset_key', 'default' => 'cafe_moxie', 'preset_participation' => true ),
			'site_kicker' => array( 'label' => 'Brand kicker', 'description' => 'Displayed short brand label used in templates.', 'group' => 'storefront_defaults', 'type' => 'text', 'sanitize' => 'text', 'default' => 'Cafe Moxie', 'preset_participation' => true ),
			'featured_tools_count' => array( 'label' => 'Featured tools on home', 'group' => 'storefront_defaults', 'type' => 'number', 'sanitize' => 'int_range', 'min' => 1, 'max' => 12, 'default' => 3 ),
			'archive_items_per_page' => array( 'label' => 'Archive items per page', 'group' => 'storefront_defaults', 'type' => 'number', 'sanitize' => 'int_range', 'min' => 3, 'max' => 24, 'default' => 9 ),
			'show_archive_filters' => array( 'label' => 'Show archive filters', 'description' => 'Adds a compact filter bar to the Edge Tool archive.', 'group' => 'storefront_defaults', 'type' => 'checkbox', 'sanitize' => 'bool', 'default' => 1 ),
			'refresh_mode' => array( 'label' => 'Starter page refresh mode', 'description' => 'Safe mode protects edited pages.', 'group' => 'page_template_defaults', 'type' => 'select', 'allowed_values' => array( 'safe' => 'Safe (create if missing)', 'overwrite' => 'Overwrite existing pages' ), 'sanitize' => 'choice', 'default' => 'safe' ),
			'load_google_fonts' => array( 'label' => 'Load Google Fonts', 'description' => 'Disable if fonts are self-hosted.', 'group' => 'global_design_tokens', 'type' => 'checkbox', 'sanitize' => 'bool', 'default' => 1 ),
			'enable_motion' => array( 'label' => 'Enable motion accents', 'group' => 'global_design_tokens', 'type' => 'checkbox', 'sanitize' => 'bool', 'default' => 1, 'class_output' => 'cm-motion-{value}' ),
			'logo_width' => array( 'label' => 'Brand mark width (px)', 'group' => 'global_design_tokens', 'type' => 'number', 'sanitize' => 'int_range', 'min' => 120, 'max' => 640, 'default' => 320, 'css_var' => '--moxie-logo-width', 'css_unit' => 'px' ),
			'header_height' => array( 'label' => 'Header minimum height (px)', 'group' => 'global_design_tokens', 'type' => 'number', 'sanitize' => 'int_range', 'min' => 60, 'max' => 160, 'default' => 82, 'css_var' => '--moxie-header-height', 'css_unit' => 'px' ),
			'section_max_width' => array( 'label' => 'Section max width (px)', 'group' => 'global_design_tokens', 'type' => 'number', 'sanitize' => 'int_range', 'min' => 960, 'max' => 1600, 'default' => 1220 ),
			'outer_wrapper_gutter' => array( 'label' => 'Outer wrapper gutter (px)', 'group' => 'global_design_tokens', 'type' => 'number', 'sanitize' => 'int_range', 'min' => 12, 'max' => 72, 'default' => 32 ),
			'hero_min_height' => array( 'label' => 'Hero min height (px)', 'group' => 'global_design_tokens', 'type' => 'number', 'sanitize' => 'int_range', 'min' => 420, 'max' => 980, 'default' => 640 ),
			'header_vertical_padding' => array( 'label' => 'Header vertical padding (px)', 'group' => 'global_design_tokens', 'type' => 'number', 'sanitize' => 'int_range', 'min' => 4, 'max' => 40, 'default' => 10 ),
			'footer_vertical_padding' => array( 'label' => 'Footer vertical padding (px)', 'group' => 'global_design_tokens', 'type' => 'number', 'sanitize' => 'int_range', 'min' => 8, 'max' => 56, 'default' => 20 ),
			'hero_section_spacing' => array( 'label' => 'Hero spacing rhythm (px)', 'group' => 'global_design_tokens', 'type' => 'number', 'sanitize' => 'int_range', 'min' => 12, 'max' => 96, 'default' => 28 ),
			'glow_intensity' => array( 'label' => 'Glow intensity', 'group' => 'global_design_tokens', 'type' => 'number', 'sanitize' => 'float_range', 'min' => 0.2, 'max' => 2.5, 'default' => 1.0 ),
			'border_radius' => array( 'label' => 'Corner radius (px)', 'group' => 'global_design_tokens', 'type' => 'number', 'sanitize' => 'int_range', 'min' => 8, 'max' => 40, 'default' => 22, 'css_var' => '--moxie-radius', 'css_unit' => 'px' ),
			'button_scale' => array( 'label' => 'Button scale', 'group' => 'global_design_tokens', 'type' => 'number', 'sanitize' => 'float_range', 'min' => 0.8, 'max' => 1.4, 'default' => 1.0, 'css_var' => '--moxie-button-scale' ),
			'mobile_heading_scale' => array( 'label' => 'Mobile heading scale', 'group' => 'global_design_tokens', 'type' => 'number', 'sanitize' => 'float_range', 'min' => 0.85, 'max' => 1.2, 'default' => 1.0, 'css_var' => '--moxie-mobile-heading-scale' ),
			'mobile_layout_mode' => array( 'label' => 'Mobile layout mode', 'group' => 'page_template_defaults', 'type' => 'select', 'allowed_values' => array( 'stacked' => 'Stacked sections', 'balanced' => 'Balanced sections' ), 'sanitize' => 'choice', 'default' => 'stacked', 'class_output' => 'cm-mobile-{value}' ),
			'card_image_ratio' => array( 'label' => 'Card image ratio', 'group' => 'component_defaults', 'type' => 'text', 'sanitize' => 'ratio', 'default' => '16:10' ),
			'card_grid_density' => array( 'label' => 'Card + grid density', 'group' => 'component_defaults', 'type' => 'select', 'allowed_values' => array( 'compact' => 'Compact', 'comfortable' => 'Comfortable', 'airy' => 'Airy' ), 'sanitize' => 'choice', 'default' => 'comfortable', 'class_output' => 'cm-density-{value}' ),
			'template_surface' => array( 'label' => 'Template surface style', 'group' => 'component_defaults', 'type' => 'select', 'allowed_values' => array( 'panel' => 'Panel (default)', 'soft' => 'Soft surface', 'flat' => 'Flat surface' ), 'sanitize' => 'choice', 'default' => 'panel', 'class_output' => 'cm-surface-{value}' ),
			'content_max_width' => array( 'label' => 'Long-form content max width (px)', 'group' => 'component_defaults', 'type' => 'number', 'sanitize' => 'int_range', 'min' => 540, 'max' => 980, 'default' => 760, 'css_var' => '--moxie-content-max', 'css_unit' => 'px' ),
			'content_band_max_width' => array( 'label' => 'Full-width band max width (px)', 'group' => 'component_defaults', 'type' => 'number', 'sanitize' => 'int_range', 'min' => 860, 'max' => 1600, 'default' => 1120 ),
			'grid_gap' => array( 'label' => 'Grid gap baseline (px)', 'group' => 'component_defaults', 'type' => 'number', 'sanitize' => 'int_range', 'min' => 8, 'max' => 48, 'default' => 20 ),
			'panel_padding' => array( 'label' => 'Panel/card padding baseline (px)', 'group' => 'component_defaults', 'type' => 'number', 'sanitize' => 'int_range', 'min' => 14, 'max' => 48, 'default' => 24 ),
			'archive_rail_max_width' => array( 'label' => 'Archive rail max width (px)', 'group' => 'component_defaults', 'type' => 'number', 'sanitize' => 'int_range', 'min' => 900, 'max' => 1800, 'default' => 1320 ),
			'single_rail_max_width' => array( 'label' => 'Single template rail max width (px)', 'group' => 'component_defaults', 'type' => 'number', 'sanitize' => 'int_range', 'min' => 860, 'max' => 1600, 'default' => 1180 ),
			'archive_columns' => array( 'label' => 'Desktop archive columns', 'group' => 'component_defaults', 'type' => 'number', 'sanitize' => 'int_range', 'min' => 1, 'max' => 4, 'default' => 3, 'css_var' => '--moxie-archive-cols' ),
			'tablet_columns' => array( 'label' => 'Tablet archive columns', 'group' => 'component_defaults', 'type' => 'number', 'sanitize' => 'int_range', 'min' => 1, 'max' => 3, 'default' => 2, 'css_var' => '--moxie-tablet-cols' ),
			'layout_behavior' => array( 'label' => 'Default layout behavior', 'group' => 'page_template_defaults', 'type' => 'select', 'allowed_values' => array( 'balanced' => 'Balanced split', 'single_column' => 'Single column focus', 'showcase_split' => 'Showcase split' ), 'sanitize' => 'choice', 'default' => 'balanced', 'class_output' => 'cm-layout-{value}' ),
			'home_hero_layout' => array( 'label' => 'Home hero layout mode', 'group' => 'page_template_defaults', 'type' => 'select', 'allowed_values' => $layout_modes, 'sanitize' => 'choice', 'default' => 'balanced_two_column' ),
			'home_story_layout' => array( 'label' => 'Home story layout mode', 'group' => 'page_template_defaults', 'type' => 'select', 'allowed_values' => $layout_modes, 'sanitize' => 'choice', 'default' => 'media_right_split' ),
			'home_trust_layout' => array( 'label' => 'Home trust layout mode', 'group' => 'page_template_defaults', 'type' => 'select', 'allowed_values' => $layout_modes, 'sanitize' => 'choice', 'default' => 'balanced_two_column' ),
			'home_featured_layout' => array( 'label' => 'Home featured layout mode', 'group' => 'page_template_defaults', 'type' => 'select', 'allowed_values' => $layout_modes, 'sanitize' => 'choice', 'default' => 'stacked_on_tablet' ),
			'about_intro_layout' => array( 'label' => 'About intro layout mode', 'group' => 'page_template_defaults', 'type' => 'select', 'allowed_values' => $layout_modes, 'sanitize' => 'choice', 'default' => 'media_right_split' ),
			'about_calibrate_layout' => array( 'label' => 'About calibration layout mode', 'group' => 'page_template_defaults', 'type' => 'select', 'allowed_values' => $layout_modes, 'sanitize' => 'choice', 'default' => 'balanced_two_column' ),
			'responsive_breakpoint_mode' => array( 'label' => 'Responsive breakpoint mode', 'group' => 'page_template_defaults', 'type' => 'select', 'allowed_values' => array( 'early_stack' => 'Early stack (tablet-first)', 'balanced' => 'Balanced', 'late_stack' => 'Late stack (wider layouts persist)' ), 'sanitize' => 'choice', 'default' => 'balanced', 'class_output' => 'cm-breakpoints-{value}' ),
			'page_section_density' => array( 'label' => 'Page section spacing', 'group' => 'page_template_defaults', 'type' => 'select', 'allowed_values' => array( 'compact' => 'Compact', 'comfortable' => 'Comfortable', 'airy' => 'Airy' ), 'sanitize' => 'choice', 'default' => 'comfortable' ),
			'site_density_preset' => array( 'label' => 'Site density preset', 'description' => 'Sets the base rhythm for spacing and padding across plugin-managed surfaces.', 'group' => 'page_template_defaults', 'type' => 'select', 'allowed_values' => array( 'compact' => 'Compact', 'comfortable' => 'Comfortable', 'airy' => 'Airy' ), 'sanitize' => 'choice', 'default' => 'comfortable', 'class_output' => 'cm-site-density-{value}' ),
			'display_logo_image' => array( 'label' => 'Brand mark image URL', 'group' => 'storefront_defaults', 'type' => 'url', 'sanitize' => 'url_or_path', 'default' => '' ),
			'enable_managed_header_footer' => array( 'label' => 'Enable plugin-managed header/footer', 'description' => 'When enabled, the plugin can generate and route template part output through managed presets.', 'group' => 'storefront_defaults', 'type' => 'checkbox', 'sanitize' => 'bool', 'default' => 0 ),
			'header_footer_preset' => array( 'label' => 'Header/footer preset', 'group' => 'storefront_defaults', 'type' => 'select', 'allowed_values' => array( 'counter' => 'Counter bar', 'utility' => 'Utility split' ), 'sanitize' => 'choice', 'default' => 'counter' ),
			'header_brand_treatment' => array( 'label' => 'Header brand treatment', 'group' => 'storefront_defaults', 'type' => 'select', 'allowed_values' => array( 'logo_and_name' => 'Logo + name', 'name_only' => 'Name only', 'logo_only' => 'Logo only' ), 'sanitize' => 'choice', 'default' => 'logo_and_name' ),
			'header_nav_source' => array( 'label' => 'Header navigation source', 'group' => 'storefront_defaults', 'type' => 'select', 'allowed_values' => array( 'primary_navigation' => 'Primary menu location', 'footer_navigation' => 'Footer menu location', 'no_navigation' => 'No navigation block' ), 'sanitize' => 'choice', 'default' => 'primary_navigation' ),
			'header_cta_label' => array( 'label' => 'Header CTA label', 'group' => 'storefront_defaults', 'type' => 'text', 'sanitize' => 'text', 'default' => 'Browse Tools' ),
			'header_cta_url' => array( 'label' => 'Header CTA URL', 'group' => 'storefront_defaults', 'type' => 'text', 'sanitize' => 'url_or_path', 'default' => '/edge-tools/' ),
			'home_hero_image' => array( 'label' => 'Home hero image URL', 'group' => 'storefront_defaults', 'type' => 'url', 'sanitize' => 'url_or_path', 'default' => '' ),
			'home_story_image' => array( 'label' => 'Home story image URL', 'group' => 'storefront_defaults', 'type' => 'url', 'sanitize' => 'url_or_path', 'default' => '' ),
			'about_story_image' => array( 'label' => 'About story image URL', 'group' => 'storefront_defaults', 'type' => 'url', 'sanitize' => 'url_or_path', 'default' => '' ),
			'home_primary_cta' => array( 'label' => 'Home primary CTA label', 'group' => 'storefront_defaults', 'type' => 'text', 'sanitize' => 'text', 'default' => 'Browse the Counter' ),
			'home_primary_url' => array( 'label' => 'Home primary CTA URL', 'group' => 'storefront_defaults', 'type' => 'text', 'sanitize' => 'url_or_path', 'default' => '/edge-tools/' ),
			'home_secondary_cta' => array( 'label' => 'Home secondary CTA label', 'group' => 'storefront_defaults', 'type' => 'text', 'sanitize' => 'text', 'default' => 'See What Runs Local' ),
			'home_secondary_url' => array( 'label' => 'Home secondary CTA URL', 'group' => 'storefront_defaults', 'type' => 'text', 'sanitize' => 'url_or_path', 'default' => '/about/' ),
			'about_primary_cta' => array( 'label' => 'About CTA label', 'group' => 'storefront_defaults', 'type' => 'text', 'sanitize' => 'text', 'default' => 'See the Tool Counter' ),
			'about_primary_url' => array( 'label' => 'About CTA URL', 'group' => 'storefront_defaults', 'type' => 'text', 'sanitize' => 'url_or_path', 'default' => '/edge-tools/' ),
			'footer_content_primary' => array( 'label' => 'Footer primary content', 'group' => 'storefront_defaults', 'type' => 'text', 'sanitize' => 'text', 'default' => 'Built for practical operators and local-first teams.' ),
			'footer_content_secondary' => array( 'label' => 'Footer secondary content', 'group' => 'storefront_defaults', 'type' => 'text', 'sanitize' => 'text', 'default' => 'Need deployment help? Contact your implementation partner.' ),
			'footer_utility_copy' => array( 'label' => 'Footer utility copy', 'group' => 'storefront_defaults', 'type' => 'text', 'sanitize' => 'text', 'default' => 'Reliable defaults. Editable everywhere.' ),
			'footer_copy' => array( 'label' => 'Footer copy', 'group' => 'storefront_defaults', 'type' => 'text', 'sanitize' => 'text', 'default' => 'Tools for people who actually do the work.' ),
			'footer_meta_behavior' => array( 'label' => 'Footer legal/meta behavior', 'group' => 'storefront_defaults', 'type' => 'select', 'allowed_values' => array( 'copyright' => 'Auto copyright line', 'custom' => 'Use custom legal text', 'hidden' => 'Hide legal/meta line' ), 'sanitize' => 'choice', 'default' => 'copyright' ),
			'footer_legal_text' => array( 'label' => 'Footer custom legal text', 'group' => 'storefront_defaults', 'type' => 'text', 'sanitize' => 'text', 'default' => '' ),
			'color_ink' => array( 'label' => 'Ink', 'group' => 'global_design_tokens', 'type' => 'color', 'sanitize' => 'color', 'default' => '#05070D', 'css_var' => '--moxie-ink' ),
			'color_midnight' => array( 'label' => 'Midnight', 'group' => 'global_design_tokens', 'type' => 'color', 'sanitize' => 'color', 'default' => '#0A1020', 'css_var' => '--moxie-midnight' ),
			'color_oil' => array( 'label' => 'Oil', 'group' => 'global_design_tokens', 'type' => 'color', 'sanitize' => 'color', 'default' => '#121A2B', 'css_var' => '--moxie-oil' ),
			'color_gunmetal' => array( 'label' => 'Gunmetal', 'group' => 'global_design_tokens', 'type' => 'color', 'sanitize' => 'color', 'default' => '#2A3140', 'css_var' => '--moxie-gunmetal' ),
			'color_cyan' => array( 'label' => 'Cyan', 'group' => 'global_design_tokens', 'type' => 'color', 'sanitize' => 'color', 'default' => '#35D6FF', 'css_var' => '--moxie-cyan' ),
			'color_teal' => array( 'label' => 'Teal', 'group' => 'global_design_tokens', 'type' => 'color', 'sanitize' => 'color', 'default' => '#1FB8B2', 'css_var' => '--moxie-teal' ),
			'color_arcade' => array( 'label' => 'Arcade', 'group' => 'global_design_tokens', 'type' => 'color', 'sanitize' => 'color', 'default' => '#5AA9FF', 'css_var' => '--moxie-arcade' ),
			'color_magenta' => array( 'label' => 'Magenta', 'group' => 'global_design_tokens', 'type' => 'color', 'sanitize' => 'color', 'default' => '#FF4FA3', 'css_var' => '--moxie-magenta' ),
			'color_amber' => array( 'label' => 'Amber', 'group' => 'global_design_tokens', 'type' => 'color', 'sanitize' => 'color', 'default' => '#F6B35C', 'css_var' => '--moxie-amber' ),
			'color_gold' => array( 'label' => 'Gold', 'group' => 'global_design_tokens', 'type' => 'color', 'sanitize' => 'color', 'default' => '#D9A441', 'css_var' => '--moxie-gold' ),
			'color_cream' => array( 'label' => 'Cream', 'group' => 'global_design_tokens', 'type' => 'color', 'sanitize' => 'color', 'default' => '#F5E6C8', 'css_var' => '--moxie-cream' ),
			'color_rust' => array( 'label' => 'Rust', 'group' => 'global_design_tokens', 'type' => 'color', 'sanitize' => 'color', 'default' => '#8E5A3C', 'css_var' => '--moxie-rust' ),
			'color_signal_red' => array( 'label' => 'Signal red', 'group' => 'global_design_tokens', 'type' => 'color', 'sanitize' => 'color', 'default' => '#E64848', 'css_var' => '--moxie-signal-red' ),
			'color_warning_yellow' => array( 'label' => 'Warning yellow', 'group' => 'global_design_tokens', 'type' => 'color', 'sanitize' => 'color', 'default' => '#F2C94C', 'css_var' => '--moxie-warning-yellow' ),
			'show_home_story' => array( 'label' => 'Show Home story section', 'group' => 'page_template_defaults', 'type' => 'checkbox', 'sanitize' => 'bool', 'default' => 1 ),
			'show_home_trust' => array( 'label' => 'Show Home trust sections', 'group' => 'page_template_defaults', 'type' => 'checkbox', 'sanitize' => 'bool', 'default' => 1 ),
			'show_home_featured' => array( 'label' => 'Show Home featured tools section', 'group' => 'page_template_defaults', 'type' => 'checkbox', 'sanitize' => 'bool', 'default' => 1 ),
			'show_home_closing' => array( 'label' => 'Show Home closing CTA section', 'group' => 'page_template_defaults', 'type' => 'checkbox', 'sanitize' => 'bool', 'default' => 1 ),
			'show_about_values' => array( 'label' => 'Show About value cards section', 'group' => 'page_template_defaults', 'type' => 'checkbox', 'sanitize' => 'bool', 'default' => 1 ),
			'show_about_calibrate' => array( 'label' => 'Show About final calibration section', 'group' => 'page_template_defaults', 'type' => 'checkbox', 'sanitize' => 'bool', 'default' => 1 ),
			'composed_page_template' => array( 'label' => 'Composed page template', 'group' => 'page_template_defaults', 'type' => 'select', 'allowed_values' => self::composed_page_templates(), 'sanitize' => 'choice', 'default' => 'conversion' ),
			'composed_page_slug' => array( 'label' => 'Default generated page slug', 'group' => 'page_template_defaults', 'type' => 'text', 'sanitize' => 'slug', 'default' => 'services' ),
			'composed_page_title' => array( 'label' => 'Default generated page title', 'group' => 'page_template_defaults', 'type' => 'text', 'sanitize' => 'text', 'default' => 'Services' ),
		);

		foreach ( self::preset_defaults_map() as $preset_defaults ) {
			foreach ( $preset_defaults as $key => $value ) {
				if ( isset( $registry[ $key ] ) && ! array_key_exists( 'preset_participation', $registry[ $key ] ) ) {
					$registry[ $key ]['preset_participation'] = true;
				}
			}
		}
		return $registry;
	}

	public static function brand_presets() {
		return array(
			'cafe_moxie' => array(
				'label' => 'Cafe Moxie',
				'archive_intro' => "Cafe Moxie is Fabled Sky's worker-first software counter for local tools and compute-backed utilities built for real digital work.",
			),
			'neutral' => array(
				'label' => 'Generic Site System',
				'archive_intro' => 'This catalog highlights practical tools and utilities with clear execution, compatibility, and trust details.',
			),
		);
	}

	public static function brand_profile() {
		$s = self::settings();
		$presets = self::brand_presets();
		$key = $s['brand_preset'] ?? 'cafe_moxie';
		$profile = $presets[ $key ] ?? $presets['cafe_moxie'];
		$profile['name'] = ! empty( $s['site_kicker'] ) ? $s['site_kicker'] : $profile['label'];
		return $profile;
	}

	public static function admin_menu() {
		add_menu_page(
			'Site System Kit',
			'Site System Kit',
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
		$out = array();
		$input = is_array( $input ) ? $input : array();
		foreach ( self::settings_registry() as $key => $field ) {
			$value = $input[ $key ] ?? $d[ $key ];
			switch ( $field['sanitize'] ) {
				case 'bool':
					$out[ $key ] = empty( $input[ $key ] ) ? 0 : 1;
					break;
				case 'int_range':
					$out[ $key ] = max( intval( $field['min'] ), min( intval( $field['max'] ), intval( $value ) ) );
					break;
				case 'float_range':
					$out[ $key ] = max( floatval( $field['min'] ), min( floatval( $field['max'] ), floatval( $value ) ) );
					break;
				case 'color':
					$sanitized = sanitize_hex_color( $value );
					$out[ $key ] = $sanitized ? $sanitized : $d[ $key ];
					break;
				case 'url_or_path':
					$out[ $key ] = self::sanitize_url_or_path( $value );
					break;
				case 'choice':
					$allowed = array_keys( $field['allowed_values'] ?? array() );
					$sanitized_value = sanitize_key( $value );
					$out[ $key ] = in_array( $sanitized_value, $allowed, true ) ? $sanitized_value : $d[ $key ];
					break;
				case 'preset_key':
					$sanitized_value = sanitize_key( $value );
					$out[ $key ] = isset( self::brand_presets()[ $sanitized_value ] ) ? $sanitized_value : 'cafe_moxie';
					break;
				case 'slug':
					$out[ $key ] = sanitize_title( $value );
					break;
				case 'ratio':
					$out[ $key ] = preg_match( '/^\s*\d+(\.\d+)?\s*:\s*\d+(\.\d+)?\s*$/', (string) $value ) ? sanitize_text_field( $value ) : $d[ $key ];
					break;
				case 'text':
				default:
					$out[ $key ] = sanitize_text_field( $value );
					break;
			}
		}
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

	private static function select_row( $key, $label, $choices, $hint = '' ) {
		$s = self::settings();
		$current = (string) ( $s[ $key ] ?? '' );
		echo '<tr><th scope="row"><label for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label></th><td>';
		echo '<select id="' . esc_attr( $key ) . '" name="' . esc_attr( self::OPTION ) . '[' . esc_attr( $key ) . ']">';
		foreach ( $choices as $value => $choice_label ) {
			echo '<option value="' . esc_attr( $value ) . '" ' . selected( $current, (string) $value, false ) . '>' . esc_html( $choice_label ) . '</option>';
		}
		echo '</select>';
		if ( $hint ) {
			echo '<p class="description">' . esc_html( $hint ) . '</p>';
		}
		echo '</td></tr>';
	}

	private static function render_registry_row( $key, $field ) {
		$label = $field['label'] ?? $key;
		$hint = $field['description'] ?? '';
		$type = $field['type'] ?? 'text';
		if ( 'checkbox' === $type ) {
			self::checkbox_row( $key, $label, $hint );
			return;
		}
		if ( 'color' === $type ) {
			self::color_row( $key, $label );
			return;
		}
		if ( 'select' === $type ) {
			self::select_row( $key, $label, $field['allowed_values'] ?? array(), $hint );
			return;
		}
		if ( 'number' === $type || 'url' === $type || 'text' === $type ) {
			self::text_row( $key, $label, $type, $hint );
			return;
		}
		self::text_row( $key, $label, 'text', $hint );
	}

	private static function render_settings_group( $group_key ) {
		$groups = self::settings_groups();
		$registry = self::settings_registry();
		if ( empty( $groups[ $group_key ] ) ) {
			return;
		}
		echo '<h2>' . esc_html( $groups[ $group_key ]['label'] ) . '</h2>';
		echo '<p class="description">' . esc_html( $groups[ $group_key ]['description'] ) . '</p>';
		echo '<table class="form-table" role="presentation">';
		foreach ( $groups[ $group_key ]['keys'] as $key ) {
			if ( isset( $registry[ $key ] ) ) {
				self::render_registry_row( $key, $registry[ $key ] );
			}
		}
		echo '</table>';
	}

	private static function admin_tabs() {
		return array(
			'overview' => array(
				'label' => 'Overview + Setup',
				'description' => 'Current plugin-managed setup state and high-impact actions.',
				'groups' => array(),
			),
			'brand_presets' => array(
				'label' => 'Brand + Presets',
				'description' => 'Brand identity, preset baseline, and storefront media/copy defaults.',
				'groups' => array( 'storefront_defaults' ),
			),
			'layout_spacing' => array(
				'label' => 'Layout + Spacing',
				'description' => 'Global spacing and responsive layout defaults for sections and templates.',
				'groups' => array( 'global_design_tokens', 'component_defaults', 'page_template_defaults' ),
			),
			'header_footer' => array(
				'label' => 'Header + Footer',
				'description' => 'Header/footer treatment controls currently managed from visual + storefront settings.',
				'groups' => array( 'global_design_tokens', 'storefront_defaults' ),
				'keys' => array( 'enable_managed_header_footer', 'header_footer_preset', 'header_brand_treatment', 'header_nav_source', 'header_height', 'logo_width', 'display_logo_image', 'header_cta_label', 'header_cta_url', 'footer_content_primary', 'footer_content_secondary', 'footer_copy', 'footer_utility_copy', 'footer_meta_behavior', 'footer_legal_text' ),
			),
			'pages_templates' => array(
				'label' => 'Pages + Templates',
				'description' => 'Starter/composed page defaults and section-template behavior.',
				'groups' => array( 'page_template_defaults' ),
			),
			'content_modules' => array(
				'label' => 'Content Modules',
				'description' => 'Composed section defaults and reusable module readiness for generated pages.',
				'groups' => array( 'page_template_defaults', 'component_defaults' ),
				'keys' => array( 'composed_page_template', 'composed_page_slug', 'composed_page_title', 'template_surface', 'content_max_width', 'content_band_max_width' ),
			),
			'storefront' => array(
				'label' => 'Storefront Behavior',
				'description' => 'Archive/query behavior and tool-catalog presentation defaults.',
				'groups' => array( 'storefront_defaults', 'component_defaults' ),
				'keys' => array( 'featured_tools_count', 'archive_items_per_page', 'show_archive_filters', 'archive_columns', 'tablet_columns', 'card_grid_density', 'card_image_ratio' ),
			),
		);
	}

	private static function current_admin_tab() {
		$tabs = self::admin_tabs();
		$requested = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'overview';
		return isset( $tabs[ $requested ] ) ? $requested : 'overview';
	}

	private static function get_tab_fields( $tab_key ) {
		$tabs = self::admin_tabs();
		$groups = self::settings_groups();
		$registry = self::settings_registry();
		if ( empty( $tabs[ $tab_key ] ) ) {
			return array();
		}
		$keys = array();
		foreach ( (array) ( $tabs[ $tab_key ]['groups'] ?? array() ) as $group_key ) {
			if ( empty( $groups[ $group_key ]['keys'] ) ) {
				continue;
			}
			$keys = array_merge( $keys, $groups[ $group_key ]['keys'] );
		}
		if ( ! empty( $tabs[ $tab_key ]['keys'] ) ) {
			$keys = $tabs[ $tab_key ]['keys'];
		}
		$keys = array_values( array_unique( $keys ) );
		return array_values(
			array_filter(
				$keys,
				static function( $key ) use ( $registry ) {
					return isset( $registry[ $key ] );
				}
			)
		);
	}

	private static function render_tab_fields( $tab_key ) {
		$registry = self::settings_registry();
		$keys = self::get_tab_fields( $tab_key );
		if ( empty( $keys ) ) {
			return;
		}
		echo '<table class="form-table" role="presentation">';
		foreach ( $keys as $key ) {
			self::render_registry_row( $key, $registry[ $key ] );
		}
		echo '</table>';
	}

	private static function render_status_summary() {
		$s = self::settings();
		$front_page_id = (int) get_option( 'page_on_front' );
		$front_page_title = $front_page_id ? get_the_title( $front_page_id ) : '';
		$starter_pages = array( 'home', 'about', 'browse-the-counter', 'how-it-works', 'who-its-for', 'trust-faq' );
		$existing_starters = 0;
		foreach ( $starter_pages as $starter_slug ) {
			if ( get_page_by_path( $starter_slug ) ) {
				$existing_starters++;
			}
		}
		$summary = array(
			'Active preset' => self::brand_profile()['label'],
			'Starter page state' => sprintf( '%1$d/6 canonical starter pages detected', $existing_starters ),
			'Composed page default' => sprintf( '%s (%s)', $s['composed_page_title'], $s['composed_page_template'] ),
			'Header/footer status' => ! empty( $s['enable_managed_header_footer'] ) ? 'Managed header/footer enabled' : sprintf( 'Theme-managed (header height %dpx)', (int) $s['header_height'] ),
			'Front page setup' => $front_page_id ? sprintf( 'Assigned: %s', $front_page_title ) : 'No static front page assigned',
			'Content module readiness' => ! empty( self::composed_sections() ) ? 'Composed sections available' : 'No composed sections registered',
		);
		echo '<div class="notice notice-info"><p><strong>System summary</strong> — Current plugin-managed state.</p></div>';
		echo '<table class="widefat striped" style="max-width:980px"><tbody>';
		foreach ( $summary as $label => $value ) {
			echo '<tr><th style="width:240px">' . esc_html( $label ) . '</th><td>' . esc_html( $value ) . '</td></tr>';
		}
		echo '</tbody></table>';
	}

	private static function setup_notice_from_request() {
		if ( empty( $_GET['cm_setup_notice'] ) ) {
			return '';
		}
		return sanitize_text_field( wp_unslash( $_GET['cm_setup_notice'] ) );
	}

	private static function presentation_state_rows() {
		$s = self::settings();
		$front_page_id = (int) get_option( 'page_on_front' );
		$show_on_front = get_option( 'show_on_front' );
		$home_page = get_page_by_path( 'home' );
		$starter_pages = array( 'home', 'about', 'browse-the-counter', 'how-it-works', 'who-its-for', 'trust-faq' );
		$existing_starters = 0;
		foreach ( $starter_pages as $starter_slug ) {
			if ( get_page_by_path( $starter_slug ) ) {
				$existing_starters++;
			}
		}
		$nav_locations = get_nav_menu_locations();
		$primary_menu_id = (int) ( $nav_locations['primary'] ?? 0 );
		$has_logo = has_custom_logo() || ! empty( $s['display_logo_image'] );
		$header_part = self::find_template_part( 'cm-site-kit-header' );
		$footer_part = self::find_template_part( 'cm-site-kit-footer' );
		$module = self::content_modules()['edge_tool'] ?? array();
		$archive_query = new WP_Query(
			array(
				'post_type' => sanitize_key( $module['post_type'] ?? 'edge_tool' ),
				'post_status' => 'publish',
				'posts_per_page' => 1,
				'fields' => 'ids',
				'no_found_rows' => true,
			)
		);
		$storefront_ready = $archive_query->have_posts();
		wp_reset_postdata();
		$assign_home_url = $home_page ? wp_nonce_url( admin_url( 'admin-post.php?action=cafe_moxie_assign_front_page&page_id=' . intval( $home_page->ID ) ), 'cafe_moxie_assign_front_page' ) : '';
		$rows = array(
			array(
				'label' => 'Front page assignment',
				'status' => ( 'page' === $show_on_front && $front_page_id > 0 ) ? 'Ready' : 'Needs attention',
				'detail' => ( 'page' === $show_on_front && $front_page_id > 0 ) ? sprintf( 'Static front page set to: %s.', get_the_title( $front_page_id ) ) : 'Site is not configured to use a static front page.',
				'actions' => array_filter(
					array(
						$assign_home_url ? '<a class="button button-small" href="' . esc_url( $assign_home_url ) . '">Assign Home as front page</a>' : '',
						'<a class="button-link" href="' . esc_url( admin_url( 'options-reading.php' ) ) . '">Open Reading settings</a>',
					)
				),
			),
			array(
				'label' => 'Key starter pages',
				'status' => ( 6 === $existing_starters ) ? 'Ready' : 'Needs attention',
				'detail' => sprintf( '%1$d of 6 canonical starter pages exist.', $existing_starters ),
				'actions' => array(
					'<a class="button-link" href="' . esc_url( admin_url( 'admin.php?page=cafe-moxie-site-kit#generation-actions' ) ) . '">Run starter page generation</a>',
					'<a class="button-link" href="' . esc_url( admin_url( 'edit.php?post_type=page' ) ) . '">Open Pages list</a>',
				),
			),
			array(
				'label' => 'Header/footer assignment',
				'status' => ( ! empty( $s['enable_managed_header_footer'] ) && $header_part && $footer_part ) ? 'Ready' : 'Needs attention',
				'detail' => ( ! empty( $s['enable_managed_header_footer'] ) && $header_part && $footer_part ) ? 'Managed header/footer is enabled and generated template parts exist.' : 'Managed header/footer is disabled or generated parts are missing.',
				'actions' => array(
					'<a class="button-link" href="' . esc_url( admin_url( 'admin.php?page=cafe-moxie-site-kit&tab=header_footer' ) ) . '">Open Header + Footer tab</a>',
					'<a class="button-link" href="' . esc_url( admin_url( 'site-editor.php?path=%2Fwp_template_part%2Fall' ) ) . '">Open Site Editor template parts</a>',
				),
			),
			array(
				'label' => 'Navigation readiness',
				'status' => $primary_menu_id > 0 ? 'Ready' : 'Needs attention',
				'detail' => $primary_menu_id > 0 ? 'Primary navigation location has an assigned menu.' : 'Primary navigation location is not assigned yet.',
				'actions' => array(
					'<a class="button-link" href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">Open Navigation menus</a>',
				),
			),
			array(
				'label' => 'Logo/media readiness',
				'status' => $has_logo ? 'Ready' : 'Needs attention',
				'detail' => $has_logo ? 'Brand logo configured via custom logo or plugin logo URL.' : 'No logo configured yet for header branding.',
				'actions' => array(
					'<a class="button-link" href="' . esc_url( admin_url( 'admin.php?page=cafe-moxie-site-kit&tab=header_footer' ) ) . '">Open brand image controls</a>',
					'<a class="button-link" href="' . esc_url( admin_url( 'customize.php?autofocus%5Bcontrol%5D=custom_logo' ) ) . '">Open Customizer logo control</a>',
				),
			),
			array(
				'label' => 'Archive/storefront readiness',
				'status' => $storefront_ready ? 'Ready' : 'Needs attention',
				'detail' => $storefront_ready ? 'Edge Tool content exists for storefront/archive rendering.' : 'No published Edge Tool entries found for archive storefront views.',
				'actions' => array(
					'<a class="button-link" href="' . esc_url( admin_url( 'edit.php?post_type=edge_tool' ) ) . '">Open Edge Tools</a>',
				),
			),
		);
		return $rows;
	}

	private static function render_presentation_state_panel() {
		$notice = self::setup_notice_from_request();
		if ( ! empty( $notice ) ) {
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html( $notice ) . '</p></div>';
		}
		echo '<h2>Presentation setup state</h2>';
		echo '<p class="description">Use this panel as the primary control console for routine presentation setup. It reports live state and links directly to core screens when needed.</p>';
		echo '<table class="widefat striped" style="max-width:1100px"><thead><tr><th style="width:220px">Area</th><th style="width:140px">Status</th><th>Details + actions</th></tr></thead><tbody>';
		foreach ( self::presentation_state_rows() as $row ) {
			$status_class = 'Ready' === $row['status'] ? 'cm-state-ready' : 'cm-state-needs-attention';
			echo '<tr><th>' . esc_html( $row['label'] ) . '</th><td><strong class="' . esc_attr( $status_class ) . '">' . esc_html( $row['status'] ) . '</strong></td><td><p>' . esc_html( $row['detail'] ) . '</p>';
			if ( ! empty( $row['actions'] ) ) {
				echo '<div class="cm-admin-action-cluster">' . implode( '', $row['actions'] ) . '</div>';
			}
			echo '</td></tr>';
		}
		echo '</tbody></table>';
	}

	private static function render_generation_actions() {
		$url = wp_nonce_url( admin_url( 'admin-post.php?action=cafe_moxie_create_starter_pages' ), 'cafe_moxie_create_starter_pages' );
		$preset_url = wp_nonce_url( admin_url( 'admin-post.php?action=cafe_moxie_apply_preset&preset=cafe_moxie' ), 'cafe_moxie_apply_preset' );
		$polished_setup_url = wp_nonce_url( admin_url( 'admin-post.php?action=cafe_moxie_polished_setup' ), 'cafe_moxie_polished_setup' );
		$compose_action = admin_url( 'admin-post.php' );
		?>
		<hr id="generation-actions" />
		<h2>Generation + Assignment Actions</h2>
		<p>These actions create or refresh content. They are separate from normal settings save actions.</p>
		<div class="cm-admin-action-cluster">
			<a class="button button-primary" href="<?php echo esc_url( $polished_setup_url ); ?>">Run One-Click Polished Setup</a>
			<a class="button button-secondary" href="<?php echo esc_url( $preset_url ); ?>">Apply Cafe Moxie polished defaults</a>
			<a class="button button-secondary" href="<?php echo esc_url( $url ); ?>">Generate / Refresh Cafe Moxie Starter Set</a>
			<?php $template_part_url = wp_nonce_url( admin_url( 'admin-post.php?action=cafe_moxie_generate_template_parts' ), 'cafe_moxie_generate_template_parts' ); ?>
			<a class="button button-secondary" href="<?php echo esc_url( $template_part_url ); ?>">Generate / Refresh Managed Header + Footer</a>
		</div>
		<h3>Generate Composed Page</h3>
		<form method="post" action="<?php echo esc_url( $compose_action ); ?>">
			<?php wp_nonce_field( 'cafe_moxie_generate_composed_page' ); ?>
			<input type="hidden" name="action" value="cafe_moxie_generate_composed_page" />
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="cm-compose-title">Page title</label></th>
					<td><input id="cm-compose-title" name="page_title" type="text" class="regular-text" value="<?php echo esc_attr( self::settings()['composed_page_title'] ); ?>" required /></td>
				</tr>
				<tr>
					<th scope="row"><label for="cm-compose-slug">Page slug</label></th>
					<td><input id="cm-compose-slug" name="page_slug" type="text" class="regular-text" value="<?php echo esc_attr( self::settings()['composed_page_slug'] ); ?>" required /></td>
				</tr>
				<tr>
					<th scope="row"><label for="cm-compose-template">Template preset</label></th>
					<td>
						<select id="cm-compose-template" name="template_key">
							<?php foreach ( self::composed_page_templates() as $key => $label ) : ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php selected( self::settings()['composed_page_template'], $key ); ?>><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row">Sections</th>
					<td>
						<?php foreach ( self::composed_sections() as $section_key => $section ) : ?>
							<label style="display:block;margin-bottom:6px;">
								<input type="checkbox" name="sections[]" value="<?php echo esc_attr( $section_key ); ?>" checked />
								<?php echo esc_html( $section['label'] ); ?>
							</label>
						<?php endforeach; ?>
						<p class="description">Pick the sections you want included. If none are selected, the template preset defaults are used.</p>
					</td>
				</tr>
			</table>
			<?php submit_button( 'Generate Page from Sections', 'secondary', 'submit', false ); ?>
		</form>
		<?php
	}

	public static function layout_mode_choices() {
		return array(
			'single_column'      => 'Single column',
			'balanced_two_column'=> 'Balanced two-column',
			'media_left_split'   => 'Media left / text right',
			'media_right_split'  => 'Text left / media right',
			'stacked_on_tablet'  => 'Stacked on tablet',
			'full_width_band'    => 'Full-width content band',
		);
	}

	public static function section_layout_classes( $setting_key, $fallback = 'balanced_two_column', $extra = '' ) {
		$s = self::settings();
		$mode = sanitize_key( $s[ $setting_key ] ?? $fallback );
		$map = array(
			'single_column'       => 'cm-layout--single-column',
			'balanced_two_column' => 'cm-layout--balanced-two-column',
			'media_left_split'    => 'cm-layout--media-left-split',
			'media_right_split'   => 'cm-layout--media-right-split',
			'stacked_on_tablet'   => 'cm-layout--balanced-two-column cm-layout--stacked-on-tablet',
			'full_width_band'     => 'cm-layout--single-column cm-layout--full-width-content-band',
		);
		$mode_classes = $map[ $mode ] ?? $map[ $fallback ];
		$classes = trim( 'cm-layout cm-section ' . $mode_classes . ' ' . $extra );
		return preg_replace( '/\s+/', ' ', $classes );
	}

	public static function settings_page() {
		$tabs = self::admin_tabs();
		$current_tab = self::current_admin_tab();
		$starter_summary = self::starter_generation_summary_from_request();
		$template_part_summary = self::template_part_generation_summary_from_request();
		?>
		<div class="wrap">
			<h1>Site System Kit</h1>
			<p>Use this control console to manage brand presets, layout defaults, composed page behavior, and storefront presentation using native WordPress settings patterns.</p>
			<style>.cm-admin-action-cluster{display:flex;flex-wrap:wrap;gap:10px;align-items:center}.cm-admin-action-cluster .button{margin:0}.cm-admin-action-cluster .button-link{display:inline-flex;align-items:center;min-height:34px}</style>
			<?php if ( ! empty( $starter_summary ) ) : ?>
				<div class="notice notice-info is-dismissible"><p><?php echo esc_html( $starter_summary ); ?></p></div>
			<?php endif; ?>
			<?php if ( ! empty( $template_part_summary ) ) : ?>
				<div class="notice notice-info is-dismissible"><p><?php echo esc_html( $template_part_summary ); ?></p></div>
			<?php endif; ?>
			<h2 class="nav-tab-wrapper">
				<?php foreach ( $tabs as $tab_key => $tab ) : ?>
					<?php
					$tab_url = add_query_arg(
						array(
							'page' => 'cafe-moxie-site-kit',
							'tab'  => $tab_key,
						),
						admin_url( 'admin.php' )
					);
					?>
					<a href="<?php echo esc_url( $tab_url ); ?>" class="nav-tab <?php echo $tab_key === $current_tab ? 'nav-tab-active' : ''; ?>"><?php echo esc_html( $tab['label'] ); ?></a>
				<?php endforeach; ?>
			</h2>
			<p class="description" style="margin:12px 0 18px;"><?php echo esc_html( $tabs[ $current_tab ]['description'] ); ?></p>
			<?php if ( 'overview' === $current_tab ) : ?>
				<?php self::render_presentation_state_panel(); ?>
				<?php self::render_status_summary(); ?>
				<?php self::render_generation_actions(); ?>
				<?php return; ?>
			<?php endif; ?>
			<form method="post" action="options.php">
				<?php settings_fields( 'cafe_moxie_site_kit_group' ); ?>
				<?php self::render_tab_fields( $current_tab ); ?>
				<?php submit_button( 'Save settings', 'primary', 'submit', false ); ?>
			</form>
			<?php self::render_generation_actions(); ?>
		</div>
		<?php
	}

	public static function body_classes( $classes ) {
		$s = self::settings();
		$classes[] = 'cm-moxie-site';
		foreach ( self::settings_registry() as $key => $field ) {
			if ( empty( $field['class_output'] ) ) {
				continue;
			}
			$value = isset( $s[ $key ] ) ? $s[ $key ] : $field['default'];
			if ( 'bool' === ( $field['sanitize'] ?? '' ) ) {
				$value = ! empty( $value ) ? 'on' : 'off';
			}
			$classes[] = sanitize_html_class( str_replace( '{value}', (string) $value, $field['class_output'] ) );
		}
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
		$density_scale = 1.0;
		if ( 'compact' === ( $s['site_density_preset'] ?? '' ) ) {
			$density_scale = 0.88;
		} elseif ( 'airy' === ( $s['site_density_preset'] ?? '' ) ) {
			$density_scale = 1.18;
		}
		$section_gap = (int) round( intval( $s['hero_section_spacing'] ) * $density_scale ) . 'px';
		if ( 'compact' === ( $s['page_section_density'] ?? '' ) ) {
			$section_gap = (int) round( intval( $s['hero_section_spacing'] ) * 0.82 * $density_scale ) . 'px';
		} elseif ( 'airy' === ( $s['page_section_density'] ?? '' ) ) {
			$section_gap = (int) round( intval( $s['hero_section_spacing'] ) * 1.34 * $density_scale ) . 'px';
		}
		$card_gap = (int) round( intval( $s['grid_gap'] ) * $density_scale ) . 'px';
		$card_padding = (int) round( intval( $s['panel_padding'] ) * $density_scale );
		if ( 'compact' === ( $s['card_grid_density'] ?? '' ) ) {
			$card_gap = (int) round( intval( $s['grid_gap'] ) * 0.72 * $density_scale ) . 'px';
			$card_padding = (int) round( intval( $s['panel_padding'] ) * 0.75 * $density_scale );
		} elseif ( 'airy' === ( $s['card_grid_density'] ?? '' ) ) {
			$card_gap = (int) round( intval( $s['grid_gap'] ) * 1.32 * $density_scale ) . 'px';
			$card_padding = (int) round( intval( $s['panel_padding'] ) * 1.24 * $density_scale );
		}
		$archive_breakpoint = 1160;
		$tablet_breakpoint = 920;
		if ( 'early_stack' === ( $s['responsive_breakpoint_mode'] ?? '' ) ) {
			$archive_breakpoint = 1280;
			$tablet_breakpoint = 1024;
		} elseif ( 'late_stack' === ( $s['responsive_breakpoint_mode'] ?? '' ) ) {
			$archive_breakpoint = 1080;
			$tablet_breakpoint = 860;
		}
		$text_rgba = self::hex_to_rgba( $s['color_cream'], 0.94 );
		$muted_rgba = self::hex_to_rgba( $s['color_cream'], 0.72 );
		$line_rgba = self::hex_to_rgba( $s['color_cyan'], 0.18 );
		$line_soft_rgba = self::hex_to_rgba( $s['color_cyan'], 0.12 );
		$cyan_glow_rgba = self::hex_to_rgba( $s['color_cyan'], 0.22 );
		$amber_glow_rgba = self::hex_to_rgba( $s['color_amber'], 0.20 );
		$magenta_glow_rgba = self::hex_to_rgba( $s['color_magenta'], 0.18 );
		$registry_vars = self::css_variable_map( $s );

		return "
:root{
{$registry_vars}
--moxie-text:{$text_rgba};
--moxie-muted:{$muted_rgba};
--moxie-line:{$line_rgba};
--moxie-line-soft:{$line_soft_rgba};
--moxie-wrap:min({$s['section_max_width']}px,calc(100% - {$s['outer_wrapper_gutter']}px));
--moxie-band-wrap:min({$s['content_band_max_width']}px,calc(100% - " . max( 16, intval( $s['outer_wrapper_gutter'] ) - 8 ) . "px));
--moxie-archive-rail:min({$s['archive_rail_max_width']}px,calc(100% - {$s['outer_wrapper_gutter']}px));
--moxie-single-rail:min({$s['single_rail_max_width']}px,calc(100% - {$s['outer_wrapper_gutter']}px));
--moxie-card-ratio:{$ratio};
--moxie-section-gap:{$section_gap};
--moxie-card-gap:{$card_gap};
--moxie-card-pad:{$card_padding}px;
--moxie-header-pad:" . (int) round( intval( $s['header_vertical_padding'] ) * $density_scale ) . "px;
--moxie-footer-pad:" . (int) round( intval( $s['footer_vertical_padding'] ) * $density_scale ) . "px;
--moxie-glow-cyan:0 0 " . ( 18 * $glow ) . "px {$cyan_glow_rgba};
--moxie-glow-amber:0 0 " . ( 20 * $glow ) . "px {$amber_glow_rgba};
--moxie-glow-magenta:0 0 " . ( 18 * $glow ) . "px {$magenta_glow_rgba};
--moxie-shadow:0 18px 48px rgba(0,0,0,.40);
}
html{scroll-behavior:smooth}
*,*::before,*::after{box-sizing:border-box}
body.cm-moxie-site{background:radial-gradient(circle at top right, rgba(31,184,178,.08), transparent 24%),radial-gradient(circle at top left, rgba(53,214,255,.09), transparent 18%),linear-gradient(180deg,var(--moxie-ink) 0%,var(--moxie-midnight) 42%,var(--moxie-oil) 100%);color:var(--moxie-text)}
body.cm-moxie-site,body.cm-moxie-site button,body.cm-moxie-site input,body.cm-moxie-site select,body.cm-moxie-site textarea,body.cm-moxie-site .wp-block-button__link{font-family:'IBM Plex Sans',system-ui,sans-serif}
body.cm-moxie-site h1,body.cm-moxie-site h2,body.cm-moxie-site h3,body.cm-moxie-site h4,body.cm-moxie-site h5,body.cm-moxie-site h6,body.cm-moxie-site .cm-sign-title,body.cm-moxie-site .wp-block-site-title{font-family:'Chathura','IBM Plex Sans Condensed','IBM Plex Sans',sans-serif;color:var(--moxie-cream);font-weight:700;letter-spacing:.02em;line-height:.92;overflow-wrap:anywhere}
body.cm-moxie-site h1{font-size:56px}body.cm-moxie-site h2{font-size:50px}body.cm-moxie-site h3{font-size:44px}body.cm-moxie-site h4{font-size:40px}body.cm-moxie-site h5{font-size:36px}body.cm-moxie-site h6{font-size:32px}
body.cm-moxie-site p,body.cm-moxie-site li,body.cm-moxie-site td,body.cm-moxie-site th{color:var(--moxie-text);overflow-wrap:anywhere}
body.cm-moxie-site a{color:var(--moxie-cyan)}
body.cm-moxie-site a:hover,body.cm-moxie-site a:focus{color:var(--moxie-amber)}
body.cm-moxie-site .wp-site-blocks > header,body.cm-moxie-site .wp-block-template-part{position:relative;z-index:10}
body.cm-moxie-site .wp-block-template-part .wp-block-group{min-height:var(--moxie-header-height)}
body.cm-moxie-site .wp-block-site-logo img,body.cm-moxie-site .custom-logo{width:min(var(--moxie-logo-width),100%);height:auto}
body.cm-moxie-site .wp-block-navigation a{color:var(--moxie-cream);text-decoration:none}
body.cm-moxie-site .wp-block-navigation a:hover{color:var(--moxie-cyan)}
.cm-managed-header,.cm-managed-footer{width:var(--moxie-wrap);margin-inline:auto}
.cm-managed-header{padding:var(--moxie-header-pad) 0}
.cm-managed-header.is-counter{border-bottom:1px solid var(--moxie-line)}
.cm-managed-header.is-utility{background:rgba(5,7,13,.25);border-radius:0 0 12px 12px}
.cm-managed-header__shell{display:grid;grid-template-columns:minmax(180px,1fr) minmax(200px,1.2fr) minmax(160px,auto);align-items:center;gap:18px}
.cm-managed-header__region,.cm-managed-header__brand,.cm-managed-header__nav,.cm-managed-header__actions{min-width:0}
.cm-managed-header__region--left{justify-self:start}
.cm-managed-header__region--center{justify-self:center;width:100%}
.cm-managed-header__region--right{justify-self:end}
.cm-managed-header__nav .wp-block-navigation{justify-content:center;row-gap:8px}
.cm-managed-header__actions{display:flex;justify-content:flex-end}
.cm-action-cluster,.cm-action-buttons{display:flex;flex-wrap:wrap;gap:12px;align-items:center}
.cm-action-cluster > *,.cm-action-buttons > *{min-width:0}
.cm-action-buttons .wp-block-button{margin:0}
.cm-managed-footer{margin-top:32px;padding:var(--moxie-footer-pad) 0;border-top:1px solid var(--moxie-line)}
.cm-managed-footer__columns{gap:18px;align-items:flex-start}
.cm-managed-footer__column > *{margin-block:0 10px}
.cm-managed-footer__meta{margin-top:8px;color:var(--moxie-muted)}
.cm-wrap{width:var(--moxie-wrap);margin-inline:auto}
.cm-archive-page .cm-wrap{width:var(--moxie-archive-rail)}
.cm-single-page .cm-wrap{width:var(--moxie-single-rail)}
.cm-wrap img,.cm-wrap svg,.cm-wrap video,.cm-wrap iframe{max-width:100%}
.cm-section{margin-block:var(--moxie-section-gap)}
.cm-panel,.cm-card,.is-style-cm-panel{position:relative;overflow:hidden;padding:var(--moxie-card-pad);border-radius:var(--moxie-radius);border:1px solid var(--moxie-line);background:linear-gradient(180deg,rgba(18,26,43,.96),rgba(10,16,32,.98));box-shadow:var(--moxie-shadow)}
.cm-panel:before,.cm-card:before,.is-style-cm-panel:before{content:'';position:absolute;inset:-1px auto auto -1px;width:180px;height:180px;background:radial-gradient(circle at top left, rgba(53,214,255,.14), transparent 70%);pointer-events:none}
.cm-brand-mark{display:inline-flex;align-items:center;gap:14px;margin-bottom:14px}
.cm-brand-mark img{display:block;width:min(var(--moxie-logo-width),100%);height:auto;filter:drop-shadow(0 0 12px rgba(53,214,255,.18))}
.cm-brand-mark__fallback{display:inline-flex;align-items:center;gap:12px;min-height:40px;padding:10px 16px;border-radius:999px;border:1px solid rgba(53,214,255,.24);background:rgba(53,214,255,.06);font-size:12px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--moxie-cyan)}
.cm-badge,.cm-chip,.cm-status{display:inline-flex;align-items:center;gap:8px;min-height:34px;padding:7px 12px;border-radius:999px;border:1px solid rgba(53,214,255,.22);background:rgba(53,214,255,.06);color:var(--moxie-cyan);font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;max-width:100%;white-space:normal;line-height:1.3}
.cm-chip{letter-spacing:0;text-transform:none;color:var(--moxie-cream)}
.cm-status--warm{color:var(--moxie-amber);border-color:rgba(246,179,92,.28);background:rgba(246,179,92,.08);box-shadow:var(--moxie-glow-amber)}
.cm-status--compute{color:var(--moxie-magenta);border-color:rgba(255,79,163,.28);background:rgba(255,79,163,.08);box-shadow:var(--moxie-glow-magenta)}
.cm-status--alert{color:var(--moxie-signal-red);border-color:rgba(230,72,72,.32);background:rgba(230,72,72,.08)}
.cm-chip-list{display:flex;flex-wrap:wrap;gap:10px}
.cm-button,.wp-element-button,.wp-block-button__link{display:inline-flex;align-items:center;justify-content:center;gap:10px;min-height:48px;padding:calc(12px * var(--moxie-button-scale)) calc(18px * var(--moxie-button-scale));border-radius:14px;border:1px solid rgba(53,214,255,.24);background:linear-gradient(180deg,rgba(53,214,255,.18),rgba(31,184,178,.16));color:var(--moxie-cream)!important;text-decoration:none;font-weight:700;box-shadow:var(--moxie-glow-cyan);text-align:center;white-space:normal;word-break:break-word}
.cm-button--secondary{background:rgba(246,179,92,.08)!important;color:var(--moxie-amber)!important;border-color:rgba(246,179,92,.28)!important;box-shadow:var(--moxie-glow-amber)}
.cm-button--subtle{background:rgba(255,255,255,.04)!important;border-color:rgba(255,255,255,.10)!important;box-shadow:none}
.cm-sign-title{position:relative;display:inline-block;max-width:100%;padding-bottom:6px}
.cm-sign-title:after{content:'';position:absolute;left:0;bottom:0;width:82px;height:3px;border-radius:999px;background:linear-gradient(90deg,var(--moxie-cyan),var(--moxie-teal));box-shadow:var(--moxie-glow-cyan)}
.cm-subtle{color:var(--moxie-muted);line-height:1.65}
.cm-note{margin-top:16px;padding:14px 16px;border-radius:16px;border:1px solid rgba(246,179,92,.24);background:rgba(246,179,92,.08);color:var(--moxie-cream)}
.cm-grid-2{display:grid;grid-template-columns:1.1fr .9fr;gap:var(--moxie-card-gap)}
.cm-grid-3{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:var(--moxie-card-gap)}
.cm-grid-4{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:var(--moxie-card-gap)}
.cm-layout{display:grid;grid-template-columns:minmax(0,1fr);gap:var(--moxie-card-gap)}
.cm-layout > *,.cm-grid-2 > *,.cm-grid-3 > *,.cm-grid-4 > *,.cm-meta-grid > *,.cm-stat-band > *,.cm-before-after > *{min-width:0}
.cm-layout--single-column{grid-template-columns:minmax(0,1fr)}
.cm-layout--balanced-two-column{grid-template-columns:repeat(2,minmax(0,1fr))}
.cm-layout--media-left-split{grid-template-columns:.95fr 1.05fr}
.cm-layout--media-right-split{grid-template-columns:1.05fr .95fr}
.cm-layout--full-width-content-band{width:var(--moxie-band-wrap);margin-inline:auto}
.cm-layout--full-width-content-band > *{max-width:var(--moxie-content-max)}
.cm-copy-prose p,.cm-copy-prose li{max-width:var(--moxie-content-max)}
.cm-hero{min-height:{$s['hero_min_height']}px;align-items:stretch;padding-block:var(--moxie-section-gap)}
.cm-placeholder{min-height:320px;display:flex;flex-direction:column;justify-content:center;align-items:flex-start;padding:28px;border:1px dashed rgba(53,214,255,.28);border-radius:18px;background:rgba(53,214,255,.04)}
.cm-placeholder-title{font-size:clamp(34px,4.8vw,50px)}
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
.cm-media-frame--hero-wide{aspect-ratio:16/10;min-height:340px}
.cm-media-frame--split-standard{aspect-ratio:4/3;min-height:280px}
.cm-media-frame--split-tall{aspect-ratio:3/4;min-height:320px}
.cm-media-frame--square-card{aspect-ratio:1/1;min-height:220px}
.cm-media-frame--portrait-focus{aspect-ratio:3/4;min-height:380px}
.cm-media-frame--logo-signage{aspect-ratio:5/3;min-height:140px}
.cm-media-frame--placeholder{display:flex;flex-direction:column;justify-content:center;align-items:flex-start;padding:28px}
.cm-media-frame--portrait{min-height:360px}
.cm-media-frame--ratio:before{content:'';display:block;padding-top:var(--moxie-card-ratio)}
.cm-media-frame--ratio > img,.cm-media-frame--ratio > .cm-media-frame__placeholder{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.cm-media-image{display:block;width:100%;height:100%;object-fit:cover}
.cm-media-frame__placeholder{display:flex;align-items:center;justify-content:center;padding:18px;color:var(--moxie-muted);text-align:center;background:radial-gradient(circle at top left, rgba(53,214,255,.10), transparent 58%)}
.cm-tool-card{display:flex;flex-direction:column;gap:16px;height:100%}
.cm-tool-card__body{display:flex;flex-direction:column;gap:14px;flex:1}
.cm-tool-card__meta{display:flex;flex-wrap:wrap;gap:8px}
.cm-tool-card__footer{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;margin-top:auto}
.cm-tool-card__title-link{color:var(--moxie-cream);text-decoration:none}
.cm-tool-card__tagline{color:var(--moxie-amber);font-weight:700;line-height:1.4}
.cm-tool-card__trust{font-size:13px;max-width:30ch}
.cm-price{font-weight:700;color:var(--moxie-amber)}
.cm-eyebrow{font-size:12px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--moxie-cyan);margin-bottom:10px}
.cm-archive-page,.cm-single-page{padding-block:28px 52px}
.cm-archive-tools{display:grid;grid-template-columns:repeat(var(--moxie-archive-cols),minmax(0,1fr));gap:var(--moxie-card-gap);margin-top:22px}
.cm-filter-bar{display:grid;grid-template-columns:2fr repeat(5,minmax(0,1fr));gap:12px;align-items:end}
.cm-filter-bar label{display:block;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--moxie-cyan);margin-bottom:6px}
.cm-filter-bar input,.cm-filter-bar select{width:100%;min-height:44px;border-radius:14px;border:1px solid rgba(53,214,255,.16);background:rgba(5,7,13,.40);color:var(--moxie-cream);padding:10px 12px}
.cm-filter-actions{display:flex;gap:12px;flex-wrap:wrap;align-items:center;margin-top:12px}
.cm-filter-toggle{display:inline-flex;align-items:center;gap:8px;color:var(--moxie-cream);font-weight:700}
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
.cm-video-wrap iframe{display:block;width:100%;aspect-ratio:16/9;min-height:300px;border:0;border-radius:calc(var(--moxie-radius) - 6px)}
.cm-footer-line{display:inline-block;color:var(--moxie-cream);padding-top:8px;border-top:1px solid rgba(53,214,255,.18)}
.cm-query-summary{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:18px}
.cm-single-hero-tagline{color:var(--moxie-amber);font-weight:700;margin-top:6px;line-height:1.4}
.cm-single-hero-actions{display:flex;flex-wrap:wrap;gap:12px;margin-top:18px}
.cm-chip-list--tight{margin-bottom:14px}
.cm-chip-list--top{margin-top:16px}
.cm-sign-title--stack{margin-top:20px}
.cm-sign-title--substack{margin-top:18px}
.cm-kv-grid--spaced,.cm-meta-grid--spaced,.cm-gallery--spaced,.cm-section-stack--spaced{margin-top:18px}
.cm-subtle--caption{margin-top:10px}
.cm-pagination{margin-top:28px}
.cm-pagination .page-numbers{display:inline-flex;align-items:center;justify-content:center;min-width:42px;min-height:42px;margin-right:8px;border-radius:12px;border:1px solid rgba(53,214,255,.14);background:rgba(255,255,255,.04);color:var(--moxie-cream);text-decoration:none}
.cm-pagination .page-numbers.current{background:rgba(53,214,255,.12);color:var(--moxie-cyan);box-shadow:var(--moxie-glow-cyan)}
body.cm-motion-on .cm-button,body.cm-motion-on .cm-card,body.cm-motion-on .cm-panel,body.cm-motion-on .cm-tool-card{transition:transform .22s ease, box-shadow .22s ease, border-color .22s ease, background .22s ease}
body.cm-motion-on .cm-button:hover,body.cm-motion-on .cm-button:focus{transform:translateY(-1px)}
body.cm-motion-on .cm-card:hover,body.cm-motion-on .cm-panel:hover{transform:translateY(-2px);border-color:rgba(53,214,255,.28)}
body.cm-motion-on .cm-sign-flicker{animation:cmSignFlicker " . ( $motion ? '6.2s' : '0s' ) . " ease-in-out infinite}
@keyframes cmSignFlicker{0%,100%{opacity:1;filter:drop-shadow(0 0 0 rgba(53,214,255,0))}2%{opacity:.88}4%{opacity:1}48%{opacity:1}50%{opacity:.9}51%{opacity:1}}
body.cm-surface-soft .cm-panel,body.cm-surface-soft .cm-card{background:linear-gradient(180deg,rgba(18,26,43,.80),rgba(10,16,32,.86))}
body.cm-surface-flat .cm-panel,body.cm-surface-flat .cm-card{background:rgba(14,20,33,.94);box-shadow:none}
body.cm-layout-single_column .cm-grid-2{grid-template-columns:1fr}
body.cm-layout-showcase_split .cm-grid-2{grid-template-columns:1fr 1fr}
@media (max-width:{$archive_breakpoint}px){.cm-filter-bar{grid-template-columns:1fr 1fr 1fr}.cm-grid-4,.cm-gallery{grid-template-columns:repeat(2,minmax(0,1fr))}.cm-archive-tools{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media (max-width:{$tablet_breakpoint}px){.cm-grid-2,.cm-grid-3,.cm-stat-band,.cm-meta-grid,.cm-layout--stacked-on-tablet,.cm-layout--media-portrait-focus{grid-template-columns:1fr}.cm-before-after{grid-template-columns:1fr}.cm-filter-bar{grid-template-columns:1fr 1fr}.cm-query-summary{flex-direction:column;align-items:flex-start}.cm-query-summary .cm-chip-list{width:100%}.cm-archive-tools{grid-template-columns:repeat(var(--moxie-tablet-cols),minmax(0,1fr))}.cm-managed-header__shell{grid-template-columns:1fr;gap:14px}.cm-managed-header__region--left,.cm-managed-header__region--center,.cm-managed-header__region--right{justify-self:stretch}.cm-managed-header__nav .wp-block-navigation{justify-content:flex-start}.cm-managed-header__actions{justify-content:flex-start}.cm-managed-footer__columns{display:grid;grid-template-columns:1fr}}
@media (max-width:640px){body.cm-moxie-site h1{font-size:calc(44px * var(--moxie-mobile-heading-scale));line-height:1}body.cm-moxie-site h2{font-size:calc(40px * var(--moxie-mobile-heading-scale));line-height:1.02}body.cm-moxie-site h3{font-size:calc(34px * var(--moxie-mobile-heading-scale));line-height:1.04}.cm-grid-4,.cm-gallery,.cm-kv-grid,.cm-archive-tools,.cm-filter-bar{grid-template-columns:1fr}.cm-meta-row{grid-template-columns:1fr;gap:6px}.cm-video-wrap iframe{min-height:220px}.cm-panel,.cm-card{overflow-wrap:anywhere}.cm-chip,.cm-status,.cm-badge,.cm-button{width:100%}.cm-action-cluster,.cm-action-buttons,.cm-filter-actions{width:100%}}
@media (max-width:640px){body.cm-mobile-balanced .cm-panel,body.cm-mobile-balanced .cm-card{padding:calc(var(--moxie-card-pad) - 4px)}}
";
	}

	private static function css_variable_map( $settings ) {
		$vars = array();
		foreach ( self::settings_registry() as $key => $field ) {
			if ( empty( $field['css_var'] ) ) {
				continue;
			}
			$value = $settings[ $key ] ?? $field['default'];
			$unit = $field['css_unit'] ?? '';
			$vars[] = $field['css_var'] . ':' . $value . $unit . ';';
		}
		return implode( "\n", $vars );
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

		foreach (
			array(
				'home' => 'Cafe Moxie Homepage',
				'about' => 'Cafe Moxie About Page',
				'browse-the-counter' => 'Browse the Counter',
				'how-it-works' => 'How It Works',
				'who-its-for' => 'Who It’s For',
				'trust-faq' => 'Trust + FAQ',
			) as $slug => $title
		) {
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

	public static function content_modules() {
		return array(
			'edge_tool' => array(
				'post_type'                 => 'edge_tool',
				'archive_query_filter'      => 'cafe_moxie_edge_tool_archive_query_args',
				'data_filter'               => 'cafe_moxie_tool_data',
				'data_builder'              => 'edge_tool_data',
				'singular_template'         => 'templates/single-edge_tool.php',
				'archive_template'          => 'templates/archive-edge_tool.php',
				'card_renderer'             => 'render_edge_tool_card',
				'single_section_renderer'   => 'render_edge_tool_single_sections',
				'archive_filters'           => array(
					'tool_type'       => 'Tool Type',
					'workflow_area'   => 'Workflow Area',
					'platform'        => 'Platform',
					'execution_model' => 'Execution Model',
				),
				'field_map' => array(
					'main_media'      => 'hero_image',
					'gallery'         => 'gallery',
					'before_after'    => 'before_after_examples',
					'primary_message' => 'one_line_value',
					'summary'         => 'tool_summary',
					'cta_download'    => 'download_url',
					'cta_compute'     => 'compute_run_url',
				),
				'normalization_callbacks' => array(
					'repeater' => 'flatten_repeater_items',
					'formats'  => 'flatten_format_rows',
					'services' => 'flatten_service_rows',
					'images'   => 'flatten_image_urls',
				),
				'derived_values' => array(
					'execution_mode' => 'derive_execution_mode',
					'trust_cue'      => 'derive_trust_cue',
					'platform_line'  => 'derive_platform_line',
				),
				'archive_card_schema' => array(
					'headline' => 'title',
					'summary'  => 'tool_summary',
					'image'    => 'hero_image',
					'price'    => 'price_display',
					'cta'      => 'View Tool',
				),
				'single_section_order' => array( 'hero', 'details', 'how_it_works', 'compatibility', 'security', 'media_proof', 'additional_notes' ),
				'empty_state_copy' => array(
					'archive_heading' => 'Nothing matched that pass.',
					'archive_body'    => 'Try a wider search or clear the filters. As soon as Edge Tool posts are published, they will render here automatically.',
				),
				'content_label'            => 'Edge Tool',
				'default_archive_headline' => 'Tools for people who actually do the work.',
			),
		);
	}


	/**
	 * Structured, deterministic architecture contract for future AI-assisted editing.
	 *
	 * This exposes only metadata + constraints + allowable operations. It does not
	 * perform model calls and does not mutate state.
	 */
	public static function ai_architecture_contract() {
		$settings_registry = self::settings_registry();
		$setting_examples = array();
		foreach ( $settings_registry as $key => $field ) {
			$setting_examples[ $key ] = $field['default'] ?? '';
		}

		return array(
			'contract_version' => '2026-04-01',
			'deterministic_flow' => array(
				'inspect_current_state',
				'serialize_structured_context',
				'propose_plan_diff',
				'human_review_required',
				'apply_via_safe_actions_only',
				'record_change_log',
			),
			'scope_constraints' => array(
				'allow' => array(
					'settings value proposals constrained by registry rules',
					'page template + section sequence proposals using registered templates/sections',
					'header/footer generation proposals using managed template-part generators',
					'content-module render-plan proposals using module registry metadata',
				),
				'deny' => array(
					'arbitrary PHP/code mutation',
					'raw SQL/direct database writes',
					'freeform page-builder style DOM editing',
					'unsafe overwrite of non-generated pages without explicit opt-in',
				),
			),
			'plan_models' => array(
				'design_token_plan' => array(
					'type' => 'object',
					'description' => 'Proposed changes to token-like settings with strict bounds.',
					'fields' => self::ai_settings_plan_fields( 'global_design_tokens' ),
					'example' => array(
						'changes' => array(
							'section_max_width' => $setting_examples['section_max_width'] ?? 1220,
							'outer_wrapper_gutter' => $setting_examples['outer_wrapper_gutter'] ?? 20,
						),
					),
				),
				'layout_settings_plan' => array(
					'type' => 'object',
					'description' => 'Proposed section/layout defaults constrained to supported modes.',
					'fields' => self::ai_settings_plan_fields( 'page_template_defaults' ),
					'example' => array(
						'changes' => array(
							'home_hero_layout' => $setting_examples['home_hero_layout'] ?? 'balanced_two_column',
							'mobile_layout_mode' => $setting_examples['mobile_layout_mode'] ?? 'stacked',
						),
					),
				),
				'header_footer_plan' => array(
					'type' => 'object',
					'description' => 'Managed header/footer settings and generation intent.',
					'fields' => self::ai_settings_plan_fields( 'storefront_defaults' ),
					'example' => array(
						'changes' => array(
							'enable_managed_header_footer' => $setting_examples['enable_managed_header_footer'] ?? 0,
							'header_cta_label' => $setting_examples['header_cta_label'] ?? 'View Edge Tools',
						),
					),
				),
				'page_template_plan' => array(
					'type' => 'object',
					'description' => 'Template selection + section sequence constrained by registries.',
					'fields' => array(
						'template_key' => array( 'type' => 'string', 'allowed' => array_keys( self::page_template_registry() ) ),
						'sections' => array( 'type' => 'array', 'allowed' => array_keys( self::composed_sections() ) ),
					),
					'example' => array(
						'template_key' => 'conversion',
						'sections' => array( 'hero', 'feature_grid', 'cta_band' ),
					),
				),
				'section_instance_plan' => array(
					'type' => 'object',
					'description' => 'Per-section intent with visibility + layout guardrails.',
					'fields' => self::ai_section_plan_fields(),
					'example' => array(
						'section_key' => 'home_featured',
						'layout_mode' => 'stacked_on_tablet',
						'visibility_setting' => 'show_home_featured',
					),
				),
				'content_module_render_plan' => array(
					'type' => 'object',
					'description' => 'Module-level render intents tied to known module registries.',
					'fields' => self::ai_content_module_plan_fields(),
					'example' => array(
						'module_key' => 'edge_tool',
						'archive_filters' => array( 'tool_type', 'platform' ),
						'single_section_order' => array( 'hero', 'details', 'how_it_works' ),
					),
				),
				'safe_mutation_apply_actions' => array(
					'type' => 'array',
					'description' => 'Actions that may be applied after human review.',
					'allowed_actions' => self::ai_safe_actions(),
				),
			),
			'registries' => array(
				'settings' => self::ai_settings_registry_snapshot(),
				'page_templates' => self::ai_page_template_snapshot(),
				'sections' => self::ai_sections_snapshot(),
				'content_modules' => self::ai_content_module_snapshot(),
			),
		);
	}

	private static function ai_settings_plan_fields( $group_key ) {
		$fields = array();
		foreach ( self::settings_registry() as $key => $field ) {
			if ( $group_key !== (string) ( $field['group'] ?? '' ) ) {
				continue;
			}
			$fields[ $key ] = array(
				'label' => (string) ( $field['label'] ?? $key ),
				'type' => (string) ( $field['type'] ?? 'text' ),
				'default' => $field['default'] ?? '',
				'min' => isset( $field['min'] ) ? (int) $field['min'] : null,
				'max' => isset( $field['max'] ) ? (int) $field['max'] : null,
				'allowed_values' => isset( $field['allowed_values'] ) && is_array( $field['allowed_values'] ) ? array_keys( $field['allowed_values'] ) : array(),
				'description' => (string) ( $field['description'] ?? '' ),
			);
		}
		return $fields;
	}

	private static function ai_settings_registry_snapshot() {
		$registry = array();
		foreach ( self::settings_registry() as $key => $field ) {
			$registry[ $key ] = array(
				'label' => (string) ( $field['label'] ?? $key ),
				'group' => (string) ( $field['group'] ?? '' ),
				'type' => (string) ( $field['type'] ?? 'text' ),
				'sanitize' => (string) ( $field['sanitize'] ?? '' ),
				'min' => isset( $field['min'] ) ? (int) $field['min'] : null,
				'max' => isset( $field['max'] ) ? (int) $field['max'] : null,
				'allowed_values' => isset( $field['allowed_values'] ) && is_array( $field['allowed_values'] ) ? $field['allowed_values'] : array(),
				'default' => $field['default'] ?? '',
				'example' => array( 'key' => $key, 'value' => $field['default'] ?? '' ),
			);
		}
		return $registry;
	}

	private static function ai_page_template_snapshot() {
		$templates = array();
		foreach ( self::page_template_registry() as $key => $template ) {
			$templates[ $key ] = array(
				'label' => (string) ( $template['label'] ?? $key ),
				'purpose' => (string) ( $template['purpose'] ?? '' ),
				'page_types' => isset( $template['page_types'] ) ? (array) $template['page_types'] : array(),
				'sections' => isset( $template['sections'] ) ? (array) $template['sections'] : array(),
				'example' => array(
					'template_key' => $key,
					'sections' => isset( $template['sections'] ) ? (array) $template['sections'] : array(),
				),
			);
		}
		return $templates;
	}

	private static function ai_sections_snapshot() {
		$sections = array();
		foreach ( self::composed_sections() as $key => $section ) {
			$sections[ $key ] = array(
				'label' => (string) ( $section['label'] ?? $key ),
				'purpose' => (string) ( $section['purpose'] ?? '' ),
				'template' => (string) ( $section['template'] ?? '' ),
				'supported_layout_modes' => isset( $section['supported_layout_modes'] ) ? (array) $section['supported_layout_modes'] : array(),
				'visibility_rules' => isset( $section['visibility_rules'] ) ? (array) $section['visibility_rules'] : array(),
				'token_placeholders' => isset( $section['token_placeholders'] ) ? (array) $section['token_placeholders'] : array(),
				'applicable_page_types' => isset( $section['applicable_page_types'] ) ? (array) $section['applicable_page_types'] : array(),
				'example' => array( 'section_key' => $key, 'template' => (string) ( $section['template'] ?? '' ) ),
			);
		}
		return $sections;
	}

	private static function ai_section_plan_fields() {
		return array(
			'section_key' => array( 'type' => 'string', 'allowed' => array_keys( self::composed_sections() ) ),
			'layout_mode' => array( 'type' => 'string', 'allowed' => array( 'single_column', 'balanced_two_column', 'media_left_split', 'media_right_split', 'stacked_on_tablet', 'full_width_band' ) ),
			'visibility_setting' => array( 'type' => 'string', 'description' => 'Optional setting key controlling whether section is rendered.' ),
		);
	}

	private static function ai_content_module_snapshot() {
		$modules = array();
		foreach ( self::content_modules() as $key => $module ) {
			$modules[ $key ] = array(
				'label' => (string) ( $module['content_label'] ?? $key ),
				'post_type' => (string) ( $module['post_type'] ?? '' ),
				'archive_filters' => isset( $module['archive_filters'] ) ? (array) $module['archive_filters'] : array(),
				'field_map' => isset( $module['field_map'] ) ? (array) $module['field_map'] : array(),
				'archive_card_schema' => isset( $module['archive_card_schema'] ) ? (array) $module['archive_card_schema'] : array(),
				'single_section_order' => isset( $module['single_section_order'] ) ? (array) $module['single_section_order'] : array(),
				'example' => array( 'module_key' => $key, 'post_type' => (string) ( $module['post_type'] ?? '' ) ),
			);
		}
		return $modules;
	}

	private static function ai_content_module_plan_fields() {
		return array(
			'module_key' => array( 'type' => 'string', 'allowed' => array_keys( self::content_modules() ) ),
			'archive_filters' => array( 'type' => 'array', 'description' => 'Subset of module archive filter keys.' ),
			'single_section_order' => array( 'type' => 'array', 'description' => 'Ordered single-section keys used by renderer.' ),
		);
	}

	private static function ai_safe_actions() {
		return array(
			'update_settings_via_sanitize_settings',
			'compose_page_content_via_registered_template_sections',
			'create_or_update_page_for_generated_content',
			'generate_or_update_starter_pages',
			'generate_or_update_managed_template_parts',
			'assign_front_page_via_wp_options',
		);
	}

	public static function content_module( $module_key = 'edge_tool' ) {
		$modules = self::content_modules();
		return $modules[ $module_key ] ?? $modules['edge_tool'];
	}

	public static function module_archive_link( $module_key = 'edge_tool' ) {
		$module = self::content_module( $module_key );
		return get_post_type_archive_link( $module['post_type'] );
	}

	public static function module_archive_headline( $module_key = 'edge_tool' ) {
		$module = self::content_module( $module_key );
		return $module['default_archive_headline'];
	}

	public static function module_content_label( $module_key = 'edge_tool' ) {
		$module = self::content_module( $module_key );
		return $module['content_label'] ?? 'Content';
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
		$module = self::content_module( 'edge_tool' );
		$post_type = $module['post_type'];
		$query = new WP_Query(
			array(
				'post_type'      => $post_type,
				'post_status'    => 'publish',
				'posts_per_page' => $count,
				'meta_key'       => 'featured_tool',
				'meta_value'     => '1',
			)
		);

		if ( ! $query->have_posts() ) {
			$query = new WP_Query(
				array(
					'post_type'      => $post_type,
					'post_status'    => 'publish',
					'posts_per_page' => $count,
				)
			);
		}

		ob_start();
		echo '<section class="cm-section">';
		echo '<div class="cm-panel">';
		$brand = self::brand_profile();
		echo '<div class="cm-eyebrow">Featured tools</div>';
		echo '<h2 class="cm-sign-title">Featured tools</h2>';
		echo '<p class="cm-subtle">Built to feel like a storefront, not a plugin pile. Featured tools for ' . esc_html( $brand['name'] ) . ' pull directly from your Edge Tool posts and Secure Custom Fields.</p>';
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

	public static function composed_page_templates() {
		$templates = self::page_template_registry();
		$options = array();
		foreach ( $templates as $key => $template ) {
			$options[ $key ] = (string) ( $template['label'] ?? $key );
		}
		return $options;
	}

	public static function page_template_registry() {
		return array(
			'home' => array(
				'label' => 'Home starter page',
				'purpose' => 'Primary storefront landing page.',
				'page_types' => array( 'starter', 'generated' ),
				'sections' => array( 'home_hero', 'home_story', 'home_value_cards', 'home_trust', 'home_featured', 'home_closing' ),
			),
			'about' => array(
				'label' => 'About starter page',
				'purpose' => 'Brand voice and trust framing.',
				'page_types' => array( 'starter', 'generated' ),
				'sections' => array( 'about_intro', 'about_values', 'about_calibrate' ),
			),
			'browse_counter' => array(
				'label' => 'Browse the Counter',
				'purpose' => 'Counter metaphor and archive hand-off.',
				'page_types' => array( 'starter', 'generated' ),
				'sections' => array( 'browse_counter_intro', 'browse_counter_categories', 'browse_counter_feed', 'browse_counter_cta' ),
			),
			'how_it_works' => array(
				'label' => 'How It Works',
				'purpose' => 'Buy once, pay per task, and hybrid model overview.',
				'page_types' => array( 'starter', 'generated' ),
				'sections' => array( 'how_it_works_intro', 'how_it_works_models', 'how_it_works_credits', 'how_it_works_cta' ),
			),
			'who_its_for' => array(
				'label' => 'Who It’s For',
				'purpose' => 'Audience, traits, and emotional truth framing.',
				'page_types' => array( 'starter', 'generated' ),
				'sections' => array( 'who_its_for_intro', 'who_its_for_traits', 'who_its_for_emotional_truth', 'who_its_for_cta' ),
			),
			'trust_faq' => array(
				'label' => 'Trust + FAQ',
				'purpose' => 'Trust policy and FAQ guidance.',
				'page_types' => array( 'starter', 'generated' ),
				'sections' => array( 'trust_faq_promises', 'trust_faq_commerce', 'trust_faq_product_clarity', 'trust_faq_cta' ),
			),
			'conversion' => array(
				'label' => 'Conversion page (hero → features → CTA)',
				'purpose' => 'Focused conversion path.',
				'page_types' => array( 'generated' ),
				'sections' => array( 'hero', 'feature_grid', 'cta_band' ),
			),
			'story' => array(
				'label' => 'Story page (hero → split story → trust)',
				'purpose' => 'Narrative and social proof layout.',
				'page_types' => array( 'generated' ),
				'sections' => array( 'hero', 'story_split', 'trust_section', 'cta_band' ),
			),
			'catalog' => array(
				'label' => 'Catalog page (hero → product feed → content)',
				'purpose' => 'Product showcase and supporting copy.',
				'page_types' => array( 'generated' ),
				'sections' => array( 'hero', 'product_feed', 'content_section', 'cta_band' ),
			),
		);
	}

	/**
	 * Structured section registry for composed pages.
	 *
	 * This registry is intentionally data-first so future AI-assisted editing can
	 * read/write section plans as arrays before they are rendered into block HTML.
	 */
	public static function composed_sections() {
		return array(
			'home_hero' => array(
				'label' => 'Home hero',
				'purpose' => 'Primary first impression and CTA entry.',
				'template' => 'home_hero',
				'default_copy' => 'Tools for people who actually do the work.',
				'supported_layout_modes' => array( 'single_column', 'balanced_two_column', 'media_left_split', 'media_right_split', 'stacked_on_tablet' ),
				'media_requirements' => array( 'optional' => array( 'home_hero_image' ) ),
				'visibility_rules' => array(),
				'token_placeholders' => array( 'brand_mark', 'brand_name', 'home_primary_cta_label', 'home_primary_cta_url', 'home_secondary_cta_label', 'home_secondary_cta_url', 'home_hero_image_url' ),
				'applicable_page_types' => array( 'home' ),
			),
			'home_story' => array(
				'label' => 'Home story split',
				'purpose' => 'Explain the operating model with optional media.',
				'template' => 'home_story',
				'default_copy' => 'A counter, not a corporation.',
				'supported_layout_modes' => array( 'single_column', 'balanced_two_column', 'media_left_split', 'media_right_split', 'stacked_on_tablet' ),
				'media_requirements' => array( 'optional' => array( 'home_story_image' ) ),
				'visibility_rules' => array( 'setting_key' => 'show_home_story', 'truthy' => true ),
				'token_placeholders' => array( 'home_story_image_url', 'brand_name' ),
				'applicable_page_types' => array( 'home' ),
			),
			'home_value_cards' => array(
				'label' => 'Home value cards',
				'purpose' => 'Introduce buy once / pay per task / hybrid options.',
				'template' => 'home_value_cards',
				'default_copy' => 'Two Ways to Work.',
				'supported_layout_modes' => array( 'single_column', 'balanced_two_column', 'stacked_on_tablet' ),
				'media_requirements' => array(),
				'visibility_rules' => array(),
				'token_placeholders' => array(),
				'applicable_page_types' => array( 'home' ),
			),
			'home_trust' => array(
				'label' => 'Home trust split',
				'purpose' => 'Trust and audience framing.',
				'template' => 'home_trust',
				'default_copy' => 'Built for people who keep everything running.',
				'supported_layout_modes' => array( 'single_column', 'balanced_two_column', 'stacked_on_tablet' ),
				'media_requirements' => array(),
				'visibility_rules' => array( 'setting_key' => 'show_home_trust', 'truthy' => true ),
				'token_placeholders' => array(),
				'applicable_page_types' => array( 'home' ),
			),
			'home_featured' => array(
				'label' => 'Home featured tools',
				'purpose' => 'Surface featured Edge Tool content.',
				'template' => 'home_featured',
				'default_copy' => 'Product Counter.',
				'supported_layout_modes' => array( 'single_column', 'balanced_two_column', 'stacked_on_tablet' ),
				'media_requirements' => array(),
				'visibility_rules' => array( 'setting_key' => 'show_home_featured', 'truthy' => true ),
				'token_placeholders' => array( 'featured_tools_count' ),
				'applicable_page_types' => array( 'home' ),
			),
			'home_closing' => array(
				'label' => 'Home closing CTA',
				'purpose' => 'Final CTA prompt on home.',
				'template' => 'home_closing',
				'default_copy' => 'Your next unfair advantage is probably a small tool.',
				'supported_layout_modes' => array( 'full_width_band', 'single_column' ),
				'media_requirements' => array(),
				'visibility_rules' => array( 'setting_key' => 'show_home_closing', 'truthy' => true ),
				'token_placeholders' => array( 'home_primary_cta_label', 'home_primary_cta_url' ),
				'applicable_page_types' => array( 'home' ),
			),
			'about_intro' => array(
				'label' => 'About intro split',
				'purpose' => 'Narrative intro and brand anchor.',
				'template' => 'about_intro',
				'default_copy' => 'A worker refuge with sharper tools.',
				'supported_layout_modes' => array( 'single_column', 'balanced_two_column', 'media_left_split', 'media_right_split', 'stacked_on_tablet' ),
				'media_requirements' => array( 'optional' => array( 'about_story_image' ) ),
				'visibility_rules' => array(),
				'token_placeholders' => array( 'about_primary_cta_label', 'about_primary_cta_url', 'about_story_image_url', 'brand_name', 'brand_mark' ),
				'applicable_page_types' => array( 'about' ),
			),
			'about_values' => array(
				'label' => 'About value cards',
				'purpose' => 'Voice, visual direction, and commerce model.',
				'template' => 'about_values',
				'default_copy' => 'Core values.',
				'supported_layout_modes' => array( 'single_column', 'stacked_on_tablet' ),
				'media_requirements' => array(),
				'visibility_rules' => array( 'setting_key' => 'show_about_values', 'truthy' => true ),
				'token_placeholders' => array(),
				'applicable_page_types' => array( 'about' ),
			),
			'about_calibrate' => array(
				'label' => 'About final calibration',
				'purpose' => 'Guardrails and final calibration statements.',
				'template' => 'about_calibrate',
				'default_copy' => 'Final calibration.',
				'supported_layout_modes' => array( 'single_column', 'balanced_two_column', 'stacked_on_tablet' ),
				'media_requirements' => array(),
				'visibility_rules' => array( 'setting_key' => 'show_about_calibrate', 'truthy' => true ),
				'token_placeholders' => array(),
				'applicable_page_types' => array( 'about' ),
			),
			'browse_counter_intro' => array(
				'label' => 'Browse counter intro',
				'purpose' => 'Counter metaphor and storefront framing.',
				'template' => 'browse_counter_intro',
				'default_copy' => 'Browse the counter.',
				'supported_layout_modes' => array( 'single_column', 'balanced_two_column', 'stacked_on_tablet' ),
				'media_requirements' => array(),
				'visibility_rules' => array(),
				'token_placeholders' => array( 'brand_name' ),
				'applicable_page_types' => array( 'browse_counter' ),
			),
			'browse_counter_categories' => array(
				'label' => 'Browse counter categories',
				'purpose' => 'Canonical category names from the brand guide.',
				'template' => 'browse_counter_categories',
				'default_copy' => 'Product categories.',
				'supported_layout_modes' => array( 'single_column', 'stacked_on_tablet' ),
				'media_requirements' => array(),
				'visibility_rules' => array(),
				'token_placeholders' => array(),
				'applicable_page_types' => array( 'browse_counter' ),
			),
			'browse_counter_feed' => array(
				'label' => 'Browse counter product feed',
				'purpose' => 'Edge Tool archive CTA and featured feed.',
				'template' => 'browse_counter_feed',
				'default_copy' => 'Browse available tools.',
				'supported_layout_modes' => array( 'single_column' ),
				'media_requirements' => array(),
				'visibility_rules' => array(),
				'token_placeholders' => array( 'featured_tools_count', 'edge_tool_archive_url' ),
				'applicable_page_types' => array( 'browse_counter' ),
			),
			'browse_counter_cta' => array(
				'label' => 'Browse counter CTA',
				'purpose' => 'Close with direct archive hand-off.',
				'template' => 'browse_counter_cta',
				'default_copy' => 'Browse the counter now.',
				'supported_layout_modes' => array( 'full_width_band', 'single_column' ),
				'media_requirements' => array(),
				'visibility_rules' => array(),
				'token_placeholders' => array( 'edge_tool_archive_url' ),
				'applicable_page_types' => array( 'browse_counter' ),
			),
			'how_it_works_intro' => array(
				'label' => 'How it works intro',
				'purpose' => 'Explain model at a glance.',
				'template' => 'how_it_works_intro',
				'default_copy' => 'How it works.',
				'supported_layout_modes' => array( 'single_column' ),
				'media_requirements' => array(),
				'visibility_rules' => array(),
				'token_placeholders' => array(),
				'applicable_page_types' => array( 'how_it_works' ),
			),
			'how_it_works_models' => array(
				'label' => 'How it works models',
				'purpose' => 'Buy once / pay per task / hybrid details.',
				'template' => 'how_it_works_models',
				'default_copy' => 'Two ways to work.',
				'supported_layout_modes' => array( 'single_column', 'stacked_on_tablet' ),
				'media_requirements' => array(),
				'visibility_rules' => array(),
				'token_placeholders' => array(),
				'applicable_page_types' => array( 'how_it_works' ),
			),
			'how_it_works_credits' => array(
				'label' => 'How it works credits',
				'purpose' => 'Compute credits explanation and guardrails.',
				'template' => 'how_it_works_credits',
				'default_copy' => 'Compute credits.',
				'supported_layout_modes' => array( 'single_column' ),
				'media_requirements' => array(),
				'visibility_rules' => array(),
				'token_placeholders' => array(),
				'applicable_page_types' => array( 'how_it_works' ),
			),
			'how_it_works_cta' => array(
				'label' => 'How it works CTA',
				'purpose' => 'Route reader to browse tools.',
				'template' => 'how_it_works_cta',
				'default_copy' => 'Browse and choose a model.',
				'supported_layout_modes' => array( 'full_width_band', 'single_column' ),
				'media_requirements' => array(),
				'visibility_rules' => array(),
				'token_placeholders' => array( 'edge_tool_archive_url' ),
				'applicable_page_types' => array( 'how_it_works' ),
			),
			'who_its_for_intro' => array(
				'label' => 'Who it’s for intro',
				'purpose' => 'Audience framing for workers.',
				'template' => 'who_its_for_intro',
				'default_copy' => 'Who this is for.',
				'supported_layout_modes' => array( 'single_column' ),
				'media_requirements' => array(),
				'visibility_rules' => array(),
				'token_placeholders' => array(),
				'applicable_page_types' => array( 'who_its_for' ),
			),
			'who_its_for_traits' => array(
				'label' => 'Who it’s for traits',
				'purpose' => 'Audience roles and traits.',
				'template' => 'who_its_for_traits',
				'default_copy' => 'Audience traits.',
				'supported_layout_modes' => array( 'single_column', 'stacked_on_tablet' ),
				'media_requirements' => array(),
				'visibility_rules' => array(),
				'token_placeholders' => array(),
				'applicable_page_types' => array( 'who_its_for' ),
			),
			'who_its_for_emotional_truth' => array(
				'label' => 'Who it’s for emotional truth',
				'purpose' => 'Emotional truth and belonging line.',
				'template' => 'who_its_for_emotional_truth',
				'default_copy' => 'Emotional truth.',
				'supported_layout_modes' => array( 'single_column' ),
				'media_requirements' => array(),
				'visibility_rules' => array(),
				'token_placeholders' => array(),
				'applicable_page_types' => array( 'who_its_for' ),
			),
			'who_its_for_cta' => array(
				'label' => 'Who it’s for CTA',
				'purpose' => 'Route audience to tools.',
				'template' => 'who_its_for_cta',
				'default_copy' => 'Find your tools.',
				'supported_layout_modes' => array( 'full_width_band', 'single_column' ),
				'media_requirements' => array(),
				'visibility_rules' => array(),
				'token_placeholders' => array( 'edge_tool_archive_url' ),
				'applicable_page_types' => array( 'who_its_for' ),
			),
			'trust_faq_promises' => array(
				'label' => 'Trust + FAQ promises',
				'purpose' => 'Primary trust commitments.',
				'template' => 'trust_faq_promises',
				'default_copy' => 'Trust promises.',
				'supported_layout_modes' => array( 'single_column' ),
				'media_requirements' => array(),
				'visibility_rules' => array(),
				'token_placeholders' => array(),
				'applicable_page_types' => array( 'trust_faq' ),
			),
			'trust_faq_commerce' => array(
				'label' => 'Trust + FAQ commerce',
				'purpose' => 'Commerce and transparency model.',
				'template' => 'trust_faq_commerce',
				'default_copy' => 'Commerce guardrails.',
				'supported_layout_modes' => array( 'single_column' ),
				'media_requirements' => array(),
				'visibility_rules' => array(),
				'token_placeholders' => array(),
				'applicable_page_types' => array( 'trust_faq' ),
			),
			'trust_faq_product_clarity' => array(
				'label' => 'Trust + FAQ product clarity',
				'purpose' => 'Product-page clarity checklist.',
				'template' => 'trust_faq_product_clarity',
				'default_copy' => 'Product clarity FAQ.',
				'supported_layout_modes' => array( 'single_column' ),
				'media_requirements' => array(),
				'visibility_rules' => array(),
				'token_placeholders' => array(),
				'applicable_page_types' => array( 'trust_faq' ),
			),
			'trust_faq_cta' => array(
				'label' => 'Trust + FAQ CTA',
				'purpose' => 'Close with trust-forward archive hand-off.',
				'template' => 'trust_faq_cta',
				'default_copy' => 'Browse trusted tools.',
				'supported_layout_modes' => array( 'full_width_band', 'single_column' ),
				'media_requirements' => array(),
				'visibility_rules' => array(),
				'token_placeholders' => array( 'edge_tool_archive_url' ),
				'applicable_page_types' => array( 'trust_faq' ),
			),
			'hero' => array(
				'label' => 'Hero',
				'purpose' => 'Default generated page hero.',
				'template' => 'hero',
				'default_copy' => 'Template Composer',
				'supported_layout_modes' => array( 'single_column', 'balanced_two_column', 'media_left_split', 'media_right_split', 'stacked_on_tablet' ),
				'media_requirements' => array(),
				'visibility_rules' => array(),
				'token_placeholders' => array( 'brand_mark', 'brand_name' ),
				'applicable_page_types' => array( 'conversion', 'story', 'catalog' ),
			),
			'story_split' => array(
				'label' => 'Story split',
				'purpose' => 'Default split body section.',
				'template' => 'story_split',
				'default_copy' => 'How Cafe Moxie helps you ship without platform theater.',
				'supported_layout_modes' => array( 'single_column', 'balanced_two_column', 'media_left_split', 'media_right_split', 'stacked_on_tablet' ),
				'media_requirements' => array( 'optional' => array( 'image' ) ),
				'visibility_rules' => array(),
				'token_placeholders' => array( 'story_layout' ),
				'applicable_page_types' => array( 'story' ),
			),
			'feature_grid' => array(
				'label' => 'Feature grid',
				'purpose' => 'Default value blocks.',
				'template' => 'feature_grid',
				'default_copy' => 'Fast setup.',
				'supported_layout_modes' => array( 'single_column', 'balanced_two_column', 'stacked_on_tablet' ),
				'media_requirements' => array(),
				'visibility_rules' => array(),
				'token_placeholders' => array(),
				'applicable_page_types' => array( 'conversion' ),
			),
			'cta_band' => array(
				'label' => 'CTA band',
				'purpose' => 'Default conversion footer.',
				'template' => 'cta_band',
				'default_copy' => 'Move from explanation to action.',
				'supported_layout_modes' => array( 'full_width_band', 'single_column' ),
				'media_requirements' => array(),
				'visibility_rules' => array(),
				'token_placeholders' => array( 'primary_cta_url', 'primary_cta_label' ),
				'applicable_page_types' => array( 'conversion', 'story', 'catalog' ),
			),
			'trust_section' => array(
				'label' => 'Trust section',
				'purpose' => 'Default trust checklist.',
				'template' => 'trust_section',
				'default_copy' => 'We do not train on your client files. We do not trap basic features behind subscriptions.',
				'supported_layout_modes' => array( 'single_column' ),
				'media_requirements' => array(),
				'visibility_rules' => array(),
				'token_placeholders' => array(),
				'applicable_page_types' => array( 'story' ),
			),
			'product_feed' => array(
				'label' => 'Product feed',
				'purpose' => 'Default featured products embed.',
				'template' => 'product_feed',
				'default_copy' => 'Featured tools feed.',
				'supported_layout_modes' => array( 'single_column' ),
				'media_requirements' => array(),
				'visibility_rules' => array(),
				'token_placeholders' => array( 'featured_tools_count' ),
				'applicable_page_types' => array( 'catalog' ),
			),
			'content_section' => array(
				'label' => 'Content section',
				'purpose' => 'Default long-form body section.',
				'template' => 'content_section',
				'default_copy' => 'Document the workflow, tradeoffs, and who this section is for in plain language.',
				'supported_layout_modes' => array( 'single_column' ),
				'media_requirements' => array(),
				'visibility_rules' => array(),
				'token_placeholders' => array(),
				'applicable_page_types' => array( 'catalog' ),
			),
		);
	}

	public static function template_sections( $template_key ) {
		$registry = self::page_template_registry();
		if ( isset( $registry[ $template_key ]['sections'] ) && is_array( $registry[ $template_key ]['sections'] ) ) {
			return $registry[ $template_key ]['sections'];
		}
		return $registry['conversion']['sections'];
	}

	public static function compose_page_content( $section_keys = array(), $args = array() ) {
		$template_key = sanitize_key( $args['template_key'] ?? 'conversion' );
		$requested = is_array( $section_keys ) ? array_values( array_filter( array_map( 'sanitize_key', $section_keys ) ) ) : array();
		$sequence = ! empty( $requested ) ? $requested : self::template_sections( $template_key );
		$sections = self::composed_sections();
		$brand = self::brand_profile();
		$s = self::settings();

		ob_start();
		echo '<!-- wp:group {"className":"cm-wrap","layout":{"type":"constrained"}} --><div class="wp-block-group cm-wrap">';
		foreach ( $sequence as $section_key ) {
			if ( ! isset( $sections[ $section_key ] ) ) {
				continue;
			}
			echo self::render_composed_section( $section_key, $s, $brand );
		}
		echo '</div><!-- /wp:group -->';
		return ob_get_clean();
	}

	private static function composed_section_markup() {
		return array(
			'home_hero' => '<!-- wp:group {"className":"{{home_hero_layout}}","layout":{"type":"default"}} --><div class="wp-block-group {{home_hero_layout}}"><!-- wp:group {"className":"cm-panel cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-copy-prose">{{brand_mark}}<!-- wp:paragraph --><p class="cm-badge">Tools for people who actually do the work.</p><!-- /wp:paragraph --><!-- wp:heading {"level":1,"className":"cm-sign-title"} --><h1 class="wp-block-heading cm-sign-title">Tools for people who actually do the work.</h1><!-- /wp:heading --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">{{brand_name}} is a worker-first software shop: focused desktop tools and compute-backed utilities for repetitive, real-world digital tasks.</p><!-- /wp:paragraph --><!-- wp:buttons {"className":"cm-action-cluster cm-action-buttons cm-action-buttons--composed"} --><div class="wp-block-buttons cm-action-cluster cm-action-buttons cm-action-buttons--composed"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="{{home_primary_cta_url}}">{{home_primary_cta_label}}</a></div><!-- /wp:button --><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button cm-button--secondary" href="{{home_secondary_cta_url}}">{{home_secondary_cta_label}}</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group --><!-- wp:group {"className":"cm-panel","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel">{{home_hero_media}}</div><!-- /wp:group --></div><!-- /wp:group -->',
			'home_story' => '<!-- wp:group {"className":"{{home_story_layout}}","layout":{"type":"default"}} --><div class="wp-block-group {{home_story_layout}}"><!-- wp:group {"className":"cm-panel cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-copy-prose"><!-- wp:paragraph --><p class="cm-eyebrow">What This System Is</p><!-- /wp:paragraph --><!-- wp:heading {"level":2,"className":"cm-sign-title"} --><h2 class="wp-block-heading cm-sign-title">A counter, not a corporation.</h2><!-- /wp:heading --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">This site system showcases small, practical software tools for people whose jobs happen on computers.</p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- wp:group {"className":"cm-panel","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel">{{home_story_media}}</div><!-- /wp:group --></div><!-- /wp:group -->',
			'home_value_cards' => '<!-- wp:group {"className":"cm-grid-3 cm-section","layout":{"type":"default"}} --><div class="wp-block-group cm-grid-3 cm-section"><!-- wp:group {"className":"cm-card","layout":{"type":"constrained"}} --><div class="wp-block-group cm-card"><!-- wp:paragraph --><p class="cm-badge">Two Ways to Work</p><!-- /wp:paragraph --><!-- wp:heading {"level":3,"className":"cm-sign-title"} --><h3 class="wp-block-heading cm-sign-title">Buy Once</h3><!-- /wp:heading --></div><!-- /wp:group --><!-- wp:group {"className":"cm-card","layout":{"type":"constrained"}} --><div class="wp-block-group cm-card"><!-- wp:paragraph --><p class="cm-badge">Two Ways to Work</p><!-- /wp:paragraph --><!-- wp:heading {"level":3,"className":"cm-sign-title"} --><h3 class="wp-block-heading cm-sign-title">Pay Per Task</h3><!-- /wp:heading --></div><!-- /wp:group --><!-- wp:group {"className":"cm-card","layout":{"type":"constrained"}} --><div class="wp-block-group cm-card"><!-- wp:paragraph --><p class="cm-badge">Two Ways to Work</p><!-- /wp:paragraph --><!-- wp:heading {"level":3,"className":"cm-sign-title"} --><h3 class="wp-block-heading cm-sign-title">Hybrid</h3><!-- /wp:heading --></div><!-- /wp:group --></div><!-- /wp:group -->',
			'home_trust' => '<!-- wp:group {"className":"{{home_trust_layout}}","layout":{"type":"default"}} --><div class="wp-block-group {{home_trust_layout}}"><!-- wp:group {"className":"cm-panel cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-copy-prose"><!-- wp:paragraph --><p class="cm-eyebrow">Built for the People Who Keep Everything Running</p><!-- /wp:paragraph --><!-- wp:heading {"level":2,"className":"cm-sign-title"} --><h2 class="wp-block-heading cm-sign-title">For the person everyone asks for help.</h2><!-- /wp:heading --></div><!-- /wp:group --><!-- wp:group {"className":"cm-panel cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-copy-prose"><!-- wp:paragraph --><p class="cm-eyebrow">Workers Should Own the Leverage</p><!-- /wp:paragraph --><!-- wp:heading {"level":2,"className":"cm-sign-title"} --><h2 class="wp-block-heading cm-sign-title">AI is happening. Workers should own the leverage.</h2><!-- /wp:heading --></div><!-- /wp:group --></div><!-- /wp:group -->',
			'home_featured' => '<!-- wp:group {"className":"{{home_featured_layout}}","layout":{"type":"default"}} --><div class="wp-block-group {{home_featured_layout}}"><!-- wp:group {"className":"cm-panel cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-copy-prose"><!-- wp:paragraph --><p class="cm-eyebrow">What We Won\'t Do</p><!-- /wp:paragraph --><!-- wp:list {"className":"cm-trust-list"} --><ul class="cm-trust-list"><li>We won\'t harvest your documents to train models.</li><li>We won\'t gate basic functionality behind a subscription.</li><li>We won\'t pretend AI replaces your judgment.</li></ul><!-- /wp:list --></div><!-- /wp:group --><!-- wp:group {"className":"cm-panel cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-copy-prose"><!-- wp:paragraph --><p class="cm-eyebrow">Product Counter</p><!-- /wp:paragraph --><!-- wp:shortcode -->[cafe_moxie_featured_edge_tools count="{{featured_tools_count}}"]<!-- /wp:shortcode --></div><!-- /wp:group --></div><!-- /wp:group -->',
			'home_closing' => '<!-- wp:group {"className":"cm-panel cm-section","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-section"><!-- wp:paragraph --><p class="cm-eyebrow">Closing CTA</p><!-- /wp:paragraph --><!-- wp:heading {"level":2,"className":"cm-sign-title"} --><h2 class="wp-block-heading cm-sign-title">Your next unfair advantage is probably a small tool.</h2><!-- /wp:heading --><!-- wp:buttons {"className":"cm-action-cluster cm-action-buttons cm-action-buttons--composed"} --><div class="wp-block-buttons cm-action-cluster cm-action-buttons cm-action-buttons--composed"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="{{home_primary_cta_url}}">{{home_primary_cta_label}}</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group -->',
			'about_intro' => '<!-- wp:group {"className":"{{about_intro_layout}}","layout":{"type":"default"}} --><div class="wp-block-group {{about_intro_layout}}"><!-- wp:group {"className":"cm-panel cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-copy-prose">{{brand_mark}}<!-- wp:paragraph --><p class="cm-eyebrow">About {{brand_name}}</p><!-- /wp:paragraph --><!-- wp:heading {"level":1,"className":"cm-sign-title"} --><h1 class="wp-block-heading cm-sign-title">A worker refuge with sharper tools.</h1><!-- /wp:heading --><!-- wp:buttons {"className":"cm-action-cluster cm-action-buttons cm-action-buttons--composed"} --><div class="wp-block-buttons cm-action-cluster cm-action-buttons cm-action-buttons--composed"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="{{about_primary_cta_url}}">{{about_primary_cta_label}}</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group --><!-- wp:group {"className":"cm-panel","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel">{{about_story_media}}</div><!-- /wp:group --></div><!-- /wp:group -->',
			'about_values' => '<!-- wp:group {"className":"cm-grid-3 cm-section","layout":{"type":"default"}} --><div class="wp-block-group cm-grid-3 cm-section"><!-- wp:group {"className":"cm-card","layout":{"type":"constrained"}} --><div class="wp-block-group cm-card"><!-- wp:paragraph --><p class="cm-badge">Voice</p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- wp:group {"className":"cm-card","layout":{"type":"constrained"}} --><div class="wp-block-group cm-card"><!-- wp:paragraph --><p class="cm-badge">Visual Direction</p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- wp:group {"className":"cm-card","layout":{"type":"constrained"}} --><div class="wp-block-group cm-card"><!-- wp:paragraph --><p class="cm-badge">Commerce</p><!-- /wp:paragraph --></div><!-- /wp:group --></div><!-- /wp:group -->',
			'about_calibrate' => '<!-- wp:group {"className":"{{about_calibrate_layout}}","layout":{"type":"default"}} --><div class="wp-block-group {{about_calibrate_layout}}"><!-- wp:group {"className":"cm-panel","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel"><!-- wp:paragraph --><p class="cm-eyebrow">What This Brand Is Not</p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- wp:group {"className":"cm-panel","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel"><!-- wp:paragraph --><p class="cm-eyebrow">Final Calibration</p><!-- /wp:paragraph --></div><!-- /wp:group --></div><!-- /wp:group -->',
			'browse_counter_intro' => '<!-- wp:group {"className":"cm-panel cm-section cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-section cm-copy-prose"><!-- wp:paragraph --><p class="cm-eyebrow">Browse the Counter</p><!-- /wp:paragraph --><!-- wp:heading {"level":1,"className":"cm-sign-title"} --><h1 class="wp-block-heading cm-sign-title">A worker-first software counter, not a platform.</h1><!-- /wp:heading --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">{{brand_name}} is built for document cleanup, file conversion, extraction, formatting, packaging, writing support, image fixes, repetitive admin tasks, and the tiny digital chores that eat half the day.</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Some tools run on your machine. Some tasks run on compute. Either way, the point is the same: help you move faster without handing over control.</p><!-- /wp:paragraph --></div><!-- /wp:group -->',
			'browse_counter_categories' => '<!-- wp:group {"className":"cm-panel cm-section cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-section cm-copy-prose"><!-- wp:paragraph --><p class="cm-eyebrow">Counter Categories</p><!-- /wp:paragraph --><!-- wp:heading {"level":2,"className":"cm-sign-title"} --><h2 class="wp-block-heading cm-sign-title">Use these aisles to find the right tool faster.</h2><!-- /wp:heading --><!-- wp:list {"className":"cm-trust-list"} --><ul class="cm-trust-list"><li>PDF &amp; Document Surgery</li><li>File Fixes &amp; Conversion</li><li>Spreadsheet Rescue</li><li>Inbox, Forms &amp; Admin Helpers</li><li>Writing &amp; Output Cleanup</li><li>Image &amp; Asset Fixes</li><li>Heavy Lifting (Compute Tasks)</li><li>Buy Once, Run Forever</li></ul><!-- /wp:list --></div><!-- /wp:group -->',
			'browse_counter_feed' => '<!-- wp:group {"className":"cm-panel cm-section cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-section cm-copy-prose"><!-- wp:paragraph --><p class="cm-eyebrow">Product Counter</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">We do not sell a platform. We sell tools, one at a time, each one useful on its own.</p><!-- /wp:paragraph --><!-- wp:shortcode -->[cafe_moxie_featured_edge_tools count="{{featured_tools_count}}"]<!-- /wp:shortcode --><!-- wp:buttons {"className":"cm-action-cluster cm-action-buttons cm-action-buttons--composed"} --><div class="wp-block-buttons cm-action-cluster cm-action-buttons cm-action-buttons--composed"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="{{edge_tool_archive_url}}">Browse full Edge Tool archive</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group -->',
			'browse_counter_cta' => '<!-- wp:group {"className":"cm-panel cm-section","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-section"><!-- wp:paragraph --><p class="cm-eyebrow">Next step</p><!-- /wp:paragraph --><!-- wp:heading {"level":2,"className":"cm-sign-title"} --><h2 class="wp-block-heading cm-sign-title">Pull up a chair. The tools are ready.</h2><!-- /wp:heading --><!-- wp:buttons {"className":"cm-action-cluster cm-action-buttons cm-action-buttons--composed"} --><div class="wp-block-buttons cm-action-cluster cm-action-buttons cm-action-buttons--composed"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="{{edge_tool_archive_url}}">Browse the Counter</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group -->',
			'how_it_works_intro' => '<!-- wp:group {"className":"cm-panel cm-section cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-section cm-copy-prose"><!-- wp:paragraph --><p class="cm-eyebrow">How It Works</p><!-- /wp:paragraph --><!-- wp:heading {"level":1,"className":"cm-sign-title"} --><h1 class="wp-block-heading cm-sign-title">Buy once when you want ownership. Use compute when you want convenience.</h1><!-- /wp:heading --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Cafe Moxie keeps the model simple: useful software without platform lock-in or subscription theater.</p><!-- /wp:paragraph --></div><!-- /wp:group -->',
			'how_it_works_models' => '<!-- wp:group {"className":"cm-grid-3 cm-section","layout":{"type":"default"}} --><div class="wp-block-group cm-grid-3 cm-section"><!-- wp:group {"className":"cm-card cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-card cm-copy-prose"><!-- wp:paragraph --><p class="cm-badge">Buy Once</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Run local on your desktop with no account required and no recurring subscription.</p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- wp:group {"className":"cm-card cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-card cm-copy-prose"><!-- wp:paragraph --><p class="cm-badge">Pay Per Task</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Use compute-backed execution when the task is heavier, specialized, or not worth setting up locally.</p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- wp:group {"className":"cm-card cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-card cm-copy-prose"><!-- wp:paragraph --><p class="cm-badge">Hybrid</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Buy once for recurring work and add compute only when you need extra power.</p><!-- /wp:paragraph --></div><!-- /wp:group --></div><!-- /wp:group -->',
			'how_it_works_credits' => '<!-- wp:group {"className":"cm-panel cm-section cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-section cm-copy-prose"><!-- wp:paragraph --><p class="cm-eyebrow">Compute Credits</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Pay for the task, not the platform. Use compute when local setup is not worth it, then return to your normal workflow.</p><!-- /wp:paragraph --><!-- wp:list {"className":"cm-trust-list"} --><ul class="cm-trust-list"><li>Heavier lift. Same judgment.</li><li>Run it off-site when you just need it done.</li><li>Every product page states what still needs a human eye.</li></ul><!-- /wp:list --></div><!-- /wp:group -->',
			'how_it_works_cta' => '<!-- wp:group {"className":"cm-panel cm-section","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-section"><!-- wp:paragraph --><p class="cm-eyebrow">Choose your model</p><!-- /wp:paragraph --><!-- wp:heading {"level":2,"className":"cm-sign-title"} --><h2 class="wp-block-heading cm-sign-title">Own the leverage. Pick the tool that matches the task.</h2><!-- /wp:heading --><!-- wp:buttons {"className":"cm-action-cluster cm-action-buttons cm-action-buttons--composed"} --><div class="wp-block-buttons cm-action-cluster cm-action-buttons cm-action-buttons--composed"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="{{edge_tool_archive_url}}">Browse the Counter</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group -->',
			'who_its_for_intro' => '<!-- wp:group {"className":"cm-panel cm-section cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-section cm-copy-prose"><!-- wp:paragraph --><p class="cm-eyebrow">Who It’s For</p><!-- /wp:paragraph --><!-- wp:heading {"level":1,"className":"cm-sign-title"} --><h1 class="wp-block-heading cm-sign-title">For the person everyone asks for help.</h1><!-- /wp:heading --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Cafe Moxie is built for people whose jobs happen on computers and whose skill has been shaped by doing the work repeatedly in the real world.</p><!-- /wp:paragraph --></div><!-- /wp:group -->',
			'who_its_for_traits' => '<!-- wp:group {"className":"cm-grid-2 cm-section","layout":{"type":"default"}} --><div class="wp-block-group cm-grid-2 cm-section"><!-- wp:group {"className":"cm-panel cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-copy-prose"><!-- wp:heading {"level":2,"className":"cm-sign-title"} --><h2 class="wp-block-heading cm-sign-title">Roles we build for</h2><!-- /wp:heading --><!-- wp:list {"className":"cm-trust-list"} --><ul class="cm-trust-list"><li>Operations staff and coordinators</li><li>Executive and administrative assistants</li><li>Analysts, researchers, editors, and producers</li><li>Office managers, project support staff, and case managers</li><li>Digital freelancers and high-competence non-engineer power users</li></ul><!-- /wp:list --></div><!-- /wp:group --><!-- wp:group {"className":"cm-panel cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-copy-prose"><!-- wp:heading {"level":2,"className":"cm-sign-title"} --><h2 class="wp-block-heading cm-sign-title">Shared traits</h2><!-- /wp:heading --><!-- wp:list {"className":"cm-trust-list"} --><ul class="cm-trust-list"><li>Comfortable with desktop and web workflows</li><li>Handles repetitive digital tasks constantly</li><li>Knows what the output should look like before the tool runs</li><li>Skeptical of hype and responsive to what actually works</li><li>Takes quiet pride in being able to fix the thing</li></ul><!-- /wp:list --></div><!-- /wp:group --></div><!-- /wp:group -->',
			'who_its_for_emotional_truth' => '<!-- wp:group {"className":"cm-panel cm-section cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-section cm-copy-prose"><!-- wp:heading {"level":2,"className":"cm-sign-title"} --><h2 class="wp-block-heading cm-sign-title">Better tools, not software theater.</h2><!-- /wp:heading --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">These workers do not want to disrupt work. They want to get through the work with less friction, less dependency, and more dignity.</p><!-- /wp:paragraph --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Pull up a chair. The tools are ready.</p><!-- /wp:paragraph --></div><!-- /wp:group -->',
			'who_its_for_cta' => '<!-- wp:group {"className":"cm-panel cm-section","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-section"><!-- wp:paragraph --><p class="cm-eyebrow">Find your edge</p><!-- /wp:paragraph --><!-- wp:buttons {"className":"cm-action-cluster cm-action-buttons cm-action-buttons--composed"} --><div class="wp-block-buttons cm-action-cluster cm-action-buttons cm-action-buttons--composed"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="{{edge_tool_archive_url}}">Browse the Counter</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group -->',
			'trust_faq_promises' => '<!-- wp:group {"className":"cm-panel cm-section cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-section cm-copy-prose"><!-- wp:paragraph --><p class="cm-eyebrow">Trust + FAQ</p><!-- /wp:paragraph --><!-- wp:heading {"level":1,"className":"cm-sign-title"} --><h1 class="wp-block-heading cm-sign-title">What we won’t do.</h1><!-- /wp:heading --><!-- wp:list {"className":"cm-trust-list"} --><ul class="cm-trust-list"><li>We won’t gate basic functionality behind a subscription.</li><li>We won’t pretend AI replaces your judgment.</li><li>We won’t harvest your documents to train models.</li><li>If a tool is not right for your task, we will say so.</li><li>Every product page states what still needs a human eye.</li></ul><!-- /wp:list --></div><!-- /wp:group -->',
			'trust_faq_commerce' => '<!-- wp:group {"className":"cm-panel cm-section cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-section cm-copy-prose"><!-- wp:heading {"level":2,"className":"cm-sign-title"} --><h2 class="wp-block-heading cm-sign-title">Commerce rules</h2><!-- /wp:heading --><!-- wp:list {"className":"cm-trust-list"} --><ul class="cm-trust-list"><li>Buy once for local tools.</li><li>Pay per task for compute-backed workflows.</li><li>Show real prices directly on product pages.</li><li>No contact-sales wall for basic purchasing.</li></ul><!-- /wp:list --></div><!-- /wp:group -->',
			'trust_faq_product_clarity' => '<!-- wp:group {"className":"cm-panel cm-section cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-section cm-copy-prose"><!-- wp:heading {"level":2,"className":"cm-sign-title"} --><h2 class="wp-block-heading cm-sign-title">Product page clarity checklist</h2><!-- /wp:heading --><!-- wp:list {"className":"cm-trust-list"} --><ul class="cm-trust-list"><li>What is the annoying task?</li><li>Who deals with this?</li><li>What goes in and what comes out?</li><li>Does it run local or use compute?</li><li>How is it priced?</li><li>What still needs your judgment?</li><li>Why does this save time?</li></ul><!-- /wp:list --></div><!-- /wp:group -->',
			'trust_faq_cta' => '<!-- wp:group {"className":"cm-panel cm-section","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-section"><!-- wp:paragraph --><p class="cm-eyebrow">Ready when you are</p><!-- /wp:paragraph --><!-- wp:buttons {"className":"cm-action-cluster cm-action-buttons cm-action-buttons--composed"} --><div class="wp-block-buttons cm-action-cluster cm-action-buttons cm-action-buttons--composed"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="{{edge_tool_archive_url}}">Browse the Counter</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group -->',
			'hero' => '<!-- wp:group {"className":"cm-panel cm-section cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-section cm-copy-prose">{{brand_mark}}<!-- wp:paragraph --><p class="cm-eyebrow">Template Composer</p><!-- /wp:paragraph --><!-- wp:heading {"level":1,"className":"cm-sign-title"} --><h1 class="wp-block-heading cm-sign-title">{{brand_name}} page template</h1><!-- /wp:heading --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Start with a focused hero and then add only the sections this page needs.</p><!-- /wp:paragraph --></div><!-- /wp:group -->',
			'story_split' => '<!-- wp:group {"className":"{{story_layout}}","layout":{"type":"default"}} --><div class="wp-block-group {{story_layout}}"><!-- wp:group {"className":"cm-panel cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-copy-prose"><!-- wp:paragraph --><p class="cm-eyebrow">Story split</p><!-- /wp:paragraph --><!-- wp:heading {"level":2,"className":"cm-sign-title"} --><h2 class="wp-block-heading cm-sign-title">How Cafe Moxie helps you ship without platform theater.</h2><!-- /wp:heading --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Pair practical workflow detail with a real screenshot, signage photo, or interface evidence.</p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- wp:group {"className":"cm-panel","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel"><!-- wp:html --><div class="cm-placeholder"><span class="cm-badge cm-status--warm">Replace media</span><p class="cm-subtle">Drop in an image, logo treatment, or proof point.</p></div><!-- /wp:html --></div><!-- /wp:group --></div><!-- /wp:group -->',
			'feature_grid' => '<!-- wp:group {"className":"cm-grid-3 cm-section","layout":{"type":"default"}} --><div class="wp-block-group cm-grid-3 cm-section"><!-- wp:group {"className":"cm-card","layout":{"type":"constrained"}} --><div class="wp-block-group cm-card"><!-- wp:paragraph --><p class="cm-badge">Feature</p><!-- /wp:paragraph --><!-- wp:heading {"level":3,"className":"cm-sign-title"} --><h3 class="wp-block-heading cm-sign-title">Fast setup</h3><!-- /wp:heading --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Keep the first value block clear and practical.</p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- wp:group {"className":"cm-card","layout":{"type":"constrained"}} --><div class="wp-block-group cm-card"><!-- wp:paragraph --><p class="cm-badge">Feature</p><!-- /wp:paragraph --><!-- wp:heading {"level":3,"className":"cm-sign-title"} --><h3 class="wp-block-heading cm-sign-title">Clear ownership</h3><!-- /wp:heading --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Define what users control and what stays simple.</p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- wp:group {"className":"cm-card","layout":{"type":"constrained"}} --><div class="wp-block-group cm-card"><!-- wp:paragraph --><p class="cm-badge">Feature</p><!-- /wp:paragraph --><!-- wp:heading {"level":3,"className":"cm-sign-title"} --><h3 class="wp-block-heading cm-sign-title">Real-world outcomes</h3><!-- /wp:heading --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Show what gets easier after the workflow changes.</p><!-- /wp:paragraph --></div><!-- /wp:group --></div><!-- /wp:group -->',
			'cta_band' => '<!-- wp:group {"className":"cm-panel cm-section","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-section"><!-- wp:paragraph --><p class="cm-eyebrow">Next step</p><!-- /wp:paragraph --><!-- wp:heading {"level":2,"className":"cm-sign-title"} --><h2 class="wp-block-heading cm-sign-title">Move from explanation to action.</h2><!-- /wp:heading --><!-- wp:buttons {"className":"cm-action-cluster cm-action-buttons cm-action-buttons--composed"} --><div class="wp-block-buttons cm-action-cluster cm-action-buttons cm-action-buttons--composed"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="{{primary_cta_url}}">{{primary_cta_label}}</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group -->',
			'trust_section' => '<!-- wp:group {"className":"cm-panel cm-section cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-section cm-copy-prose"><!-- wp:paragraph --><p class="cm-eyebrow">Trust section</p><!-- /wp:paragraph --><!-- wp:list {"className":"cm-trust-list"} --><ul class="cm-trust-list"><li>We do not train on your client files. We do not trap basic features behind subscriptions.</li><li>Set expectations for edge cases and limits.</li><li>Keep promises specific and verifiable.</li></ul><!-- /wp:list --></div><!-- /wp:group -->',
			'product_feed' => '<!-- wp:group {"className":"cm-panel cm-section cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-section cm-copy-prose"><!-- wp:paragraph --><p class="cm-eyebrow">Product feed</p><!-- /wp:paragraph --><!-- wp:shortcode -->[cafe_moxie_featured_edge_tools count="{{featured_tools_count}}"]<!-- /wp:shortcode --></div><!-- /wp:group -->',
			'content_section' => '<!-- wp:group {"className":"cm-panel cm-section cm-copy-prose","layout":{"type":"constrained"}} --><div class="wp-block-group cm-panel cm-section cm-copy-prose"><!-- wp:paragraph --><p class="cm-eyebrow">Content section</p><!-- /wp:paragraph --><!-- wp:heading {"level":2,"className":"cm-sign-title"} --><h2 class="wp-block-heading cm-sign-title">Document the workflow, tradeoffs, and who this section is for in plain language.</h2><!-- /wp:heading --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Keep this copy concrete: what the tool does, what it does not do, and when to choose buy-once vs per-task execution.</p><!-- /wp:paragraph --></div><!-- /wp:group -->',
		);
	}

	private static function composed_section_context( $s, $brand ) {
		$home_hero_image = self::resolve_url( $s['home_hero_image'] ?? '' );
		$home_story_image = self::resolve_url( $s['home_story_image'] ?? '' );
		$about_story_image = self::resolve_url( $s['about_story_image'] ?? '' );
		$home_story_meta = self::resolve_media_metadata( $home_story_image );
		$about_story_meta = self::resolve_media_metadata( $about_story_image );
		$home_story_layout_fallback = 'portrait' === $home_story_meta['orientation'] ? 'stacked_on_tablet' : 'media_right_split';
		$about_intro_layout_fallback = 'portrait' === $about_story_meta['orientation'] ? 'stacked_on_tablet' : 'media_right_split';
		return array(
			'brand_mark' => self::render_brand_mark(),
			'brand_name' => esc_html( $brand['name'] ?? '' ),
			'home_hero_layout' => esc_attr( self::section_layout_classes( 'home_hero_layout', 'balanced_two_column', 'cm-hero' ) ),
			'home_story_layout' => esc_attr( self::section_layout_classes( 'home_story_layout', $home_story_layout_fallback ) ),
			'home_trust_layout' => esc_attr( self::section_layout_classes( 'home_trust_layout', 'balanced_two_column' ) ),
			'home_featured_layout' => esc_attr( self::section_layout_classes( 'home_featured_layout', 'stacked_on_tablet' ) ),
			'about_intro_layout' => esc_attr( self::section_layout_classes( 'about_intro_layout', $about_intro_layout_fallback ) ),
			'about_calibrate_layout' => esc_attr( self::section_layout_classes( 'about_calibrate_layout', 'balanced_two_column' ) ),
			'home_hero_media' => self::render_composed_media_frame( $home_hero_image, $brand['name'] ?? '', 'hero-wide', 'Add hero image', 'Cafe signage-worthy hero frame' ),
			'home_story_media' => self::render_composed_media_frame( $home_story_image, $brand['name'] ?? '', 'split-standard', 'Add story image', 'Story image frame' ),
			'about_story_media' => self::render_composed_media_frame( $about_story_image, $brand['name'] ?? '', 'split-tall', 'Add about image', 'About image frame' ),
			'story_layout' => esc_attr( self::section_layout_classes( 'home_story_layout', 'media_right_split' ) ),
			'primary_cta_url' => esc_url( self::resolve_url( $s['home_primary_url'] ?? '' ) ),
			'primary_cta_label' => esc_html( $s['home_primary_cta'] ?? '' ),
			'home_primary_cta_url' => esc_url( self::resolve_url( $s['home_primary_url'] ?? '' ) ),
			'home_primary_cta_label' => esc_html( $s['home_primary_cta'] ?? '' ),
			'home_secondary_cta_url' => esc_url( self::resolve_url( $s['home_secondary_url'] ?? '' ) ),
			'home_secondary_cta_label' => esc_html( $s['home_secondary_cta'] ?? '' ),
			'about_primary_cta_url' => esc_url( self::resolve_url( $s['about_primary_url'] ?? '' ) ),
			'about_primary_cta_label' => esc_html( $s['about_primary_cta'] ?? '' ),
			'edge_tool_archive_url' => esc_url( self::module_archive_link( 'edge_tool' ) ),
			'featured_tools_count' => esc_attr( (string) (int) ( $s['featured_tools_count'] ?? 3 ) ),
		);
	}

	private static function render_composed_media_frame( $url, $brand_name, $frame_mode = 'split-standard', $badge = 'Add image', $fallback_title = 'Image slot' ) {
		$html = self::render_media_frame(
			$url,
			array(
				'frame_mode' => $frame_mode,
				'alt' => ( $brand_name ? $brand_name . ' image' : 'Cafe Moxie image' ),
				'placeholder_badge' => $badge,
				'placeholder_title' => $fallback_title,
				'weak_media_fallback' => true,
			)
		);
		return '<!-- wp:html -->' . $html . '<!-- /wp:html -->';
	}

	public static function resolve_media_metadata( $source, $size = 'large' ) {
		$resolved = array(
			'attachment_id' => 0,
			'url' => '',
			'width' => 0,
			'height' => 0,
			'aspect_ratio' => 0,
			'orientation' => 'unknown',
			'is_tiny' => false,
		);

		if ( empty( $source ) ) {
			return $resolved;
		}

		$attachment_id = 0;
		if ( is_numeric( $source ) ) {
			$attachment_id = intval( $source );
		} elseif ( is_array( $source ) ) {
			$attachment_id = intval( $source['ID'] ?? $source['id'] ?? 0 );
		}

		$url = self::image_url( $source, $size );
		if ( $url && ! $attachment_id ) {
			$attachment_id = attachment_url_to_postid( $url );
		}

		$resolved['attachment_id'] = $attachment_id > 0 ? $attachment_id : 0;
		$resolved['url'] = $url ? esc_url_raw( $url ) : '';

		if ( $resolved['attachment_id'] ) {
			$meta = wp_get_attachment_metadata( $resolved['attachment_id'] );
			$resolved['width'] = intval( $meta['width'] ?? 0 );
			$resolved['height'] = intval( $meta['height'] ?? 0 );
		}

		if ( $resolved['width'] > 0 && $resolved['height'] > 0 ) {
			$resolved['aspect_ratio'] = round( $resolved['width'] / $resolved['height'], 4 );
			if ( $resolved['width'] > $resolved['height'] ) {
				$resolved['orientation'] = 'landscape';
			} elseif ( $resolved['height'] > $resolved['width'] ) {
				$resolved['orientation'] = 'portrait';
			} else {
				$resolved['orientation'] = 'square';
			}
			$resolved['is_tiny'] = ( $resolved['width'] * $resolved['height'] ) < 300000 || min( $resolved['width'], $resolved['height'] ) < 480;
		}

		return $resolved;
	}

	public static function render_media_frame( $source, $args = array() ) {
		$defaults = array(
			'frame_mode' => 'split-standard',
			'alt' => '',
			'placeholder_badge' => 'Add image',
			'placeholder_title' => 'Cafe signage frame',
			'placeholder_detail' => 'Add a photo, screenshot, or signage-ready image.',
			'weak_media_fallback' => false,
			'extra_class' => '',
		);
		$args = wp_parse_args( $args, $defaults );
		$meta = self::resolve_media_metadata( $source );
		$frame_mode = sanitize_html_class( (string) $args['frame_mode'] );
		$orientation = sanitize_html_class( (string) $meta['orientation'] );
		$mode_by_orientation = array(
			'portrait' => 'portrait-focus',
			'square' => 'square-card',
			'landscape' => $frame_mode,
			'unknown' => $frame_mode,
		);
		if ( isset( $mode_by_orientation[ $orientation ] ) && in_array( $frame_mode, array( 'split-standard', 'split-tall' ), true ) ) {
			$frame_mode = $mode_by_orientation[ $orientation ];
		}
		$extra_classes = array();
		foreach ( preg_split( '/\s+/', (string) $args['extra_class'] ) as $extra_class ) {
			$extra_class = sanitize_html_class( $extra_class );
			if ( $extra_class ) {
				$extra_classes[] = $extra_class;
			}
		}
		$classes = trim( 'cm-media-frame cm-media-frame--' . $frame_mode . ' cm-media-frame--' . $orientation . ' ' . implode( ' ', $extra_classes ) );
		$has_weak_media = ! empty( $args['weak_media_fallback'] ) && ! empty( $meta['is_tiny'] );

		if ( empty( $meta['url'] ) || $has_weak_media ) {
			$detail = $has_weak_media ? 'Image is too small for this layout. Add a higher resolution media asset.' : (string) $args['placeholder_detail'];
			return '<div class="' . esc_attr( $classes ) . ' cm-media-frame--placeholder"><span class="cm-badge cm-status--warm">' . esc_html( $args['placeholder_badge'] ) . '</span><h2 class="cm-sign-title cm-placeholder-title">' . esc_html( $args['placeholder_title'] ) . '</h2><p class="cm-subtle">' . esc_html( $detail ) . '</p></div>';
		}

		return '<figure class="' . esc_attr( $classes ) . '"><img class="cm-media-image" src="' . esc_url( $meta['url'] ) . '" alt="' . esc_attr( (string) $args['alt'] ) . '" loading="lazy" decoding="async"/></figure>';
	}

	private static function replace_markup_tokens( $markup, $context ) {
		$replacements = array();
		foreach ( $context as $key => $value ) {
			$replacements[ '{{' . $key . '}}' ] = (string) $value;
		}
		return strtr( (string) $markup, $replacements );
	}

	private static function render_composed_section( $section_key, $s, $brand ) {
		$sections = self::composed_sections();
		if ( ! isset( $sections[ $section_key ]['template'] ) ) {
			return '';
		}
		$visibility = $sections[ $section_key ]['visibility_rules'] ?? array();
		if ( ! empty( $visibility['setting_key'] ) ) {
			$setting_key = sanitize_key( $visibility['setting_key'] );
			$expected = isset( $visibility['truthy'] ) ? (bool) $visibility['truthy'] : true;
			$current = ! empty( $s[ $setting_key ] );
			if ( $expected !== $current ) {
				return '';
			}
		}
		$templates = self::composed_section_markup();
		$template_key = $sections[ $section_key ]['template'];
		if ( ! isset( $templates[ $template_key ] ) ) {
			return '';
		}
		$context = self::composed_section_context( $s, $brand );
		return self::replace_markup_tokens( $templates[ $template_key ], $context );
	}

	public static function create_or_update_page( $slug, $title, $content, $overwrite = true ) {
		$existing = get_page_by_path( $slug, OBJECT, 'page' );
		$data = array(
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_content' => $content,
		);
		if ( $existing ) {
			if ( ! $overwrite ) {
				return $existing->ID;
			}
			$data['ID'] = $existing->ID;
			return wp_update_post( $data, true );
		}
		return wp_insert_post( $data, true );
	}

	public static function starter_page_definitions() {
		return array(
			array(
				'slug' => 'home',
				'title' => 'Home',
				'pattern' => 'home.php',
			),
			array(
				'slug' => 'about',
				'title' => 'About Cafe Moxie',
				'pattern' => 'about.php',
			),
			array(
				'slug' => 'browse-the-counter',
				'title' => 'Browse the Counter',
				'pattern' => 'browse-the-counter.php',
			),
			array(
				'slug' => 'how-it-works',
				'title' => 'How It Works',
				'pattern' => 'how-it-works.php',
			),
			array(
				'slug' => 'who-its-for',
				'title' => 'Who It’s For',
				'pattern' => 'who-its-for.php',
			),
			array(
				'slug' => 'trust-faq',
				'title' => 'Trust + FAQ',
				'pattern' => 'trust-faq.php',
			),
		);
	}

	private static function load_pattern_content( $pattern_file ) {
		ob_start();
		include plugin_dir_path( __FILE__ ) . 'patterns/' . $pattern_file;
		return ob_get_clean();
	}

	private static function set_generated_markers( $post_id, $type, $content ) {
		update_post_meta( $post_id, self::META_GENERATED_MARKER, 1 );
		update_post_meta( $post_id, self::META_GENERATED_TYPE, sanitize_key( $type ) );
		update_post_meta( $post_id, self::META_GENERATED_AT, current_time( 'mysql', true ) );
		update_post_meta( $post_id, self::META_GENERATED_HASH, md5( (string) $content ) );
	}

	private static function is_marked_generated_page( $post_id, $expected_type = '' ) {
		$marked = (bool) get_post_meta( $post_id, self::META_GENERATED_MARKER, true );
		if ( ! $marked ) {
			return false;
		}
		if ( '' === $expected_type ) {
			return true;
		}
		return sanitize_key( $expected_type ) === sanitize_key( (string) get_post_meta( $post_id, self::META_GENERATED_TYPE, true ) );
	}

	private static function generate_or_update_page( $args ) {
		$defaults = array(
			'slug' => '',
			'title' => '',
			'content' => '',
			'type' => 'starter',
			'overwrite' => false,
		);
		$args = wp_parse_args( $args, $defaults );
		$slug = sanitize_title( $args['slug'] );
		$title = sanitize_text_field( $args['title'] );
		$content = (string) $args['content'];
		$type = sanitize_key( $args['type'] );
		$overwrite = ! empty( $args['overwrite'] );

		$existing = get_page_by_path( $slug, OBJECT, 'page' );
		if ( ! $existing ) {
			$post_id = self::create_or_update_page( $slug, $title, $content, true );
			if ( ! is_wp_error( $post_id ) ) {
				self::set_generated_markers( $post_id, $type, $content );
			}
			return array( 'status' => 'created', 'post_id' => $post_id );
		}

		if ( ! $overwrite ) {
			return array( 'status' => 'skipped_existing', 'post_id' => $existing->ID );
		}

		if ( ! self::is_marked_generated_page( $existing->ID, $type ) ) {
			return array( 'status' => 'skipped_unmanaged', 'post_id' => $existing->ID );
		}

		if ( function_exists( 'wp_save_post_revision' ) ) {
			wp_save_post_revision( $existing->ID );
		}

		$post_id = self::create_or_update_page( $slug, $title, $content, true );
		if ( ! is_wp_error( $post_id ) ) {
			self::set_generated_markers( $post_id, $type, $content );
		}
		return array( 'status' => 'updated', 'post_id' => $post_id );
	}

	private static function starter_generation_summary_from_request() {
		$created = isset( $_GET['starter_created'] ) ? intval( $_GET['starter_created'] ) : 0;
		$updated = isset( $_GET['starter_updated'] ) ? intval( $_GET['starter_updated'] ) : 0;
		$skipped_existing = isset( $_GET['starter_skipped_existing'] ) ? intval( $_GET['starter_skipped_existing'] ) : 0;
		$skipped_unmanaged = isset( $_GET['starter_skipped_unmanaged'] ) ? intval( $_GET['starter_skipped_unmanaged'] ) : 0;
		$errors = isset( $_GET['starter_errors'] ) ? intval( $_GET['starter_errors'] ) : 0;
		$ran = isset( $_GET['starter_ran'] ) ? intval( $_GET['starter_ran'] ) : 0;

		if ( $ran < 1 ) {
			return '';
		}

		return sprintf(
			'Starter page generation complete: %1$d created, %2$d updated, %3$d skipped (already exists), %4$d skipped (not generated by Site Kit), %5$d errors.',
			$created,
			$updated,
			$skipped_existing,
			$skipped_unmanaged,
			$errors
		);
	}

	private static function template_part_generation_summary_from_request() {
		$created = isset( $_GET['parts_created'] ) ? intval( $_GET['parts_created'] ) : 0;
		$updated = isset( $_GET['parts_updated'] ) ? intval( $_GET['parts_updated'] ) : 0;
		$skipped_unmanaged = isset( $_GET['parts_skipped_unmanaged'] ) ? intval( $_GET['parts_skipped_unmanaged'] ) : 0;
		$errors = isset( $_GET['parts_errors'] ) ? intval( $_GET['parts_errors'] ) : 0;
		$ran = isset( $_GET['parts_ran'] ) ? intval( $_GET['parts_ran'] ) : 0;
		if ( $ran < 1 ) {
			return '';
		}
		return sprintf(
			'Header/footer generation complete: %1$d created, %2$d updated, %3$d skipped (not generated by Site Kit), %4$d errors.',
			$created,
			$updated,
			$skipped_unmanaged,
			$errors
		);
	}

	public static function create_starter_pages() {
		check_admin_referer( 'cafe_moxie_create_starter_pages' );
		$s = self::settings();
		$overwrite = ( 'overwrite' === ( $s['refresh_mode'] ?? 'safe' ) );
		$counts = self::generate_starter_pages( $overwrite );
		$redirect_url = add_query_arg(
			array(
				'page' => 'cafe-moxie-site-kit',
				'starter_ran' => 1,
				'starter_created' => $counts['created'],
				'starter_updated' => $counts['updated'],
				'starter_skipped_existing' => $counts['skipped_existing'],
				'starter_skipped_unmanaged' => $counts['skipped_unmanaged'],
				'starter_errors' => $counts['errors'],
			),
			admin_url( 'admin.php' )
		);
		wp_safe_redirect( $redirect_url );
		exit;
	}

	private static function generate_starter_pages( $overwrite = false ) {
		$overwrite = (bool) $overwrite;

		$counts = array(
			'created' => 0,
			'updated' => 0,
			'skipped_existing' => 0,
			'skipped_unmanaged' => 0,
			'errors' => 0,
		);

		foreach ( self::starter_page_definitions() as $definition ) {
			$content = self::load_pattern_content( $definition['pattern'] );
			$result = self::generate_or_update_page(
				array(
					'slug' => $definition['slug'],
					'title' => $definition['title'],
					'content' => $content,
					'type' => 'starter',
					'overwrite' => $overwrite,
				)
			);
			$status = $result['status'] ?? 'errors';
			if ( isset( $counts[ $status ] ) ) {
				$counts[ $status ]++;
			} else {
				$counts['errors']++;
			}
		}
		return $counts;
	}

	public static function assign_front_page() {
		check_admin_referer( 'cafe_moxie_assign_front_page' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You are not allowed to manage presentation settings.', 'cafe-moxie-site-kit' ) );
		}
		$page_id = isset( $_GET['page_id'] ) ? intval( $_GET['page_id'] ) : 0;
		if ( $page_id < 1 ) {
			wp_safe_redirect(
				add_query_arg(
					array(
						'page' => 'cafe-moxie-site-kit',
						'cm_setup_notice' => 'Could not assign front page: missing page ID.',
					),
					admin_url( 'admin.php' )
				)
			);
			exit;
		}
		$page = get_post( $page_id );
		if ( ! $page || 'page' !== $page->post_type ) {
			wp_safe_redirect(
				add_query_arg(
					array(
						'page' => 'cafe-moxie-site-kit',
						'cm_setup_notice' => 'Could not assign front page: selected content is not a page.',
					),
					admin_url( 'admin.php' )
				)
			);
			exit;
		}
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $page_id );
		wp_safe_redirect(
			add_query_arg(
				array(
					'page' => 'cafe-moxie-site-kit',
					'cm_setup_notice' => sprintf( 'Front page assigned to %s.', get_the_title( $page_id ) ),
				),
				admin_url( 'admin.php' )
			)
		);
		exit;
	}

	public static function apply_brand_preset_action() {
		check_admin_referer( 'cafe_moxie_apply_preset' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You are not allowed to apply presets.', 'cafe-moxie-site-kit' ) );
		}
		$preset = isset( $_GET['preset'] ) ? sanitize_key( wp_unslash( $_GET['preset'] ) ) : 'cafe_moxie';
		$current = self::settings();
		$updated = self::apply_brand_preset_to_settings( $current, $preset );
		update_option( self::OPTION, $updated );
		wp_safe_redirect(
			add_query_arg(
				array(
					'page' => 'cafe-moxie-site-kit',
					'cm_setup_notice' => sprintf( 'Applied %s polished defaults.', 'neutral' === $preset ? 'neutral' : 'Cafe Moxie' ),
				),
				admin_url( 'admin.php' )
			)
		);
		exit;
	}

	public static function run_polished_setup() {
		check_admin_referer( 'cafe_moxie_polished_setup' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You are not allowed to run setup.', 'cafe-moxie-site-kit' ) );
		}
		$updated = self::apply_brand_preset_to_settings( self::settings(), 'cafe_moxie', true );
		update_option( self::OPTION, $updated );
		$counts = self::generate_starter_pages( true );
		self::generate_template_parts_internal();
		$home_page = get_page_by_path( 'home', OBJECT, 'page' );
		if ( $home_page ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', (int) $home_page->ID );
		}
		$msg = sprintf( 'Polished setup complete: %1$d created, %2$d updated starter pages.', $counts['created'], $counts['updated'] );
		wp_safe_redirect( add_query_arg( array( 'page' => 'cafe-moxie-site-kit', 'cm_setup_notice' => $msg ), admin_url( 'admin.php' ) ) );
		exit;
	}

	public static function filter_template_part_blocks( $parsed_block, $source_block ) {
		$s = self::settings();
		if ( empty( $s['enable_managed_header_footer'] ) ) {
			return $parsed_block;
		}
		if ( empty( $parsed_block['blockName'] ) || 'core/template-part' !== $parsed_block['blockName'] ) {
			return $parsed_block;
		}
		$slug = sanitize_title( $parsed_block['attrs']['slug'] ?? '' );
		if ( 'header' === $slug && self::find_template_part( 'cm-site-kit-header' ) ) {
			$parsed_block['attrs']['slug'] = 'cm-site-kit-header';
			$parsed_block['attrs']['theme'] = get_stylesheet();
		} elseif ( 'footer' === $slug && self::find_template_part( 'cm-site-kit-footer' ) ) {
			$parsed_block['attrs']['slug'] = 'cm-site-kit-footer';
			$parsed_block['attrs']['theme'] = get_stylesheet();
		}
		return $parsed_block;
	}

	private static function find_template_part( $slug ) {
		$posts = get_posts(
			array(
				'post_type' => 'wp_template_part',
				'name' => sanitize_title( $slug ),
				'post_status' => array( 'publish', 'draft', 'auto-draft' ),
				'numberposts' => 1,
			)
		);
		return ! empty( $posts[0] ) ? $posts[0] : null;
	}

	private static function resolve_navigation_post_id( $source ) {
		if ( 'no_navigation' === $source ) {
			return 0;
		}
		$location = 'primary_navigation' === $source ? 'primary' : 'footer';
		$locations = get_nav_menu_locations();
		$menu_id = isset( $locations[ $location ] ) ? intval( $locations[ $location ] ) : 0;
		if ( ! $menu_id ) {
			return 0;
		}
		$nav = get_posts(
			array(
				'post_type' => 'wp_navigation',
				'post_status' => array( 'publish', 'draft' ),
				'meta_key' => 'wp_navigation_menu_id',
				'meta_value' => $menu_id,
				'numberposts' => 1,
			)
		);
		return ! empty( $nav[0] ) ? intval( $nav[0]->ID ) : 0;
	}

	private static function generated_header_markup() {
		$s = self::settings();
		$site_name = get_bloginfo( 'name' );
		$brand_name = ! empty( $s['site_kicker'] ) ? $s['site_kicker'] : $site_name;
		$logo_url = self::resolve_url( $s['display_logo_image'] ?? '' );
		$cta_label = ! empty( $s['header_cta_label'] ) ? $s['header_cta_label'] : 'Browse Tools';
		$cta_url = self::resolve_url( $s['header_cta_url'] ?? '/edge-tools/' );
		$nav_ref = self::resolve_navigation_post_id( $s['header_nav_source'] ?? 'primary_navigation' );
		$brand_markup = '<!-- wp:site-title {"level":0} /-->';
		if ( 'logo_only' === ( $s['header_brand_treatment'] ?? '' ) && ! empty( $logo_url ) ) {
			$brand_markup = '<!-- wp:html -->' . self::render_media_frame(
				$logo_url,
				array(
					'frame_mode' => 'logo-signage',
					'alt' => $brand_name . ' brand mark',
					'placeholder_badge' => 'Add logo',
					'placeholder_title' => 'Cafe signage mark',
					'extra_class' => 'cm-managed-header__brand-logo',
				)
			) . '<!-- /wp:html -->';
		} elseif ( 'logo_and_name' === ( $s['header_brand_treatment'] ?? '' ) && ! empty( $logo_url ) ) {
			$brand_markup = '<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"}} --><div class="wp-block-group"><!-- wp:html -->' . self::render_media_frame(
				$logo_url,
				array(
					'frame_mode' => 'logo-signage',
					'alt' => $brand_name . ' brand mark',
					'placeholder_badge' => 'Add logo',
					'placeholder_title' => 'Cafe signage mark',
					'extra_class' => 'cm-managed-header__brand-logo',
				)
			) . '<!-- /wp:html --><!-- wp:site-title {"level":0} /--></div><!-- /wp:group -->';
		}
		$nav_markup = $nav_ref ? '<!-- wp:navigation {"ref":' . intval( $nav_ref ) . ',"overlayMenu":"mobile","className":"cm-managed-header__nav-items"} /-->' : '';
		$header_class = 'counter' === ( $s['header_footer_preset'] ?? '' ) ? 'cm-managed-header is-counter' : 'cm-managed-header is-utility';
		return '<!-- cm-site-kit:managed-header --><!-- wp:group {"tagName":"header","className":"' . esc_attr( $header_class ) . '","layout":{"type":"constrained"}} --><header class="wp-block-group ' . esc_attr( $header_class ) . '"><!-- wp:group {"className":"cm-managed-header__shell","layout":{"type":"default"}} --><div class="wp-block-group cm-managed-header__shell"><!-- wp:group {"className":"cm-managed-header__region cm-managed-header__region--left cm-managed-header__brand","layout":{"type":"constrained"}} --><div class="wp-block-group cm-managed-header__region cm-managed-header__region--left cm-managed-header__brand">' . $brand_markup . '</div><!-- /wp:group --><!-- wp:group {"className":"cm-managed-header__region cm-managed-header__region--center cm-managed-header__nav","layout":{"type":"constrained"}} --><div class="wp-block-group cm-managed-header__region cm-managed-header__region--center cm-managed-header__nav">' . $nav_markup . '</div><!-- /wp:group --><!-- wp:group {"className":"cm-managed-header__region cm-managed-header__region--right cm-managed-header__actions cm-action-cluster","layout":{"type":"constrained"}} --><div class="wp-block-group cm-managed-header__region cm-managed-header__region--right cm-managed-header__actions cm-action-cluster"><!-- wp:buttons {"className":"cm-action-buttons"} --><div class="wp-block-buttons cm-action-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $cta_url ) . '">' . esc_html( $cta_label ) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group --></div><!-- /wp:group --></header><!-- /wp:group -->';
	}

	private static function generated_footer_markup() {
		$s = self::settings();
		$meta = '';
		if ( 'custom' === ( $s['footer_meta_behavior'] ?? '' ) && ! empty( $s['footer_legal_text'] ) ) {
			$meta = $s['footer_legal_text'];
		} elseif ( 'copyright' === ( $s['footer_meta_behavior'] ?? '' ) ) {
			$meta = sprintf( '© %1$s %2$s', gmdate( 'Y' ), get_bloginfo( 'name' ) );
		}
		return '<!-- cm-site-kit:managed-footer --><!-- wp:group {"tagName":"footer","className":"cm-managed-footer","layout":{"type":"constrained"}} --><footer class="wp-block-group cm-managed-footer"><!-- wp:columns {"className":"cm-managed-footer__columns"} --><div class="wp-block-columns cm-managed-footer__columns"><!-- wp:column {"className":"cm-managed-footer__column"} --><div class="wp-block-column cm-managed-footer__column"><!-- wp:paragraph --><p>' . esc_html( $s['footer_copy'] ) . '</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>' . esc_html( $s['footer_content_primary'] ) . '</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"className":"cm-managed-footer__column"} --><div class="wp-block-column cm-managed-footer__column"><!-- wp:paragraph --><p>' . esc_html( $s['footer_content_secondary'] ) . '</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>' . esc_html( $s['footer_utility_copy'] ) . '</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns -->' . ( $meta ? '<!-- wp:paragraph {"className":"cm-managed-footer__meta"} --><p class="cm-managed-footer__meta">' . esc_html( $meta ) . '</p><!-- /wp:paragraph -->' : '' ) . '</footer><!-- /wp:group -->';
	}

	private static function upsert_template_part( $slug, $title, $content, $area ) {
		$existing = self::find_template_part( $slug );
		if ( $existing ) {
			if ( ! self::is_marked_generated_page( $existing->ID, 'template_part' ) ) {
				return 'skipped_unmanaged';
			}
			$updated = wp_update_post(
				array(
					'ID' => $existing->ID,
					'post_title' => $title,
					'post_content' => $content,
					'post_status' => 'publish',
				),
				true
			);
			if ( is_wp_error( $updated ) ) {
				return 'errors';
			}
			wp_set_post_terms( $existing->ID, array( $area ), 'wp_template_part_area', false );
			self::set_generated_markers( $existing->ID, 'template_part', $content );
			return 'updated';
		}
		$post_id = wp_insert_post(
			array(
				'post_type' => 'wp_template_part',
				'post_name' => sanitize_title( $slug ),
				'post_title' => $title,
				'post_content' => $content,
				'post_status' => 'publish',
			),
			true
		);
		if ( is_wp_error( $post_id ) || ! $post_id ) {
			return 'errors';
		}
		wp_set_post_terms( $post_id, array( $area ), 'wp_template_part_area', false );
		self::set_generated_markers( $post_id, 'template_part', $content );
		return 'created';
	}

	public static function generate_template_parts() {
		check_admin_referer( 'cafe_moxie_generate_template_parts' );
		$counts = self::generate_template_parts_internal();
		$redirect_url = add_query_arg(
			array(
				'page' => 'cafe-moxie-site-kit',
				'tab' => 'header_footer',
				'parts_ran' => 1,
				'parts_created' => $counts['created'],
				'parts_updated' => $counts['updated'],
				'parts_skipped_unmanaged' => $counts['skipped_unmanaged'],
				'parts_errors' => $counts['errors'],
			),
			admin_url( 'admin.php' )
		);
		wp_safe_redirect( $redirect_url );
		exit;
	}

	private static function generate_template_parts_internal() {
		$counts = array(
			'created' => 0,
			'updated' => 0,
			'skipped_unmanaged' => 0,
			'errors' => 0,
		);
		$results = array(
			self::upsert_template_part( 'cm-site-kit-header', 'Site Kit Header', self::generated_header_markup(), 'header' ),
			self::upsert_template_part( 'cm-site-kit-footer', 'Site Kit Footer', self::generated_footer_markup(), 'footer' ),
		);
		foreach ( $results as $result ) {
			if ( isset( $counts[ $result ] ) ) {
				$counts[ $result ]++;
			}
		}
		return $counts;
	}

	public static function generate_composed_page() {
		check_admin_referer( 'cafe_moxie_generate_composed_page' );
		$s = self::settings();
		$template_key = sanitize_key( $_POST['template_key'] ?? $s['composed_page_template'] );
		$page_title = sanitize_text_field( $_POST['page_title'] ?? $s['composed_page_title'] );
		$page_slug = sanitize_title( $_POST['page_slug'] ?? $s['composed_page_slug'] );
		$sections = isset( $_POST['sections'] ) && is_array( $_POST['sections'] ) ? $_POST['sections'] : array();
		$content = self::compose_page_content( $sections, array( 'template_key' => $template_key ) );
		self::generate_or_update_page(
			array(
				'slug' => $page_slug,
				'title' => $page_title,
				'content' => $content,
				'type' => 'composed',
				'overwrite' => false,
			)
		);
		wp_safe_redirect( admin_url( 'admin.php?page=cafe-moxie-site-kit&composed=1' ) );
		exit;
	}

	public static function template_include( $template ) {
		foreach ( self::content_modules() as $module ) {
			if ( is_singular( $module['post_type'] ) ) {
				$file = plugin_dir_path( __FILE__ ) . $module['singular_template'];
				if ( file_exists( $file ) ) {
					return $file;
				}
			}
			if ( is_post_type_archive( $module['post_type'] ) ) {
				$file = plugin_dir_path( __FILE__ ) . $module['archive_template'];
				if ( file_exists( $file ) ) {
					return $file;
				}
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
		$brand = self::brand_profile();
		if ( $image ) {
			return '<div class="cm-brand-mark cm-sign-flicker">' . self::render_media_frame(
				$image,
				array(
					'frame_mode' => 'logo-signage',
					'alt' => ( $brand['name'] ?? 'Brand' ) . ' logo',
					'placeholder_badge' => 'Add logo',
					'placeholder_title' => 'Cafe signage mark',
					'extra_class' => 'cm-brand-mark__media',
				)
			) . '</div>';
		}
		return '<div class="cm-brand-mark"><span class="cm-brand-mark__fallback">' . esc_html( $s['site_kicker'] ) . '</span></div>';
	}


	public static function normalize_scalar_text( $value ) {
		if ( is_array( $value ) || is_object( $value ) ) {
			return '';
		}
		return trim( (string) $value );
	}

	public static function normalize_bool_flag( $value ) {
		return ! empty( $value );
	}

	public static function normalize_string_list( $values ) {
		$list = array();
		foreach ( (array) $values as $value ) {
			$normalized = self::normalize_scalar_text( $value );
			if ( '' !== $normalized ) {
				$list[] = $normalized;
			}
		}
		return array_values( array_unique( $list ) );
	}

	public static function module_data( $post_id, $module_key = 'edge_tool' ) {
		$module = self::content_module( $module_key );
		$builder = $module['data_builder'] ?? '';
		if ( $builder && is_callable( array( __CLASS__, $builder ) ) ) {
			return call_user_func( array( __CLASS__, $builder ), $post_id );
		}
		return array(
			'post_id' => intval( $post_id ),
			'title' => get_the_title( $post_id ),
			'permalink' => get_permalink( $post_id ),
			'excerpt' => get_the_excerpt( $post_id ),
			'content' => apply_filters( 'the_content', get_post_field( 'post_content', $post_id ) ),
			'taxonomies' => array(),
		);
	}

	public static function edge_tool_data( $post_id ) {
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

		$module = self::content_module( 'edge_tool' );
		return apply_filters( $module['data_filter'], $data, $post_id );
	}

	public static function tool_data( $post_id, $module_key = 'edge_tool' ) {
		return self::module_data( $post_id, $module_key );
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


	public static function render_edge_tool_single_sections( $data, $module_key = 'edge_tool' ) {
		$module = self::content_module( $module_key );
		$sections = $module['single_section_order'] ?? array();
		$html = '';
		foreach ( $sections as $section_key ) {
			$html .= self::render_edge_tool_single_section( $section_key, $data );
		}
		return $html;
	}

	private static function render_edge_tool_single_section( $section_key, $data ) {
		if ( 'how_it_works' === $section_key && empty( $data['how_it_works'] ) && empty( $data['secondary_tasks'] ) && empty( $data['not_for'] ) ) {
			return '';
		}
		if ( 'media_proof' === $section_key && empty( $data['gallery'] ) && empty( $data['before_after'] ) && empty( $data['demo_video'] ) ) {
			return '';
		}
		if ( 'additional_notes' === $section_key && '' === trim( wp_strip_all_tags( (string) $data['content'] ) ) ) {
			return '';
		}
		return '<div class="cm-single-section-marker" data-cm-section="' . esc_attr( $section_key ) . '"></div>';
	}

	public static function render_edge_tool_card( $post_id ) {
		$d = self::edge_tool_data( $post_id );
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
			$image_markup = self::render_media_frame(
				$d['hero_image'],
				array(
					'frame_mode' => 'square-card',
					'alt' => $d['title'],
					'placeholder_badge' => 'Add hero image',
					'placeholder_title' => 'Tool image slot',
					'weak_media_fallback' => true,
					'extra_class' => 'cm-media-frame--ratio',
				)
			);
		} else {
			$image_markup = self::render_media_frame(
				'',
				array(
					'frame_mode' => 'square-card',
					'alt' => $d['title'],
					'placeholder_badge' => 'Add hero image',
					'placeholder_title' => 'Tool image slot',
					'placeholder_detail' => 'Add a featured image or SCF hero image to elevate the card.',
					'extra_class' => 'cm-media-frame--ratio',
				)
			);
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
		$html .= '<h3 class="cm-sign-title"><a class="cm-tool-card__title-link" href="' . esc_url( $d['permalink'] ) . '">' . esc_html( $d['title'] ) . '</a></h3>';
		if ( $d['short_tagline'] ) {
			$html .= '<div class="cm-tool-card__tagline">' . esc_html( $d['short_tagline'] ) . '</div>';
		}
		$html .= '<p class="cm-subtle">' . esc_html( $d['tool_summary'] ? $d['tool_summary'] : $d['excerpt'] ) . '</p>';
		if ( ! empty( $d['taxonomies']['workflow_area'] ) ) {
			$html .= self::render_chip_list( array_slice( $d['taxonomies']['workflow_area'], 0, 3 ) );
		}
		$html .= '<div class="cm-tool-card__footer">';
		$html .= '<div><div class="cm-price">' . esc_html( $d['price_display'] ? $d['price_display'] : 'See details' ) . '</div><div class="cm-subtle cm-tool-card__trust">' . esc_html( $d['trust_cue'] ) . '</div></div>';
		$html .= '<a class="cm-button" href="' . esc_url( $d['permalink'] ) . '">View Tool</a>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</article>';
		return $html;
	}

	public static function render_tool_card( $post_id, $module_key = 'edge_tool' ) {
		$module = self::content_module( $module_key );
		$renderer = $module['card_renderer'] ?? '';
		if ( $renderer && is_callable( array( __CLASS__, $renderer ) ) ) {
			return call_user_func( array( __CLASS__, $renderer ), $post_id );
		}
		return self::render_edge_tool_card( $post_id );
	}

	public static function archive_filters( $module_key = 'edge_tool' ) {
		$module = self::content_module( $module_key );
		return $module['archive_filters'] ?? array();
	}

	public static function request_value( $key ) {
		return sanitize_text_field( wp_unslash( $_GET[ $key ] ?? '' ) );
	}

	public static function archive_query( $module_key = 'edge_tool' ) {
		$s      = self::settings();
		$module = self::content_module( $module_key );
		$paged  = max( 1, intval( get_query_var( 'paged' ) ?: get_query_var( 'page' ) ?: 1 ) );
		$args   = array(
			'post_type'      => $module['post_type'],
			'post_status'    => 'publish',
			'paged'          => $paged,
			'posts_per_page' => intval( $s['archive_items_per_page'] ),
		);

		$search = self::request_value( 'cm_search' );
		if ( $search ) {
			$args['s'] = $search;
		}

		$tax_query = array();
		foreach ( self::archive_filters( $module_key ) as $taxonomy => $label ) {
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
			$meta_query[] = array( 'key' => 'runs_local', 'value' => '1', 'compare' => '=' );
		} elseif ( 'compute' === $mode ) {
			$meta_query[] = array( 'key' => 'requires_compute', 'value' => '1', 'compare' => '=' );
		} elseif ( 'hybrid' === $mode ) {
			$meta_query[] = array(
				'relation' => 'AND',
				array( 'key' => 'runs_local', 'value' => '1', 'compare' => '=' ),
				array( 'key' => 'requires_compute', 'value' => '1', 'compare' => '=' ),
			);
		}
		if ( ! empty( self::request_value( 'cm_featured' ) ) ) {
			$meta_query[] = array( 'key' => 'featured_tool', 'value' => '1', 'compare' => '=' );
		}
		if ( ! empty( $meta_query ) ) {
			if ( count( $meta_query ) > 1 ) {
				$meta_query = array_merge( array( 'relation' => 'AND' ), $meta_query );
			}
			$args['meta_query'] = $meta_query;
		}

		return new WP_Query( apply_filters( $module['archive_query_filter'], $args ) );
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

	public static function current_archive_args( $module_key = 'edge_tool' ) {
		$args = array();
		$keys = array( 'cm_search', 'cm_mode', 'cm_featured' );
		foreach ( array_keys( self::archive_filters( $module_key ) ) as $taxonomy ) {
			$keys[] = 'cm_' . $taxonomy;
		}
		foreach ( $keys as $key ) {
			$value = self::request_value( $key );
			if ( '' !== $value ) {
				$args[ $key ] = $value;
			}
		}
		return $args;
	}
}

Cafe_Moxie_Site_Kit::init();
