<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// PHP logic for initial state (fallback and initial load)
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'settings';
$active_subtab = isset($_GET['subtab']) ? $_GET['subtab'] : 'php';
?>
<div class="wrap zci-settings-wrap">
    <h1>
        <?php esc_html_e('Categories Images', 'categories-images'); ?>
        <span class="zci-version-tag">v<?php echo esc_html(ZCI_VERSION); ?></span>
    </h1>

    <h2 class="nav-tab-wrapper zci-main-tabs">
        <a href="?page=zci_settings&tab=settings" class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>" data-tab="settings"><?php esc_html_e('Settings', 'categories-images'); ?></a>
        <a href="?page=zci_settings&tab=documentation" class="nav-tab <?php echo $active_tab == 'documentation' ? 'nav-tab-active' : ''; ?>" data-tab="documentation"><?php esc_html_e('Documentation', 'categories-images'); ?></a>
    </h2>
    
    <div class="zci-container">
        <!-- Settings Tab Content -->
        <div id="zci-tab-settings" class="zci-tab-content <?php echo $active_tab == 'settings' ? 'active' : ''; ?>">
            <div class="zci-main-column">
                <form method="post" action="options.php" class="zci-card">
                    <h2><?php esc_html_e('General Settings', 'categories-images'); ?></h2>
                    <p class="description"><?php esc_html_e('Configure which taxonomies should be excluded from having images.', 'categories-images'); ?></p>
                    
                    <?php settings_fields('zci_options'); ?>
                    <?php do_settings_sections('zci-options'); ?>
                    
                    <div class="zci-submit-section">
                        <?php submit_button(); ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Documentation Tab Content -->
        <div id="zci-tab-documentation" class="zci-tab-content <?php echo $active_tab == 'documentation' ? 'active' : ''; ?>">
            <div class="zci-main-column">
                <div class="zci-card zci-documentation">
                    <h2 class="nav-tab-wrapper zci-sub-tabs">
                        <a href="?page=zci_settings&tab=documentation&subtab=php" class="nav-tab <?php echo $active_subtab == 'php' ? 'nav-tab-active' : ''; ?>" data-subtab="php"><?php esc_html_e('PHP Usage', 'categories-images'); ?></a>
                        <a href="?page=zci_settings&tab=documentation&subtab=shortcodes" class="nav-tab <?php echo $active_subtab == 'shortcodes' ? 'nav-tab-active' : ''; ?>" data-subtab="shortcodes"><?php esc_html_e('Shortcodes', 'categories-images'); ?></a>
                    </h2>

                    <!-- PHP Sub-tab Content -->
                    <div id="zci-subtab-php" class="zci-subtab-content <?php echo $active_subtab == 'php' ? 'active' : ''; ?>">
                        <h2><?php esc_html_e('PHP Usage and Documentation', 'categories-images'); ?></h2>
                        <p><?php echo wp_kses_post( __('To show the category image, use the following code in your <code>category.php</code>, <code>taxonomy.php</code>, or any other template file:', 'categories-images') ); ?></p>
                        
                        <pre><code>&lt;img src="&lt;?php echo z_taxonomy_image_url(); ?&gt;" /&gt;</code></pre>
                        
                        <p><?php esc_html_e('Or simply:', 'categories-images'); ?></p>
                        <pre><code>&lt;?php if (function_exists('z_taxonomy_image')) z_taxonomy_image(); ?&gt;</code></pre>

                        <hr>

                        <h3><?php esc_html_e('Functions Reference', 'categories-images'); ?></h3>
                        
                        <div class="zci-function-doc">
                            <h4><code>z_taxonomy_image_url($term_id = NULL, $size = 'full', $return_placeholder = FALSE)</code></h4>
                            <p><?php esc_html_e('Returns the taxonomy image URL as a string.', 'categories-images'); ?></p>
                            <ul>
                                <li><strong><code>$term_id</code></strong>: <?php esc_html_e('The category or taxonomy ID (default NULL).', 'categories-images'); ?></li>
                                <li><strong><code>$size</code></strong>: <?php esc_html_e('Image size (default \'full\').', 'categories-images'); ?></li>
                                <li><strong><code>$return_placeholder</code></strong>: <?php esc_html_e('Whether to return a placeholder image if no image is set (default FALSE).', 'categories-images'); ?></li>
                            </ul>
                        </div>

                        <div class="zci-function-doc">
                            <h4><code>z_taxonomy_image($term_id = NULL, $size = 'full', $attr = NULL, $echo = TRUE)</code></h4>
                            <p><?php esc_html_e('Returns the category or taxonomy image as HTML.', 'categories-images'); ?></p>
                            <ul>
                                <li><strong><code>$term_id</code></strong>: <?php esc_html_e('The category or taxonomy ID (default NULL).', 'categories-images'); ?></li>
                                <li><strong><code>$size</code></strong>: <?php esc_html_e('Image size (default \'full\').', 'categories-images'); ?></li>
                                <li><strong><code>$attr</code></strong>: <?php esc_html_e('Array of HTML img tag attributes (default NULL). Supported: alt, class, height, width, title.', 'categories-images'); ?></li>
                                <li><strong><code>$echo</code></strong>: <?php esc_html_e('Whether to print out the HTML (default TRUE).', 'categories-images'); ?></li>
                            </ul>
                        </div>

                        <hr>

                        <h3><?php esc_html_e('PHP Examples', 'categories-images'); ?></h3>
                        
                        <p><strong><?php esc_html_e('Inside a category loop:', 'categories-images'); ?></strong></p>
<pre><code>&lt;ul&gt;
    &lt;?php foreach (get_categories() as $cat) : ?&gt;
    &lt;li&gt;
        &lt;img src="&lt;?php echo z_taxonomy_image_url($cat->term_id); ?&gt;" /&gt;
        &lt;a href="&lt;?php echo get_category_link($cat->term_id); ?&gt;"&gt;&lt;?php echo $cat->cat_name; ?&gt;&lt;/a&gt;
    &lt;/li&gt;
    &lt;?php endforeach; ?&gt;
&lt;/ul&gt;</code></pre>

                        <p><strong><?php esc_html_e('Inside a custom taxonomy loop:', 'categories-images'); ?></strong></p>
<pre><code>&lt;ul&gt;
    &lt;?php foreach (get_terms('your_taxonomy') as $cat) : ?&gt;
    &lt;li&gt;
        &lt;img src="&lt;?php echo z_taxonomy_image_url($cat->term_id); ?&gt;" /&gt;
        &lt;a href="&lt;?php echo get_term_link($cat->slug, 'your_taxonomy'); ?&gt;"&gt;&lt;?php echo $cat->name; ?&gt;&lt;/a&gt;
    &lt;/li&gt;
    &lt;?php endforeach; ?&gt;
&lt;/ul&gt;</code></pre>
                    </div>

                    <!-- Shortcodes Sub-tab Content -->
                    <div id="zci-subtab-shortcodes" class="zci-subtab-content <?php echo $active_subtab == 'shortcodes' ? 'active' : ''; ?>">
                        <h2><?php esc_html_e('Shortcode Usage Guide', 'categories-images'); ?></h2>
                        <p><?php esc_html_e('Use these shortcodes to display taxonomy images anywhere on your site.', 'categories-images'); ?></p>
                        
                        <hr>

                        <h3>1. <?php esc_html_e('Single Term Image', 'categories-images'); ?>: <code>[z_taxonomy_image]</code></h3>
                        <p><?php esc_html_e('Displays the image for a specific term (Category, Tag, etc).', 'categories-images'); ?></p>
                        
                        <table class="widefat striped">
                            <thead>
                                <tr>
                                    <th>Attribute</th>
                                    <th>Description</th>
                                    <th>Default</th>
                                    <th>Example</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>term_id</code></td>
                                    <td>ID of the term. Auto-detects on archive pages.</td>
                                    <td>Current</td>
                                    <td><code>term_id="12"</code></td>
                                </tr>
                                <tr>
                                    <td><code>taxonomy</code></td>
                                    <td>Taxonomy slug (category, post_tag).</td>
                                    <td>category</td>
                                    <td><code>taxonomy="post_tag"</code></td>
                                </tr>
                                <tr>
                                    <td><code>size</code></td>
                                    <td>Image size (thumbnail, medium, full).</td>
                                    <td>full</td>
                                    <td><code>size="thumbnail"</code></td>
                                </tr>
                                <tr>
                                    <td><code>link</code></td>
                                    <td>Link image to term archive? (yes, no, custom_url)</td>
                                    <td>no</td>
                                    <td><code>link="yes"</code></td>
                                </tr>
                                <tr>
                                    <td><code>class</code></td>
                                    <td>Custom CSS class for the image.</td>
                                    <td>Empty</td>
                                    <td><code>class="my-style"</code></td>
                                </tr>
                                <tr>
                                    <td><code>default</code></td>
                                    <td>Fallback image URL if none exists. Set <code>default=""</code> or just <code>default</code> for plugin placeholder.</td>
                                    <td>None</td>
                                    <td><code>default</code> or <code>default="https://..."</code></td>
                                </tr>
                                <tr>
                                    <td><code>format</code></td>
                                    <td>Output format (img, url).</td>
                                    <td>img</td>
                                    <td><code>format="url"</code></td>
                                </tr>
                            </tbody>
                        </table>
                        <p><strong><?php esc_html_e('Example', 'categories-images'); ?>:</strong> <code>[z_taxonomy_image term_id="5" size="medium" link="yes"]</code></p>
                        <p><strong><?php esc_html_e('Example (with placeholder)', 'categories-images'); ?>:</strong> <code>[z_taxonomy_image default]</code></p>
                        <p><strong><?php esc_html_e('PHP Example', 'categories-images'); ?>:</strong></p>
                        <pre><code>&lt;?php echo do_shortcode('[z_taxonomy_image term_id="5" size="medium" link="yes"]'); ?&gt;</code></pre>

                        <hr>

                        <h3>2. <?php esc_html_e('Taxonomy List', 'categories-images'); ?>: <code>[z_taxonomy_list]</code></h3>
                        <p><?php esc_html_e('Displays a list of terms with their images.', 'categories-images'); ?></p>
                        
                        <table class="widefat striped">
                            <thead>
                                <tr>
                                    <th>Attribute</th>
                                    <th>Description</th>
                                    <th>Default</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>taxonomy</code></td>
                                    <td>Taxonomy to list terms from.</td>
                                    <td>category</td>
                                </tr>
                                <tr>
                                    <td><code>post_id</code></td>
                                    <td>Get terms assigned to this Post. Use 'current' for active post.</td>
                                    <td>Empty</td>
                                </tr>
                                <tr>
                                    <td><code>include</code></td>
                                    <td>Comma-separated list of Term IDs to include.</td>
                                    <td>Empty</td>
                                </tr>
                                <tr>
                                    <td><code>exclude</code></td>
                                    <td>Comma-separated list of Term IDs to exclude.</td>
                                    <td>Empty</td>
                                </tr>
                                <tr>
                                    <td><code>parent</code></td>
                                    <td>Parent Term ID (0 for top-level).</td>
                                    <td>Empty</td>
                                </tr>
                                <tr>
                                    <td><code>orderby</code></td>
                                    <td>Sort by (name, count, id, slug).</td>
                                    <td>name</td>
                                </tr>
                                <tr>
                                    <td><code>order</code></td>
                                    <td>Sort order (ASC, DESC).</td>
                                    <td>ASC</td>
                                </tr>
                                <tr>
                                    <td><code>hide_empty</code></td>
                                    <td>Hide terms with no posts? (yes/no)</td>
                                    <td>yes</td>
                                </tr>
                                <tr>
                                    <td><code>size</code></td>
                                    <td>Image size name.</td>
                                    <td>full</td>
                                </tr>
                                <tr>
                                    <td><code>style</code></td>
                                    <td>Layout style: <code>list</code>, <code>grid</code>, <code>inline</code>.</td>
                                    <td>list</td>
                                </tr>
                                <tr>
                                    <td><code>columns</code></td>
                                    <td>Number of columns (for grid style).</td>
                                    <td>3</td>
                                </tr>
                                <tr>
                                    <td><code>show_name</code></td>
                                    <td>Show term name? (yes/no)</td>
                                    <td>no</td>
                                </tr>
                                <tr>
                                    <td><code>show_count</code></td>
                                    <td>Show post count? (yes/no)</td>
                                    <td>no</td>
                                </tr>
                                <tr>
                                    <td><code>format</code></td>
                                    <td>Output format (img, array).</td>
                                    <td>img</td>
                                </tr>
                            </tbody>
                        </table>
                        <p><strong><?php esc_html_e('Example (Grid)', 'categories-images'); ?>:</strong> <code>[z_taxonomy_list style="grid" columns="4" show_name="yes"]</code></p>
                        <p><strong><?php esc_html_e('Example (Current Post Tags)', 'categories-images'); ?>:</strong> <code>[z_taxonomy_list taxonomy="post_tag" post_id="current" style="inline"]</code></p>
                        <p><strong><?php esc_html_e('PHP Example', 'categories-images'); ?>:</strong></p>
                        <pre><code>&lt;?php echo do_shortcode('[z_taxonomy_list style="grid" columns="4" show_name="yes"]'); ?&gt;</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="zci-footer">
        <p><?php /* translators: %s: author link */ printf(wp_kses_post(__('Plugin developed and supported by %s', 'categories-images')), '<a href="https://zahlan.net" target="_blank">Zahlan</a>'); ?></p>
    </footer>
</div>

<script type="text/javascript">
(function() {
    const mainTabs = document.querySelectorAll('.zci-main-tabs .nav-tab');
    const subTabs = document.querySelectorAll('.zci-sub-tabs .nav-tab');
    const tabContents = document.querySelectorAll('.zci-tab-content');
    const subtabContents = document.querySelectorAll('.zci-subtab-content');

    function updateURL(params) {
        const url = new URL(window.location);
        Object.keys(params).forEach(key => {
            if (params[key]) {
                url.searchParams.set(key, params[key]);
            } else {
                url.searchParams.delete(key);
            }
        });
        window.history.replaceState({}, '', url);
    }

    function switchTab(tabId) {
        mainTabs.forEach(tab => {
            tab.classList.toggle('nav-tab-active', tab.dataset.tab === tabId);
        });
        tabContents.forEach(content => {
            content.classList.toggle('active', content.id === `zci-tab-${tabId}`);
        });
        updateURL({ tab: tabId });
    }

    function switchSubtab(subtabId) {
        subTabs.forEach(tab => {
            tab.classList.toggle('nav-tab-active', tab.dataset.subtab === subtabId);
        });
        subtabContents.forEach(content => {
            content.classList.toggle('active', content.id === `zci-subtab-${subtabId}`);
        });
        updateURL({ subtab: subtabId });
    }

    mainTabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            switchTab(this.dataset.tab);
        });
    });

    subTabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            switchSubtab(this.dataset.subtab);
        });
    });
})();
</script>