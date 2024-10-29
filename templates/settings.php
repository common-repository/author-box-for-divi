<div id="abfd-settings" class="wrap">
	<h2>
		<img src="<?= plugins_url('author-box-for-divi/images/logo-author-box-wplens-plugin.png') ?>"
			alt="<?php echo esc_attr('Author Box for Divi'); ?>" style="vertical-align: middle; width: 300px; height: auto;">
	</h2>

	<div class="abfd-preview-area">
		<?php
		// get author box html
		$html = ABFD::get_author_box_html('demo');
		echo $html;
		?>
	</div>

	<div class="abfd-shortcode-info">
		<?php if (!ABFD::$is_pro) : ?>
			<a href="https://wplens.com" target="_blank" class="button-primary abfd-pro">
				<?php _e('Pro', 'author-box-for-divi'); ?>
			</a>
		<?php endif; ?>
		<p>
			<?php _e('You can use the following shortcode to display the author box in your posts, pages, or custom post types:', 'author-box-for-divi'); ?>
		</p>
		<p>
			<code>[author-box-wp-lens id="user id | username"]</code>
		</p>
	</div>

	<!-- Tabs -->
	<div class="nav-tab-wrapper">
		<a href="#nav-tab-general" class="nav-tab nav-tab-active">
			<?php esc_html_e( 'General', 'author-box-for-divi' ); ?>
		</a>
		<a href="#nav-tab-styling" class="nav-tab">
			<?php esc_html_e( 'Styling', 'author-box-for-divi' ); ?>
		</a>
		<a href="#nav-tab-license" class="nav-tab">
			<?php esc_html_e( 'License', 'author-box-for-divi' ); ?>
		</a>
	</div>

	<form id="abfd-settings-form" action="<?= admin_url() ?>?page=abfd" method="post">

		<!-- General Settings -->
		<div id="nav-tab-general" class="nav-tab-content">
			<table class="form-table">
				<tr>
					<th><label for="abfd-option-name-prefix">
							<?php _e('Name Prefix', 'author-box-for-divi') ?>:
						</label></th>
					<td><input type="text" name="abfd-option-name-prefix" id="abfd-option-name-prefix"
							value="<?php echo esc_attr(get_option('abfd-option-name-prefix', '')); ?>" class="large-text"></td>
				</tr>

				<tr>
					<th><label for="abfd-option-disable-on-post-types">
							<?php _e('Disable On', 'author-box-for-divi') ?>:
						</label></th>
					<td>
						<select name="abfd-option-disable-on-post-types[]" id="abfd-option-disable-on-post-types" multiple>
							<?php
							$disable_on_post_types = (array) get_option('abfd-option-disable-on-post-types', array());

							$post_types = get_post_types(null, 'objects');

							foreach ($post_types as $post_type) {
								if ($post_type->public == 1) {
									?>
									<option value="<?php echo $post_type->name; ?>" <?php if (in_array($post_type->name, $disable_on_post_types))
												echo 'selected'; ?>>
										<?php echo $post_type->label; ?>
									</option>
									<?php
								}
							}
							?>
						</select>

						<p>
							<?php _e('Select the Post Types to disable the author box on.', 'author-box-for-divi'); ?>
						</p>
					</td>
				</tr>

				<tr>
					<th><label for="abfd-option-exclude-categories">
							<?php _e('Exclude Categories', 'author-box-for-divi') ?>:
						</label></th>
					<td>
						<select name="abfd-option-exclude-categories[]" id="abfd-option-exclude-categories" multiple>
							<?php
							$exclude_categories = (array) get_option('abfd-option-exclude-categories', array());

							$categories = get_categories(array('hide_empty' => false, 'fields' => 'id=>name'));

							foreach ($categories as $category_id => $category_name) {
								?>
								<option value="<?php echo $category_id; ?>" <?php if (in_array($category_id, $exclude_categories))
											echo 'selected'; ?>>
									<?php echo $category_name; ?>
								</option>
								<?php
							}
							?>
						</select>

						<p>
							<?php _e('Select the category you want to disable the author box on.', 'author-box-for-divi'); ?>
						</p>
					</td>
				</tr>

				<tr>
					<th>
						<?php if (!ABFD::$is_pro) : ?>
							<a href="https://wplens.com" target="_blank" class="button-primary">
								<?php _e('Pro', 'author-box-for-divi'); ?>
							</a>
						<?php endif; ?>
						<label for="abfd-option-author-box-position">
							<?php _e('Author Box Position', 'author-box-for-divi') ?>:
						</label>
					</th>
					<td>
						<?php if (ABFD::$is_pro) : ?>
							<?php $author_box_position = get_option('abfd-option-author-box-position', 'bottom'); ?>
							<select name="abfd-option-author-box-position" id="abfd-option-author-box-position">
								<option value="top" <?php selected('top', $author_box_position); ?>>
									<?php _e('Top of Content', 'author-box-for-divi'); ?>
								</option>
								<option value="bottom" <?php selected('bottom', $author_box_position); ?>>
									<?php _e('Bottom of Content', 'author-box-for-divi'); ?>
								</option>
							</select>
						<?php else : ?>
							<input type="hidden" name="abfd-option-author-box-position" value="bottom">
							<p>
								<?php _e('Upgrade to Pro to unlock this feature.', 'author-box-for-divi'); ?>
							</p>
						<?php endif; ?>
						<p>
							<?php _e('Select where you want the author box to appear.', 'author-box-for-divi'); ?>
						</p>
					</td>
				</tr>

				<tr>
					<th><label for="abfd-option-new-tab">
							<?php _e('Open Links in new Tab', 'author-box-for-divi') ?>:
						</label></th>
					<td>
						<input type="checkbox" name="abfd-option-new-tab" id="abfd-option-new-tab" value="1" <?= checked(1, get_option('abfd-option-new-tab', true)) ?>>
						<span>
							<?php _e('Check this box if you wants author box links to open in new tab', 'author-box-for-divi'); ?>
						</span>
					</td>
				</tr>

				<tr>
					<th>
						<?php if (!ABFD::$is_pro) : ?>
							<a href="https://wplens.com" target="_blank" class="button-primary">
								<?php _e('Pro', 'author-box-for-divi'); ?>
							</a>
						<?php endif; ?>
						<label for="abfd-option-author-box-position">
							<?php _e('Link Attributes', 'author-box-for-divi') ?>:
						</label>
					</th>
					<td>
						<?php $link_attrs = get_option('abfd-option-link-attributes', array()); ?>
						<select name="abfd-option-link-attributes[]" id="abfd-option-link-attributes" multiple <?php if (!ABFD::$is_pro) echo 'disabled'; ?>>
							<option value="nofollow" <?php if (in_array('nofollow', (array) $link_attrs)) echo 'selected'; ?>><?php _e('No follow', 'author-box-for-divi'); ?></option>
							<option value="noopener" <?php if (in_array('noopener', (array) $link_attrs)) echo 'selected'; ?>><?php _e('No opener', 'author-box-for-divi'); ?></option>
							<option value="noreferrer" <?php if (in_array('noreferrer', (array) $link_attrs)) echo 'selected'; ?>><?php _e('No referrer', 'author-box-for-divi'); ?></option>
							<option value="sponsored" <?php if (in_array('sponsored', (array) $link_attrs)) echo 'selected'; ?>><?php _e('Sponsored', 'author-box-for-divi'); ?></option>
							<option value="ugc" <?php if (in_array('ugc', (array) $link_attrs)) echo 'selected'; ?>><?php _e('UGC', 'author-box-for-divi'); ?></option>
							<option value="alternate" <?php if (in_array('alternate', (array) $link_attrs)) echo 'selected'; ?>><?php _e('Alternate', 'author-box-for-divi'); ?></option>
							<option value="author" <?php if (in_array('author', (array) $link_attrs)) echo 'selected'; ?>><?php _e('Author', 'author-box-for-divi'); ?></option>
							<option value="bookmark" <?php if (in_array('bookmark', (array) $link_attrs)) echo 'selected'; ?>><?php _e('Bookmark', 'author-box-for-divi'); ?></option>
							<option value="external" <?php if (in_array('external', (array) $link_attrs)) echo 'selected'; ?>><?php _e('External', 'author-box-for-divi'); ?></option>
							<option value="help" <?php if (in_array('help', (array) $link_attrs)) echo 'selected'; ?>><?php _e('Help', 'author-box-for-divi'); ?></option>
							<option value="license" <?php if (in_array('license', (array) $link_attrs)) echo 'selected'; ?>><?php _e('License', 'author-box-for-divi'); ?></option>
							<option value="next" <?php if (in_array('next', (array) $link_attrs)) echo 'selected'; ?>><?php _e('Next', 'author-box-for-divi'); ?></option>
							<option value="prev" <?php if (in_array('prev', (array) $link_attrs)) echo 'selected'; ?>><?php _e('Prev', 'author-box-for-divi'); ?></option>
							<option value="search" <?php if (in_array('search', (array) $link_attrs)) echo 'selected'; ?>><?php _e('Search', 'author-box-for-divi'); ?></option>
							<option value="tag" <?php if (in_array('tag', (array) $link_attrs)) echo 'selected'; ?>><?php _e('Tag', 'author-box-for-divi'); ?></option>
						</select>
						<p>
							<?php _e('Select the link attributes you want to add to the author box links.', 'author-box-for-divi'); ?>
						</p>
					</td>
				</tr>

				<tr>
					<th><label for="abfd-option-email-icon">
							<?php _e('Add email icon', 'author-box-for-divi') ?>:
						</label></th>
					<td>
						<input type="checkbox" name="abfd-option-email-icon" id="abfd-option-email-icon" value="1"
							<?= checked(1, get_option('abfd-option-email-icon', false)) ?>>
						<span>
							<?php _e('Check this box if you want to add email icon', 'author-box-for-divi'); ?>
						</span>
					</td>
				</tr>

				<tr>
					<th><label for="abfd-option-website-icon">
							<?php _e('Add website icon', 'author-box-for-divi') ?>:
						</label></th>
					<td>
						<input type="checkbox" name="abfd-option-website-icon" id="abfd-option-website-icon" value="1"
							<?= checked(1, get_option('abfd-option-website-icon', false)) ?>>
						<span>
							<?php _e('Check this box if you want to add website icon', 'author-box-for-divi'); ?>
						</span>
					</td>
				</tr>

				<tr>
					<th><label for="abfd-option-hyperlink-author-page">
							<?php _e('Hyperlink to author page', 'author-box-for-divi') ?>:
						</label></th>
					<td>
						<input type="checkbox" name="abfd-option-hyperlink-author-page"
							id="abfd-option-hyperlink-author-page" value="1" <?= checked(1, get_option('abfd-option-hyperlink-author-page', false)) ?>>
						<span>
							<?php _e('Check this box if you want hyperlink to author page', 'author-box-for-divi'); ?>
						</span>
					</td>
				</tr>

				<tr>
					<th>
						<?php if (!ABFD::$is_pro) : ?>
							<a href="https://wplens.com" target="_blank" class="button-primary">
								<?php _e('Pro', 'author-box-for-divi'); ?>
							</a>
							<?php endif; ?>
						<label for="abfd-option-multiple-authors">
							<?php _e('Enable multiple authors', 'author-box-for-divi') ?>:
						</label></th>
					<td>
						<input type="checkbox" name="abfd-option-multiple-authors"
							id="abfd-option-multiple-authors" value="1" <?= checked(1, get_option('abfd-option-multiple-authors', false)) ?>
							<?= disabled(1, !ABFD::$is_pro) ?>>
						<span>
							<?php _e('Check this box if you want to enable multiple authors', 'author-box-for-divi'); ?>
						</span>
					</td>
				</tr>

				<tr>
					<th>
						<?php if (!ABFD::$is_pro) : ?>
							<a href="https://wplens.com" target="_blank" class="button-primary">
								<?php _e('Pro', 'author-box-for-divi'); ?>
							</a>
							<?php endif; ?>
						<label for="abfd-option-guest-authors">
							<?php _e('Enable guest authors', 'author-box-for-divi') ?>:
						</label></th>
					<td>
						<input type="checkbox" name="abfd-option-guest-authors"
							id="abfd-option-guest-authors" value="1" <?= checked(1, get_option('abfd-option-guest-authors', false)) ?>
							<?= disabled(1, !ABFD::$is_pro) ?>>
						<span>
							<?php _e('Check this box if you want to enable guest authors', 'author-box-for-divi'); ?>
						</span>
					</td>
				</tr>

				<tr>
					<th>
						<?php if (!ABFD::$is_pro) : ?>
							<a href="https://wplens.com" target="_blank" class="button-primary">
								<?php _e('Pro', 'author-box-for-divi'); ?>
							</a>
							<?php endif; ?>
						<label for="abfd-option-author-posts-page-link">
							<?php _e('Enable Author Posts page link', 'author-box-for-divi') ?>:
						</label></th>
					<td>
						<input type="checkbox" name="abfd-option-author-posts-page-link"
							id="abfd-option-author-posts-page-link" value="1" <?= checked(1, get_option('abfd-option-author-posts-page-link', false)) ?>
							<?= disabled(1, !ABFD::$is_pro) ?>>
						<span>
							<?php _e('Check this box if you want to enable the Author Posts page link', 'author-box-for-divi'); ?>
						</span>
					</td>
				</tr>

			</table>

			<p class="submit"><input type="submit" name="abfd-submit"
				value="<?php _e('Save Settings', 'author-box-for-divi') ?>" class="button-primary"></p>
		</div>

		<!-- Styling Settings -->
		<div id="nav-tab-styling" class="nav-tab-content" style="display: none;">
			<table class="form-table">
				<tr><th colspan="2"><?php _e('BOX SETTINGS', 'author-box-for-divi'); ?></th></tr>
			<tr>
					<th><label for="abfd-option-background-color">
							<?php _e('Background Color', 'author-box-for-divi') ?>:
						</label></th>
					<td><input type="text" data-jscolor="{}" name="abfd-option-background-color"
							id="abfd-option-background-color"
							value="<?php echo esc_attr(get_option('abfd-option-background-color', '#fff')); ?>" class="large-text abfd-color-picker">
					</td>
				</tr>
				<tr>
					<th><label for="abfd-option-border-color">
							<?php _e('Border Color', 'author-box-for-divi') ?>:
						</label></th>
					<td><input type="text" data-jscolor="{}" name="abfd-option-border-color" id="abfd-option-border-color"
							value="<?php echo esc_attr(get_option('abfd-option-border-color', '#e2e2e2')); ?>" class="large-text abfd-color-picker"></td>
				</tr>

				<tr>
					<th>
						<?php if (!ABFD::$is_pro) : ?>
							<a href="https://wplens.com" target="_blank" class="button-primary">
								<?php _e('Pro', 'author-box-for-divi'); ?>
							</a>
							<?php endif; ?>
						<label for="abfd-option-border-size">
							<?php _e('Border Size', 'author-box-for-divi') ?>:
						</label></th>
					<td>
						<input type="number" name="abfd-option-border-size" id="abfd-option-border-size"
							value="<?php echo esc_attr(get_option('abfd-option-border-size', '1')); ?>" class="small-text"
							<?= disabled(1, !ABFD::$is_pro) ?>>px
					</td>
			</tr>

				<tr>
					<th><label for="abfd-option-border-radius">
							<?php _e('Border Radius', 'author-box-for-divi') ?>:
						</label></th>
					<td>
						<input type="number" name="abfd-option-border-radius" id="abfd-option-border-radius"
							value="<?php echo esc_attr(get_option('abfd-option-border-radius', 0)); ?>" class="small-text">px

						<p>
							<?php _e('To disable border radius, set this to 0 or leave it blank.', 'author-box-for-divi'); ?>
						</p>
					</td>
				</tr>

				<tr><th colspan="2"><?php _e('PROFILE PICTURE SETTINGS', 'author-box-for-divi'); ?></th></tr>

				<tr>
					<th>
						<?php if (!ABFD::$is_pro) : ?>
							<a href="https://wplens.com" target="_blank" class="button-primary">
								<?php _e('Pro', 'author-box-for-divi'); ?>
							</a>
							<?php endif; ?>
						<label for="abfd-option-profile-picture-position">
							<?php _e('Profile picture position', 'author-box-for-divi') ?>:
						</label></th>
					<td>
						<?php $position = get_option('abfd-option-profile-picture-position', 'left'); ?>
						<select name="abfd-option-profile-picture-position" id="abfd-option-profile-picture-position"
							<?= disabled(1, !ABFD::$is_pro) ?>>
							<option value="left" <?php selected('left', $position); ?>>
								<?php _e('Left', 'author-box-for-divi'); ?>
							</option>
							<option value="center" <?php selected('center', $position); ?>>
								<?php _e('Center', 'author-box-for-divi'); ?>
							</option>
							<option value="right" <?php selected('right', $position); ?>>
								<?php _e('Right', 'author-box-for-divi'); ?>
							</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>
						<?php if (!ABFD::$is_pro) : ?>
							<a href="https://wplens.com" target="_blank" class="button-primary">
								<?php _e('Pro', 'author-box-for-divi'); ?>
							</a>
							<?php endif; ?>
						<label for="abfd-option-profile-picture-shape">
							<?php _e('Profile picture shape', 'author-box-for-divi') ?>:
						</label></th>
					<td>
						<?php $shape = get_option('abfd-option-profile-picture-shape', 'square'); ?>
						<select name="abfd-option-profile-picture-shape" id="abfd-option-profile-picture-shape"
							<?= disabled(1, !ABFD::$is_pro) ?>>
							<option value="square" <?php selected('square', $shape); ?>>
								<?php _e('Square', 'author-box-for-divi'); ?>
							</option>
							<option value="circle" <?php selected('circle', $shape); ?>>
								<?php _e('Circle', 'author-box-for-divi'); ?>
							</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>
						<?php if (!ABFD::$is_pro) : ?>
							<a href="https://wplens.com" target="_blank" class="button-primary">
								<?php _e('Pro', 'author-box-for-divi'); ?>
							</a>
							<?php endif; ?>
						<label for="abfd-option-profile-picture-border-color">
							<?php _e('Profile picture border color', 'author-box-for-divi') ?>:
						</label></th>
					<td>
						<input type="text" data-jscolor="{}" name="abfd-option-profile-picture-border-color"
							id="abfd-option-profile-picture-border-color"
							value="<?php echo esc_attr(get_option('abfd-option-profile-picture-border-color', '#000')); ?>" class="large-text abfd-color-picker"
							<?= disabled(1, !ABFD::$is_pro) ?>>
					</td>
				</tr>
				<tr>
					<th>
						<?php if (!ABFD::$is_pro) : ?>
							<a href="https://wplens.com" target="_blank" class="button-primary">
								<?php _e('Pro', 'author-box-for-divi'); ?>
							</a>
							<?php endif; ?>
						<label for="abfd-option-profile-picture-border">
							<?php _e('Profile picture border', 'author-box-for-divi') ?>:
						</label></th>
					<td>
						<input type="number" name="abfd-option-profile-picture-border" id="abfd-option-profile-picture-border"
							value="<?php echo esc_attr(get_option('abfd-option-profile-picture-border', 0)); ?>" class="small-text"
							<?= disabled(1, !ABFD::$is_pro) ?>>px
						<p>
							<?php _e('To disable profile picture border, set this to 0 or leave it blank.', 'author-box-for-divi'); ?>
						</p>
					</td>
				</tr>

				<tr><th colspan="2"><?php _e('TEXT SETTINGS', 'author-box-for-divi'); ?></th></tr>

			<tr>
					<th>
						<?php if (!ABFD::$is_pro) : ?>
							<a href="https://wplens.com" target="_blank" class="button-primary">
								<?php _e('Pro', 'author-box-for-divi'); ?>
							</a>
							<?php endif; ?>
						<label for="abfd-option-font">
							<?php _e('Text Font', 'author-box-for-divi') ?>:
						</label></th>
					<td>
						<?php $font = get_option('abfd-option-font', ''); ?>
						<select name="abfd-option-font" id="abfd-option-font"
							<?= disabled(1, !ABFD::$is_pro) ?>>
							<option value="" <?php selected('left', $font); ?>>
								<?php _e('Default', 'author-box-for-divi'); ?>
							</option>
							<?php foreach ( $google_fonts as $google_font ) : ?>
								<option value="<?= $google_font ?>" <?php selected($google_font, $font); ?>>
									<?= $google_font ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>

				<tr>
					<th>
						<?php if (!ABFD::$is_pro) : ?>
							<a href="https://wplens.com" target="_blank" class="button-primary">
								<?php _e('Pro', 'author-box-for-divi'); ?>
							</a>
							<?php endif; ?>
						<label for="abfd-option-font-size">
							<?php _e('Text Size', 'author-box-for-divi') ?>:
						</label></th>
					<td>
						<input type="number" name="abfd-option-font-size" id="abfd-option-font-size"
							value="<?php echo esc_attr(get_option('abfd-option-font-size', '15')); ?>" class="small-text"
							<?= disabled(1, !ABFD::$is_pro) ?>>px
					</td>
			</tr>

			<tr>
					<th><label for="abfd-option-text-color">
							<?php _e('Text Color', 'author-box-for-divi') ?>:
						</label></th>
					<td><input type="text" data-jscolor="{}" name="abfd-option-text-color" id="abfd-option-text-color"
							value="<?php echo esc_attr(get_option('abfd-option-text-color')); ?>" class="large-text abfd-color-picker"></td>
				</tr>

				<tr>
					<th>
						<?php if (!ABFD::$is_pro) : ?>
							<a href="https://wplens.com" target="_blank" class="button-primary">
								<?php _e('Pro', 'author-box-for-divi'); ?>
							</a>
							<?php endif; ?>
						<label for="abfd-option-hyperlink-color">
							<?php _e('Hyperlink color', 'author-box-for-divi') ?>:
						</label></th>
					<td>
						<input type="text" data-jscolor="{}" name="abfd-option-hyperlink-color" id="abfd-option-hyperlink-color"
							value="<?php echo esc_attr(get_option('abfd-option-hyperlink-color')); ?>" class="large-text abfd-color-picker"
							<?= disabled(1, !ABFD::$is_pro) ?>>
					</td>
				</tr>

				<tr><th colspan="2"><?php _e('SOCIAL ICONS SETTINGS', 'author-box-for-divi'); ?></th></tr>

				<tr>
					<th>
						<?php if (!ABFD::$is_pro) : ?>
							<a href="https://wplens.com" target="_blank" class="button-primary">
								<?php _e('Pro', 'author-box-for-divi'); ?>
							</a>
							<?php endif; ?>
						<label for="abfd-option-icon-shape">
							<?php _e('Social Icon Shape', 'author-box-for-divi') ?>:
						</label></th>
					<td>
						<?php $icon_shape = get_option('abfd-option-icon-shape', 'icon'); ?>
						<select name="abfd-option-icon-shape" id="abfd-option-icon-shape"
							<?= disabled(1, !ABFD::$is_pro) ?>>
							<option value="icon" <?php selected('icon', $icon_shape); ?>>
								<?php _e('Icon only', 'author-box-for-divi'); ?>
							</option>
							<option value="square" <?php selected('square', $icon_shape); ?>>
								<?php _e('Square', 'author-box-for-divi'); ?>
							</option>
							<option value="circle" <?php selected('circle', $icon_shape); ?>>
								<?php _e('Circle', 'author-box-for-divi'); ?>
							</option>
						</select>
					</td>
				</tr>
				<tr>
					<th><label for="abfd-option-icon-color">
							<?php _e('Icon Color', 'author-box-for-divi') ?>:
						</label></th>
					<td><input type="text" data-jscolor="{}" name="abfd-option-icon-color" id="abfd-option-icon-color"
							value="<?php echo esc_attr(get_option('abfd-option-icon-color', '#000')); ?>" class="large-text abfd-color-picker"></td>
				</tr>
				<tr>
					<th><label for="abfd-option-social-icon-as-original">
							<?php _e('Social Icon as original Color', 'author-box-for-divi') ?>:
						</label></th>
					<td>
						<input type="checkbox" name="abfd-option-social-icon-as-original"
							id="abfd-option-social-icon-as-original" value="1" <?= checked(1, get_option('abfd-option-social-icon-as-original', true)) ?>>
						<span>
							<?php _e('Check this box if you want Social Icon as original Color', 'author-box-for-divi'); ?>
						</span>
					</td>
				</tr>

			</table>

			<p class="submit"><input type="submit" name="abfd-submit"
				value="<?php _e('Save Settings', 'author-box-for-divi') ?>" class="button-primary"></p>
		</div>

		<!-- License Settings -->
		<div id="nav-tab-license" class="nav-tab-content" style="display: none;">
			<table class="form-table">
				<tr>
					<td colspan="2" style="padding-bottom: 0;">
						<div class="notice notice-alt notice-warning inline">
							<p>
								<?php _e('Enter your license key. Your license key can be found in your', 'author-box-for-divi'); ?>
								<a href="https://wplens.com/membership-account/" target="_blank"><?php _e('Membership Account', 'author-box-for-divi'); ?></a>
							</p>
						</div>
					</td>
				</tr>
				<tr>
					<th>
					<?php if (!ABFD::$is_pro) : ?>
							<a href="https://wplens.com" target="_blank" class="button-primary">
								<?php _e('Pro', 'author-box-for-divi'); ?>
							</a>
							<?php endif; ?>	
					<label for="abfd-option-license-key">
							<?php _e('License Key', 'author-box-for-divi') ?>:
						</label></th>
					<td>
						<input type="text" name="abfd-option-license-key" id="abfd-option-license-key"
							value="<?php echo esc_attr(get_option('abfd-option-license-key', '')); ?>" class="regular-text">
						<span id="abfd-license-validation-icon"></span>
						<p id="abfd-license-validation-result"></p>
					</td>
				</tr>
			</table>

			<p class="submit">
				<button type="button" id="abfd-validate-license-key" class="button-primary">
					<?php _e('Validate Key', 'author-box-for-divi'); ?>
				</button>
				<span class="spinner"></span>
				<button type="button" id="abfd-reload-page" class="button-secondary" style="display: none;">
					<?php _e('Reload to see changes', 'author-box-for-divi'); ?>
				</button>
			</p>
		</div>

		<?php wp_nonce_field('abfd', 'abfd-nonce'); ?>
	</form>
</div>

<script>
	jQuery(document).ready(function ($) {
		// Initialize color picker
		$('.abfd-color-picker').wpColorPicker({
				change: debounce(function (event, ui) {
						refreshPreview();
				}, 100)
		});

		// Tabs click event
		$('.nav-tab').click(function (e) {
			e.preventDefault();

			$('.nav-tab').removeClass('nav-tab-active');
			$(this).addClass('nav-tab-active');

			$('.nav-tab-content').hide();
			$($(this).attr('href')).show();
		});

		// If any input or select fields change, reload .abfd-preview-area with ajax
		$('#abfd-settings-form input, #abfd-settings-form select').change(function () {
			refreshPreview();
		});

		function refreshPreview() {
			var data = $('#abfd-settings-form').serialize();
			// add unchecked checkboxes
			$('#abfd-settings-form input[type=checkbox]:not(:checked)').each(function () {
				data += '&' + $(this).attr('name') + '=0';
			});
			$.post('<?php echo admin_url('admin-ajax.php'); ?>', {
				action: 'abfd_preview',
				data
			}, function (response) {
				$('.abfd-preview-area').html(response.html);
				$('#abfd-custom-styles').replaceWith(response.css);
				$('#abfd-custom-styles-pro').replaceWith(response.css_pro);
			});
		}

		// Debounce function
		function debounce(func, wait, immediate) {
				var timeout;
				return function() {
						var context = this, args = arguments;
						var later = function() {
								timeout = null;
								if (!immediate) func.apply(context, args);
						};
						var callNow = immediate && !timeout;
						clearTimeout(timeout);
						timeout = setTimeout(later, wait);
						if (callNow) func.apply(context, args);
				};
		}

		// License key validation
		$('#abfd-validate-license-key').click(function () {
			var licenseKey = $('#abfd-option-license-key').val();
			var $button = $(this);
			var $spinner = $button.next('.spinner');
			
			$button.prop('disabled', true);
			$spinner.addClass('is-active');
			$('#abfd-license-validation-icon').html('');
			$('#abfd-license-validation-result').text('');
			$('#abfd-reload-page').hide();

			$.post('<?php echo admin_url('admin-ajax.php'); ?>', {
				nonce: '<?php echo wp_create_nonce('abfd_ajax_nonce'); ?>',
				action: 'abfd_validate_license_key',
				license_key: licenseKey
			}, function (response) {
				if(response.success) {
					$('#abfd-license-validation-icon').html('<span class="dashicons dashicons-yes" style="color: green;"></span>');
					$('#abfd-license-validation-result').css('color', 'green').text(response.data);
					$('#abfd-reload-page').show();
				} else {
					$('#abfd-license-validation-icon').html('<span class="dashicons dashicons-no" style="color: red;"></span>');
					$('#abfd-license-validation-result').css('color', 'red').text(response.data);
				}
			}).always(function() {
				$button.prop('disabled', false);
				$spinner.removeClass('is-active');
			});
		});

		// Reload page button
		$('#abfd-reload-page').click(function() {
			location.reload();
		});

	});
</script>

<style type="text/css">
	.abfd-shortcode-info {
		background-color: white;
		border: 1px solid #ddd;
		padding: 20px;
		max-width: 800px;
	}

	.abfd-shortcode-info .abfd-pro {
		float: right;
	}

	#abfd-license-validation-icon {
		margin-left: 5px;
		vertical-align: middle;
	}

	#nav-tab-license .spinner {
		float: none;
		margin-top: 4px;
		margin-left: 5px;
	}

	#abfd-reload-page {
		margin-left: 10px;
	}
</style>