<?php
$s = Cafe_Moxie_Site_Kit::settings();
$story = Cafe_Moxie_Site_Kit::resolve_url( $s['about_story_image'] );
?>
<!-- wp:group {"className":"cm-wrap","layout":{"type":"constrained"}} -->
<div class="wp-block-group cm-wrap">

<!-- wp:group {"className":"cm-grid-2 cm-section","layout":{"type":"default"}} -->
<div class="wp-block-group cm-grid-2 cm-section">
<!-- wp:group {"className":"cm-panel","layout":{"type":"constrained"}} -->
<div class="wp-block-group cm-panel">
<?php echo Cafe_Moxie_Site_Kit::render_brand_mark(); ?>
<!-- wp:paragraph --><p class="cm-eyebrow">About Cafe Moxie</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"className":"cm-sign-title"} --><h1 class="wp-block-heading cm-sign-title">A worker refuge with sharper tools.</h1><!-- /wp:heading -->
<!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Cafe Moxie is for the people who learned computers by doing the work. The people who know where the files are, who clean up the formatting, who rename, convert, package, extract, patch, proof, and send.</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">You are not a workflow. You are not a bottleneck. You are not a process to be optimized. You are the reason the work gets done at all.</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Sometimes you just need a tool that takes a stubborn task and makes it manageable. That's what the counter is for.</p><!-- /wp:paragraph -->
<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( Cafe_Moxie_Site_Kit::resolve_url( $s['about_primary_url'] ) ); ?>"><?php echo esc_html( $s['about_primary_cta'] ); ?></a></div><!-- /wp:button --></div><!-- /wp:buttons -->
</div><!-- /wp:group -->
<!-- wp:group {"className":"cm-panel","layout":{"type":"constrained"}} -->
<div class="wp-block-group cm-panel">
<?php if ( $story ) : ?>
<!-- wp:image {"sizeSlug":"large"} --><figure class="wp-block-image size-large"><img src="<?php echo esc_url( $story ); ?>" alt="Cafe Moxie about image" /></figure><!-- /wp:image -->
<?php else : ?>
<!-- wp:html --><div class="cm-placeholder"><span class="cm-badge cm-status--warm">Add about image</span><h2 class="cm-sign-title" style="font-size:50px;">About image slot</h2><p class="cm-subtle">Use a storefront scene, branded sign, or image that shows the Cafe Moxie mood.</p></div><!-- /wp:html -->
<?php endif; ?>
</div><!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"cm-grid-3 cm-section","layout":{"type":"default"}} -->
<div class="wp-block-group cm-grid-3 cm-section">
<!-- wp:group {"className":"cm-card","layout":{"type":"constrained"}} --><div class="wp-block-group cm-card"><!-- wp:paragraph --><p class="cm-badge">Voice</p><!-- /wp:paragraph --><!-- wp:heading {"level":3,"className":"cm-sign-title"} --><h3 class="wp-block-heading cm-sign-title">Grounded. Capable. Specific.</h3><!-- /wp:heading --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Cafe Moxie should sound like the most competent person in the office if that person opened a software counter and started selling the tools they actually wished existed.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group {"className":"cm-card","layout":{"type":"constrained"}} --><div class="wp-block-group cm-card"><!-- wp:paragraph --><p class="cm-badge">Visual Direction</p><!-- /wp:paragraph --><!-- wp:heading {"level":3,"className":"cm-sign-title"} --><h3 class="wp-block-heading cm-sign-title">Signal, not noise.</h3><!-- /wp:heading --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Dark industrial neutrals, cyan and teal signal glow, and small pockets of amber warmth. Interface first, decoration second. Alive, not chaotic.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group {"className":"cm-card","layout":{"type":"constrained"}} --><div class="wp-block-group cm-card"><!-- wp:paragraph --><p class="cm-badge">Commerce</p><!-- /wp:paragraph --><!-- wp:heading {"level":3,"className":"cm-sign-title"} --><h3 class="wp-block-heading cm-sign-title">Own it when you want ownership.</h3><!-- /wp:heading --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Buy once for local tools. Pay per task for compute-backed workflows. Keep ownership and control with the worker whenever possible.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"cm-grid-2 cm-section","layout":{"type":"default"}} -->
<div class="wp-block-group cm-grid-2 cm-section">
<!-- wp:group {"className":"cm-panel","layout":{"type":"constrained"}} -->
<div class="wp-block-group cm-panel">
<!-- wp:paragraph --><p class="cm-eyebrow">What Cafe Moxie Is Not</p><!-- /wp:paragraph -->
<!-- wp:list {"className":"cm-list"} --><ul class="cm-list"><li>Not enterprise AI transformation software.</li><li>Not a workforce replacement platform.</li><li>Not a generic AI marketplace.</li><li>Not a dashboard pretending to be a tool.</li><li>Not a subscription treadmill.</li></ul><!-- /wp:list -->
</div><!-- /wp:group -->
<!-- wp:group {"className":"cm-panel","layout":{"type":"constrained"}} -->
<div class="wp-block-group cm-panel">
<!-- wp:paragraph --><p class="cm-eyebrow">Final Calibration</p><!-- /wp:paragraph -->
<!-- wp:list {"className":"cm-list"} --><ul class="cm-list"><li>70% practical competence</li><li>20% recognition and solidarity</li><li>10% rebellion</li></ul><!-- /wp:list -->
<!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">If the brand becomes more corporate than human, it is wrong. If it becomes more aesthetic than useful, it is wrong.</p><!-- /wp:paragraph -->
</div><!-- /wp:group -->
</div>
<!-- /wp:group -->

</div>
<!-- /wp:group -->
