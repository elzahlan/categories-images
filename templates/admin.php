<div class="wrap zci-settings-wrap">
    <h1><?php _e('Categories Images', 'categories-images'); ?></h1>
    
    <div class="zci-container">
        <!-- Main Settings Column -->
        <div class="zci-main-column">
            
            <form method="post" action="options.php" class="zci-card">
                <h2><?php _e('General Settings', 'categories-images'); ?></h2>
                <p class="description"><?php _e('Configure which taxonomies should be excluded from having images.', 'categories-images'); ?></p>
                
                <?php settings_fields('zci_options'); ?>
                <?php do_settings_sections('zci-options'); ?>
                
                <div class="zci-submit-section">
                    <?php submit_button(); ?>
                </div>
            </form>

            <div class="zci-card zci-documentation">
                <h2><?php _e('Shortcode Usage Guide', 'categories-images'); ?></h2>
                <p><?php _e('Use these shortcodes to display taxonomy images anywhere on your site.', 'categories-images'); ?></p>
                
                <hr>

                <h3>1. <?php _e('Single Term Image', 'categories-images'); ?>: <code>[z_taxonomy_image]</code></h3>
                <p><?php _e('Displays the image for a specific term (Category, Tag, etc).', 'categories-images'); ?></p>
                
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
                            <td>Fallback image URL if none exists.</td>
                            <td>Placeholder</td>
                            <td><code>default="http://..."</code></td>
                        </tr>
                        <tr>
                            <td><code>format</code></td>
                            <td>Output format (img, url).</td>
                            <td>img</td>
                            <td><code>format="url"</code></td>
                        </tr>
                    </tbody>
                </table>
                <p><strong><?php _e('Example', 'categories-images'); ?>:</strong> <code>[z_taxonomy_image term_id="5" size="medium" link="yes"]</code></p>

                <hr>

                <h3>2. <?php _e('Taxonomy List', 'categories-images'); ?>: <code>[z_taxonomy_list]</code></h3>
                <p><?php _e('Displays a list of terms with their images.', 'categories-images'); ?></p>
                
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
                <p><strong><?php _e('Example (Grid)', 'categories-images'); ?>:</strong> <code>[z_taxonomy_list style="grid" columns="4" show_name="yes"]</code></p>
                <p><strong><?php _e('Example (Current Post Tags)', 'categories-images'); ?>:</strong> <code>[z_taxonomy_list taxonomy="post_tag" post_id="current" style="inline"]</code></p>

            </div>
        </div>

        <!-- Sidebar (Optional, maybe for support/links if needed later) -->
    </div>
</div>