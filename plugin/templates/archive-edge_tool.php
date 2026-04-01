<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
$query = Cafe_Moxie_Site_Kit::archive_query();
$settings = Cafe_Moxie_Site_Kit::settings();
?>
<main class="cm-archive-page">
	<div class="cm-wrap">
		<section class="cm-panel cm-section">
			<div class="cm-eyebrow">Browse the Counter</div>
			<h1 class="cm-sign-title">Tools for people who actually do the work.</h1>
			<p class="cm-subtle">Cafe Moxie is Fabled Sky's worker-first software counter for local tools and compute-backed utilities built for real digital work.</p>
			<?php if ( ! empty( $settings['show_archive_filters'] ) ) : ?>
				<form class="cm-section" method="get" action="<?php echo esc_url( get_post_type_archive_link( 'edge_tool' ) ); ?>">
					<div class="cm-filter-bar">
						<div>
							<label for="cm_search">Search</label>
							<input type="search" id="cm_search" name="cm_search" value="<?php echo esc_attr( Cafe_Moxie_Site_Kit::request_value( 'cm_search' ) ); ?>" placeholder="Search tools, tasks, formats">
						</div>
						<?php
						foreach ( Cafe_Moxie_Site_Kit::archive_filters() as $taxonomy => $label ) {
							echo Cafe_Moxie_Site_Kit::archive_filter_select( $taxonomy, $label );
						}
						?>
						<div>
							<label for="cm_mode">Mode</label>
							<select id="cm_mode" name="cm_mode">
								<option value="">All</option>
								<option value="local" <?php selected( Cafe_Moxie_Site_Kit::request_value( 'cm_mode' ), 'local' ); ?>>Runs Local</option>
								<option value="compute" <?php selected( Cafe_Moxie_Site_Kit::request_value( 'cm_mode' ), 'compute' ); ?>>Uses Compute Credits</option>
								<option value="hybrid" <?php selected( Cafe_Moxie_Site_Kit::request_value( 'cm_mode' ), 'hybrid' ); ?>>Local + Optional Compute</option>
							</select>
						</div>
					</div>
					<div class="cm-filter-actions">
						<label style="display:inline-flex;align-items:center;gap:8px;color:var(--moxie-cream);font-weight:700;"><input type="checkbox" name="cm_featured" value="1" <?php checked( Cafe_Moxie_Site_Kit::request_value( 'cm_featured' ), '1' ); ?>> Worker Favorites only</label>
						<button class="cm-button" type="submit">Apply Filters</button>
						<a class="cm-button cm-button--subtle" href="<?php echo esc_url( get_post_type_archive_link( 'edge_tool' ) ); ?>">Reset</a>
					</div>
				</form>
			<?php endif; ?>
			<div class="cm-query-summary">
				<div class="cm-subtle"><?php echo esc_html( intval( $query->found_posts ) ); ?> tool<?php echo 1 === intval( $query->found_posts ) ? '' : 's'; ?> found.</div>
				<div class="cm-chip-list"><span class="cm-chip">Buy Once</span><span class="cm-chip">Pay Per Task</span><span class="cm-chip">Built for repetitive digital work</span></div>
			</div>
		</section>

		<?php if ( $query->have_posts() ) : ?>
			<section class="cm-archive-tools">
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<?php echo Cafe_Moxie_Site_Kit::render_tool_card( get_the_ID() ); ?>
				<?php endwhile; ?>
			</section>
			<div class="cm-pagination">
				<?php echo wp_kses_post( Cafe_Moxie_Site_Kit::pagination_links( $query ) ); ?>
			</div>
		<?php else : ?>
			<section class="cm-panel cm-empty-state cm-section">
				<h2 class="cm-sign-title">Nothing matched that pass.</h2>
				<p class="cm-subtle">Try a wider search or clear the filters. As soon as Edge Tool posts are published, they will render here automatically.</p>
			</section>
		<?php endif; ?>
	</div>
</main>
<?php wp_reset_postdata(); ?>
<?php get_footer(); ?>
