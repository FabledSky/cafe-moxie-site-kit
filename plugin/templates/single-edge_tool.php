<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();
	$data = Cafe_Moxie_Site_Kit::tool_data( get_the_ID() );
	$who_deals = ! empty( $data['best_for'] ) ? $data['best_for'] : $data['taxonomies']['workflow_area'];
	$what_in   = array_values( array_unique( array_merge( $data['input_formats'], $data['taxonomies']['input_type'] ) ) );
	$what_out  = array_values( array_unique( array_merge( $data['output_formats'], $data['taxonomies']['output_type'] ) ) );
	$who_else  = ! empty( $data['taxonomies']['tool_type'] ) ? $data['taxonomies']['tool_type'] : $data['taxonomies']['workflow_area'];
	$platform_items = array();
	if ( $data['platform_line'] ) {
		$platform_items[] = $data['platform_line'];
	}
	if ( ! empty( $data['windows_versions'] ) ) {
		$platform_items[] = 'Windows: ' . implode( ', ', (array) $data['windows_versions'] );
	}
	if ( ! empty( $data['mac_versions'] ) ) {
		$platform_items[] = 'macOS: ' . $data['mac_versions'];
	}
	if ( ! empty( $data['linux_distros'] ) ) {
		$platform_items[] = 'Linux: ' . implode( ', ', $data['linux_distros'] );
	}
	$handling_items = array();
	$handling_items[] = 'Drag and drop: ' . Cafe_Moxie_Site_Kit::render_bool( $data['accepts_drag_drop'] );
	$handling_items[] = 'Batch processing: ' . Cafe_Moxie_Site_Kit::render_bool( $data['batch_processing'] );
	$handling_items[] = 'Folder processing: ' . Cafe_Moxie_Site_Kit::render_bool( $data['folder_processing'] );
	$handling_items[] = 'Preserves metadata: ' . Cafe_Moxie_Site_Kit::render_bool( $data['preserves_metadata'] );
	$handling_items[] = 'Creates backup: ' . Cafe_Moxie_Site_Kit::render_bool( $data['creates_backup'] );
	$handling_items[] = 'Destructive operation: ' . Cafe_Moxie_Site_Kit::render_bool( $data['destructive'] );
	if ( $data['max_file_size'] ) {
		$handling_items[] = 'Max file size: ' . $data['max_file_size'];
	}
	if ( $data['install_method'] ) {
		$handling_items[] = 'Install method: ' . $data['install_method'];
	}
	if ( $data['shell_type'] ) {
		$handling_items[] = 'Runtime: ' . $data['shell_type'];
	}
	$security_items = array();
	$security_items[] = 'Processes locally: ' . Cafe_Moxie_Site_Kit::render_bool( $data['processes_locally'] );
	$security_items[] = 'Uploads to cloud: ' . Cafe_Moxie_Site_Kit::render_bool( $data['uploads_to_cloud'] );
	$security_items[] = 'Stores user files: ' . Cafe_Moxie_Site_Kit::render_bool( $data['stores_user_files'] );
	$security_items[] = 'Requires API key: ' . Cafe_Moxie_Site_Kit::render_bool( $data['requires_api_key'] );
	$security_items[] = 'Sensitive content warning: ' . Cafe_Moxie_Site_Kit::render_bool( $data['sensitive_warning'] );
	if ( $data['privacy_note'] ) {
		$security_items[] = 'Privacy note: ' . $data['privacy_note'];
	}
	if ( $data['data_retention'] ) {
		$security_items[] = 'Retention note: ' . $data['data_retention'];
	}
	if ( ! empty( $data['third_party'] ) ) {
		$security_items[] = 'Third-party services: ' . implode( ', ', $data['third_party'] );
	}
	$cta_mode = $data['download_url'] ? 'Download Tool' : 'View Details';
	?>
	<main class="cm-single-page">
		<div class="cm-wrap">
			<section class="cm-grid-2 cm-section">
				<div class="cm-panel">
					<div class="cm-chip-list" style="margin-bottom:14px;">
						<span class="cm-badge">Edge Tool</span>
						<?php if ( $data['buying_model'] ) : ?><span class="cm-status cm-status--warm"><?php echo esc_html( $data['buying_model'] ); ?></span><?php endif; ?>
						<?php if ( $data['execution_mode'] ) : ?><span class="cm-status <?php echo false !== stripos( $data['execution_mode'], 'Compute' ) ? 'cm-status--compute' : ''; ?>"><?php echo esc_html( $data['execution_mode'] ); ?></span><?php endif; ?>
						<?php if ( $data['release_status'] ) : ?><span class="cm-chip"><?php echo esc_html( $data['release_status'] ); ?></span><?php endif; ?>
					</div>
					<h1 class="cm-sign-title"><?php echo esc_html( $data['title'] ); ?></h1>
					<?php if ( $data['short_tagline'] ) : ?><div style="color:var(--moxie-amber);font-weight:700;margin-top:6px;"><?php echo esc_html( $data['short_tagline'] ); ?></div><?php endif; ?>
					<p class="cm-subtle"><?php echo esc_html( $data['one_line_value'] ? $data['one_line_value'] : $data['tool_summary'] ); ?></p>
					<div class="cm-chip-list" style="margin:16px 0 0;">
						<?php foreach ( array_merge( $data['taxonomies']['tool_type'], $data['taxonomies']['workflow_area'] ) as $chip ) : ?>
							<span class="cm-chip"><?php echo esc_html( $chip ); ?></span>
						<?php endforeach; ?>
					</div>
					<div style="display:flex;flex-wrap:wrap;gap:12px;margin-top:18px;">
						<?php if ( $data['download_url'] ) : ?><a class="cm-button" href="<?php echo esc_url( $data['download_url'] ); ?>">Download Tool</a><?php endif; ?>
						<?php if ( $data['compute_run_url'] ) : ?><a class="cm-button cm-button--secondary" href="<?php echo esc_url( $data['compute_run_url'] ); ?>">Use Compute</a><?php endif; ?>
						<?php if ( ! $data['download_url'] && ! $data['compute_run_url'] ) : ?><a class="cm-button" href="#tool-details"><?php echo esc_html( $cta_mode ); ?></a><?php endif; ?>
					</div>
					<div class="cm-note"><?php echo esc_html( $data['trust_cue'] ); ?></div>
				</div>
				<div class="cm-panel">
					<?php if ( $data['hero_image'] ) : ?>
						<div class="cm-media-frame"><img src="<?php echo esc_url( $data['hero_image'] ); ?>" alt="<?php echo esc_attr( $data['title'] ); ?>" style="display:block;width:100%;height:auto;"></div>
					<?php else : ?>
						<div class="cm-placeholder"><span class="cm-badge cm-status--warm">Add hero image</span><h2 class="cm-sign-title" style="font-size:50px;">Tool media slot</h2><p class="cm-subtle">Use the SCF hero image field or a featured image for a stronger product page.</p></div>
					<?php endif; ?>
					<div class="cm-kv-grid" style="margin-top:18px;">
						<div class="cm-kv"><div class="cm-kv__label">Price</div><div class="cm-kv__value"><?php echo esc_html( $data['price_display'] ? $data['price_display'] : 'See details' ); ?></div></div>
						<div class="cm-kv"><div class="cm-kv__label">Automation</div><div class="cm-kv__value"><?php echo esc_html( $data['automation_level'] ? $data['automation_level'] : 'Manual' ); ?></div></div>
						<div class="cm-kv"><div class="cm-kv__label">Runtime</div><div class="cm-kv__value"><?php echo esc_html( $data['typical_runtime'] ? $data['typical_runtime'] : 'Not specified' ); ?></div></div>
						<div class="cm-kv"><div class="cm-kv__label">Platforms</div><div class="cm-kv__value"><?php echo esc_html( $data['platform_line'] ? $data['platform_line'] : 'See compatibility below' ); ?></div></div>
					</div>
				</div>
			</section>

			<section id="tool-details" class="cm-section-stack cm-section">
				<div class="cm-panel">
					<div class="cm-meta-grid">
						<div>
							<h2 class="cm-sign-title">What's the annoying task?</h2>
							<p class="cm-subtle"><?php echo esc_html( $data['primary_task'] ? $data['primary_task'] : ( $data['short_tagline'] ? $data['short_tagline'] : $data['tool_summary'] ) ); ?></p>
							<h2 class="cm-sign-title" style="margin-top:20px;">Who deals with this?</h2>
							<?php echo Cafe_Moxie_Site_Kit::render_list( $who_deals ); ?>
							<h2 class="cm-sign-title" style="margin-top:20px;">What goes in?</h2>
							<?php echo Cafe_Moxie_Site_Kit::render_list( $what_in ); ?>
							<h2 class="cm-sign-title" style="margin-top:20px;">What comes out?</h2>
							<?php echo Cafe_Moxie_Site_Kit::render_list( $what_out ); ?>
						</div>
						<div>
							<h2 class="cm-sign-title">Local or compute?</h2>
							<p class="cm-subtle"><?php echo esc_html( $data['execution_mode'] ); ?></p>
							<h2 class="cm-sign-title" style="margin-top:20px;">How is it priced?</h2>
							<p class="cm-subtle"><?php echo esc_html( $data['price_display'] ? $data['price_display'] : 'Pricing not yet filled in.' ); ?></p>
							<?php if ( $data['trial_available'] ) : ?><p class="cm-subtle">Trial available.</p><?php endif; ?>
							<h2 class="cm-sign-title" style="margin-top:20px;">What still needs your judgment?</h2>
							<p class="cm-subtle"><?php echo esc_html( $data['trust_cue'] ); ?></p>
							<h2 class="cm-sign-title" style="margin-top:20px;">Why this saves you time</h2>
							<p class="cm-subtle"><?php echo esc_html( $data['one_line_value'] ? $data['one_line_value'] : $data['tool_summary'] ); ?></p>
							<h2 class="cm-sign-title" style="margin-top:20px;">Who else uses this?</h2>
							<?php echo Cafe_Moxie_Site_Kit::render_list( $who_else ); ?>
						</div>
					</div>
				</div>

				<?php if ( $data['how_it_works'] || ! empty( $data['secondary_tasks'] ) || ! empty( $data['not_for'] ) ) : ?>
				<div class="cm-panel">
					<h2 class="cm-sign-title">How it works</h2>
					<?php if ( $data['how_it_works'] ) : ?><div class="cm-subtle"><?php echo wp_kses_post( $data['how_it_works'] ); ?></div><?php endif; ?>
					<?php if ( ! empty( $data['secondary_tasks'] ) ) : ?>
						<h3 class="cm-sign-title" style="margin-top:18px;">Secondary tasks</h3>
						<?php echo Cafe_Moxie_Site_Kit::render_list( $data['secondary_tasks'] ); ?>
					<?php endif; ?>
					<?php if ( ! empty( $data['not_for'] ) ) : ?>
						<h3 class="cm-sign-title" style="margin-top:18px;">Not for</h3>
						<?php echo Cafe_Moxie_Site_Kit::render_list( $data['not_for'] ); ?>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<div class="cm-grid-2">
					<div class="cm-panel">
						<h2 class="cm-sign-title">Platform + compatibility</h2>
						<?php echo Cafe_Moxie_Site_Kit::render_list( $platform_items ); ?>
						<div class="cm-meta-grid" style="margin-top:18px;">
							<div><?php echo Cafe_Moxie_Site_Kit::render_meta_row( 'Runs locally', esc_html( Cafe_Moxie_Site_Kit::render_bool( $data['runs_local'] ) ) ); ?></div>
							<div><?php echo Cafe_Moxie_Site_Kit::render_meta_row( 'Internet required', esc_html( Cafe_Moxie_Site_Kit::render_bool( $data['internet_required'] ) ) ); ?></div>
							<div><?php echo Cafe_Moxie_Site_Kit::render_meta_row( 'Admin rights required', esc_html( Cafe_Moxie_Site_Kit::render_bool( $data['admin_required'] ) ) ); ?></div>
							<div><?php echo Cafe_Moxie_Site_Kit::render_meta_row( 'Portable tool', esc_html( Cafe_Moxie_Site_Kit::render_bool( $data['portable_tool'] ) ) ); ?></div>
						</div>
					</div>
					<div class="cm-panel">
						<h2 class="cm-sign-title">File handling</h2>
						<?php echo Cafe_Moxie_Site_Kit::render_list( $handling_items ); ?>
					</div>
				</div>

				<div class="cm-panel">
					<h2 class="cm-sign-title">Security + data handling</h2>
					<?php echo Cafe_Moxie_Site_Kit::render_list( $security_items ); ?>
				</div>

				<?php if ( ! empty( $data['gallery'] ) || ! empty( $data['before_after'] ) || $data['demo_video'] ) : ?>
				<div class="cm-panel">
					<h2 class="cm-sign-title">Media + proof</h2>
					<?php if ( $data['demo_video'] ) : ?><div class="cm-video-wrap"><?php echo wp_oembed_get( esc_url( $data['demo_video'] ) ); ?></div><?php endif; ?>
					<?php if ( ! empty( $data['gallery'] ) ) : ?>
						<div class="cm-gallery" style="margin-top:18px;">
							<?php foreach ( $data['gallery'] as $url ) : ?><figure class="cm-media-frame"><img src="<?php echo esc_url( $url ); ?>" alt="<?php echo esc_attr( $data['title'] ); ?> gallery image"></figure><?php endforeach; ?>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $data['before_after'] ) ) : ?>
						<div class="cm-section-stack" style="margin-top:18px;">
							<?php foreach ( $data['before_after'] as $pair ) :
								$before = Cafe_Moxie_Site_Kit::image_url( $pair['before_asset'] ?? '', 'large' );
								$after  = Cafe_Moxie_Site_Kit::image_url( $pair['after_asset'] ?? '', 'large' );
								$caption = $pair['caption'] ?? '';
							?>
								<div>
									<div class="cm-before-after">
										<div class="cm-media-frame"><?php if ( $before ) : ?><img src="<?php echo esc_url( $before ); ?>" alt="Before example"><?php else : ?><div class="cm-media-frame__placeholder">Before asset</div><?php endif; ?></div>
										<div class="cm-media-frame"><?php if ( $after ) : ?><img src="<?php echo esc_url( $after ); ?>" alt="After example"><?php else : ?><div class="cm-media-frame__placeholder">After asset</div><?php endif; ?></div>
									</div>
									<?php if ( $caption ) : ?><p class="cm-subtle" style="margin-top:10px;"><?php echo esc_html( $caption ); ?></p><?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<?php if ( trim( wp_strip_all_tags( $data['content'] ) ) ) : ?>
				<div class="cm-panel">
					<h2 class="cm-sign-title">Additional notes</h2>
					<div class="cm-subtle"><?php echo wp_kses_post( $data['content'] ); ?></div>
				</div>
				<?php endif; ?>
			</section>
		</div>
	</main>
<?php endwhile; ?>
<?php get_footer(); ?>
