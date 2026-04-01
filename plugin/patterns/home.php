<?php
$s = Cafe_Moxie_Site_Kit::settings();
$hero  = Cafe_Moxie_Site_Kit::resolve_url( $s['home_hero_image'] );
$story = Cafe_Moxie_Site_Kit::resolve_url( $s['home_story_image'] );
?>
<!-- wp:group {"className":"cm-wrap","layout":{"type":"constrained"}} -->
<div class="wp-block-group cm-wrap">

<!-- wp:group {"className":"cm-grid-2 cm-hero cm-section","layout":{"type":"default"}} -->
<div class="wp-block-group cm-grid-2 cm-hero cm-section">

<!-- wp:group {"className":"cm-panel","layout":{"type":"constrained"}} -->
<div class="wp-block-group cm-panel">
<?php echo Cafe_Moxie_Site_Kit::render_brand_mark(); ?>
<!-- wp:paragraph --><p class="cm-badge">Tools for people who actually do the work.</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"className":"cm-sign-title"} --><h1 class="wp-block-heading cm-sign-title">Tools for people who actually do the work.</h1><!-- /wp:heading -->
<!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Cafe Moxie is Fabled Sky's worker-first software shop: focused desktop tools and compute-backed utilities for the repetitive, annoying, real-world tasks that fill modern work.</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">No bloated platforms. No forced subscriptions. Just useful leverage.</p><!-- /wp:paragraph -->
<!-- wp:html --><div class="cm-chip-list"><span class="cm-chip">Worker-first</span><span class="cm-chip">Buy once</span><span class="cm-chip">Pay per task</span><span class="cm-chip">No platform bloat</span></div><!-- /wp:html -->
<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( Cafe_Moxie_Site_Kit::resolve_url( $s['home_primary_url'] ) ); ?>"><?php echo esc_html( $s['home_primary_cta'] ); ?></a></div><!-- /wp:button --><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button cm-button--secondary" href="<?php echo esc_url( Cafe_Moxie_Site_Kit::resolve_url( $s['home_secondary_url'] ) ); ?>"><?php echo esc_html( $s['home_secondary_cta'] ); ?></a></div><!-- /wp:button --></div><!-- /wp:buttons -->
<!-- wp:html --><div class="cm-note">Pull up a chair. The tools are ready.</div><!-- /wp:html -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"cm-panel","layout":{"type":"constrained"}} -->
<div class="wp-block-group cm-panel">
<?php if ( $hero ) : ?>
<!-- wp:image {"sizeSlug":"large"} --><figure class="wp-block-image size-large"><img src="<?php echo esc_url( $hero ); ?>" alt="Cafe Moxie hero image" /></figure><!-- /wp:image -->
<?php else : ?>
<!-- wp:html --><div class="cm-placeholder"><span class="cm-badge cm-status--warm">Add hero image</span><h2 class="cm-sign-title" style="font-size:50px;">Hero image slot</h2><p class="cm-subtle">Paste a Media Library image URL into Cafe Moxie → Brand Media.</p></div><!-- /wp:html -->
<?php endif; ?>
<!-- wp:html --><div class="cm-stat-band"><div class="cm-stat"><div class="cm-stat__label">Buy Once</div><div class="cm-stat__value">Own the tool when you want ownership.</div></div><div class="cm-stat"><div class="cm-stat__label">Pay Per Task</div><div class="cm-stat__value">Use compute when setup is not worth it.</div></div><div class="cm-stat"><div class="cm-stat__label">Hybrid</div><div class="cm-stat__value">Keep the local edge. Rent the heavy lift.</div></div><div class="cm-stat"><div class="cm-stat__label">Built For</div><div class="cm-stat__value">Operations, admin, research, writing, cleanup.</div></div></div><!-- /wp:html -->
</div>
<!-- /wp:group -->

</div>
<!-- /wp:group -->

<!-- wp:group {"className":"cm-grid-2 cm-section","layout":{"type":"default"}} -->
<div class="wp-block-group cm-grid-2 cm-section">
<!-- wp:group {"className":"cm-panel","layout":{"type":"constrained"}} -->
<div class="wp-block-group cm-panel">
<!-- wp:paragraph --><p class="cm-eyebrow">What Cafe Moxie Is</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"className":"cm-sign-title"} --><h2 class="wp-block-heading cm-sign-title">A counter, not a corporation.</h2><!-- /wp:heading -->
<!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Cafe Moxie sells small, practical software tools for people whose jobs happen on computers.</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">That includes document cleanup, file conversion, extraction, formatting, packaging, writing support, image fixes, repetitive admin tasks, and all the tiny digital chores that somehow take half the day.</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Some tools run on your machine. Some tasks run on compute. Either way, the point is the same: help you move faster without handing over control.</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">We do not sell a platform. We sell tools, one at a time, each one useful on its own.</p><!-- /wp:paragraph -->
</div><!-- /wp:group -->
<!-- wp:group {"className":"cm-panel","layout":{"type":"constrained"}} -->
<div class="wp-block-group cm-panel">
<?php if ( $story ) : ?>
<!-- wp:image {"sizeSlug":"large"} --><figure class="wp-block-image size-large"><img src="<?php echo esc_url( $story ); ?>" alt="Cafe Moxie story image" /></figure><!-- /wp:image -->
<?php else : ?>
<!-- wp:html --><div class="cm-placeholder"><span class="cm-badge cm-status--warm">Add story image</span><h2 class="cm-sign-title" style="font-size:50px;">Story image slot</h2><p class="cm-subtle">Use this for a marquee sign, workshop scene, counter photo, or branded storefront art.</p></div><!-- /wp:html -->
<?php endif; ?>
</div><!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"cm-grid-3 cm-section","layout":{"type":"default"}} -->
<div class="wp-block-group cm-grid-3 cm-section">
<!-- wp:group {"className":"cm-card","layout":{"type":"constrained"}} --><div class="wp-block-group cm-card"><!-- wp:paragraph --><p class="cm-badge">Two Ways to Work</p><!-- /wp:paragraph --><!-- wp:heading {"level":3,"className":"cm-sign-title"} --><h3 class="wp-block-heading cm-sign-title">Buy Once</h3><!-- /wp:heading --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Buy once and run it on your desktop. No account required. No subscription. Best when you want ownership, repeat use, and full control.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group {"className":"cm-card","layout":{"type":"constrained"}} --><div class="wp-block-group cm-card"><!-- wp:paragraph --><p class="cm-badge">Two Ways to Work</p><!-- /wp:paragraph --><!-- wp:heading {"level":3,"className":"cm-sign-title"} --><h3 class="wp-block-heading cm-sign-title">Pay Per Task</h3><!-- /wp:heading --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Use Compute Credits when the task is heavier, more specialized, or not worth setting up locally. Pay for what runs. Best when you want convenience or one-off power.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:group {"className":"cm-card","layout":{"type":"constrained"}} --><div class="wp-block-group cm-card"><!-- wp:paragraph --><p class="cm-badge">Two Ways to Work</p><!-- /wp:paragraph --><!-- wp:heading {"level":3,"className":"cm-sign-title"} --><h3 class="wp-block-heading cm-sign-title">Hybrid</h3><!-- /wp:heading --><!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Own it when you want ownership. Run it when you just need the task done. Hybrid tools keep the local baseline and only light up compute when needed.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"cm-grid-2 cm-section","layout":{"type":"default"}} -->
<div class="wp-block-group cm-grid-2 cm-section">
<!-- wp:group {"className":"cm-panel","layout":{"type":"constrained"}} -->
<div class="wp-block-group cm-panel">
<!-- wp:paragraph --><p class="cm-eyebrow">Built for the People Who Keep Everything Running</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"className":"cm-sign-title"} --><h2 class="wp-block-heading cm-sign-title">For the person everyone asks for help.</h2><!-- /wp:heading -->
<!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Cafe Moxie is for operations people, assistants, researchers, analysts, editors, coordinators, and digital workers of all kinds who spend their days inside files, emails, forms, folders, spreadsheets, and documents.</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">If your job includes repetitive computer work, messy inputs, manual cleanup, or digital admin labor, and you have built real instincts about how to handle it, this shop is for you.</p><!-- /wp:paragraph -->
</div><!-- /wp:group -->
<!-- wp:group {"className":"cm-panel","layout":{"type":"constrained"}} -->
<div class="wp-block-group cm-panel">
<!-- wp:paragraph --><p class="cm-eyebrow">Workers Should Own the Leverage</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"className":"cm-sign-title"} --><h2 class="wp-block-heading cm-sign-title">AI is happening. Workers should own the leverage.</h2><!-- /wp:heading -->
<!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">AI is not going away. That does not mean workers should become dependent on bloated platforms or wait for enterprise software to decide how their jobs should function.</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">The people who do the actual work have earned the right to better tools, not tools that report on them, manage them, or slowly learn to replace them. Tools that make their existing skill go further.</p><!-- /wp:paragraph -->
</div><!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"cm-grid-2 cm-section","layout":{"type":"default"}} -->
<div class="wp-block-group cm-grid-2 cm-section">
<!-- wp:group {"className":"cm-panel","layout":{"type":"constrained"}} -->
<div class="wp-block-group cm-panel">
<!-- wp:paragraph --><p class="cm-eyebrow">What We Won't Do</p><!-- /wp:paragraph -->
<!-- wp:list {"className":"cm-trust-list"} --><ul class="cm-trust-list"><li>We won't harvest your documents to train models.</li><li>We won't gate basic functionality behind a subscription.</li><li>We won't pretend AI replaces your judgment.</li><li>Every product page tells you what still needs a human eye.</li><li>If a tool is not right for your task, we will say so.</li></ul><!-- /wp:list -->
</div><!-- /wp:group -->
<!-- wp:group {"className":"cm-panel","layout":{"type":"constrained"}} -->
<div class="wp-block-group cm-panel">
<!-- wp:paragraph --><p class="cm-eyebrow">Product Counter</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">Featured tools below read directly from your <strong>Edge Tools</strong> custom post type and the Secure Custom Fields schema powering it.</p><!-- /wp:paragraph -->
<!-- wp:shortcode -->[cafe_moxie_featured_edge_tools count="<?php echo esc_attr( $s['featured_tools_count'] ); ?>"]<!-- /wp:shortcode -->
</div><!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"cm-panel cm-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group cm-panel cm-section">
<!-- wp:paragraph --><p class="cm-eyebrow">Closing CTA</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":2,"className":"cm-sign-title"} --><h2 class="wp-block-heading cm-sign-title">Your next unfair advantage is probably a small tool.</h2><!-- /wp:heading -->
<!-- wp:paragraph {"className":"cm-subtle"} --><p class="cm-subtle">A little less friction. A little more control. A better tool for the job you already know how to do.</p><!-- /wp:paragraph -->
<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( Cafe_Moxie_Site_Kit::resolve_url( $s['home_primary_url'] ) ); ?>"><?php echo esc_html( $s['home_primary_cta'] ); ?></a></div><!-- /wp:button --></div><!-- /wp:buttons -->
</div>
<!-- /wp:group -->

</div>
<!-- /wp:group -->
