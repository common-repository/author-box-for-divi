<h2>
	<?php _e('Author Box WP Lens', 'author-box-for-divi') ?>
</h2>
<table class="form-table">
	<tr>
		<th>
			<?php _e('Do you want to disable this for this author?', 'author-box-for-divi'); ?>:
		</th>
		<td>
			<label><input type="radio" name="abfd-user-disable" value="no" <?php if (empty($disable) || $disable == 'no')
				echo 'checked'; ?> /> No</label>

			<label><input type="radio" name="abfd-user-disable" value="yes" <?php if ($disable == 'yes')
				echo 'checked'; ?> /> Yes</label>
		</td>
	</tr>

	<tr>
		<th><label for="abfd-user-photograph">
				<?php _e('Photograph', 'author-box-for-divi'); ?>:
			</label></th>
		<td>
			<input type="text" name="abfd-user-photograph" id="abfd-user-photograph"
				value="<?php echo esc_url(get_user_meta($profileuser->ID, 'abfd-user-photograph', true)); ?>"
				class="regular-text" />
			<button type="button" id="abfd-user-photograph-button" class="button"><?php _e('Media', 'author-box-for-divi'); ?></button>
		</td>
	</tr>

	<tr>
		<th>
			<label for="abfd-add-social-network">
				<?php _e('Add Social Network', 'author-box-for-divi'); ?>:
			</label>
		</th>
		<td>
			<select id="abfd-add-social-network" class="large-text">
				<option value=""><?php _e('Select a social network', 'author-box-for-divi'); ?></option>
				<?php foreach (self::$social_networks as $key => $network) : ?>
						<option value="<?php echo esc_attr($key); ?>" data-fa="<?php echo esc_attr($network[2]); ?>"><?php echo esc_html($network[0]); ?></option>
				<?php endforeach; ?>
			</select>
		</td>
	</tr>
</table>
<div id="social-networks-container">
	<?php
	foreach (self::$social_networks as $key => $network) {
		$url = esc_url(get_user_meta($profileuser->ID, 'abfd-user-social-networks-' . $key, true));
		if (!empty($url)) {
			?>
			<table class="form-table social-network-row" data-network="<?php echo esc_attr($key); ?>">
				<tr>
					<th>
						<label for="abfd-user-social-networks-<?php echo $key; ?>">
							<i class="fa-<?php echo esc_attr($network[2]); ?> fa-<?php echo esc_attr($key); ?>"></i>
							<?php echo $network[0]; ?>:
						</label>
					</th>
					<td>
						<input type="text" name="abfd-user-social-networks-<?php echo $key; ?>" 
								id="abfd-user-social-networks-<?php echo $key; ?>"
								value="<?php echo $url; ?>" class="regular-text" />
						<span class="dashicons dashicons-trash remove-social-network"></span>
					</td>
				</tr>
			</table>
			<?php
		}
	}
	?>
</div>

<script>
jQuery(document).ready(function($) {
		function formatState(state) {
				if (!state.id) { return state.text; }
				// get fa data attr
				var fa = $(state.element).data('fa');
				var baseClass = 'fa-' + fa + ' fa-' + state.id;
				var $state = $(
						'<span><i class="' + baseClass + '"></i> ' + state.text + '</span>'
				);
				return $state;
		}

		function refreshSelect2() {
				var addedNetworks = [];
				$('#social-networks-container .social-network-row').each(function() {
						addedNetworks.push($(this).data('network'));
				});

				$('#abfd-add-social-network option').each(function() {
						if (addedNetworks.includes($(this).val())) {
								$(this).prop('disabled', true).hide();
						} else {
								$(this).prop('disabled', false).show();
						}
				});

				$('#abfd-add-social-network').select2({
						placeholder: 'Select a social network',
						allowClear: true,
						templateResult: formatState,
						templateSelection: formatState
				});
		}

		refreshSelect2();

		$('#abfd-add-social-network').on('select2:select', function(e) {
				var network = e.params.data.id;
				var networkText = e.params.data.text;
				var selectedElement = e.params.data.element;
				var fa = $(selectedElement).data('fa');
				if ($('#social-networks-container').find('[data-network="' + network + '"]').length === 0) {
						var inputHtml = '<table class="form-table social-network-row" data-network="' + network + '">' +
								'<tr>' +
										'<th>' +
												'<label for="abfd-user-social-networks-' + network + '"><i class="fa-' + fa + ' fa-' + network + '"></i> ' + networkText + ':</label>' +
										'</th>' +
										'<td>' +
												'<input type="text" name="abfd-user-social-networks-' + network + '" class="regular-text" />' +
												'<span class="dashicons dashicons-trash remove-social-network"></span>' +
										'</td>' +
								'</tr>' +
						'</table>';
						$('#social-networks-container').append(inputHtml);
						refreshSelect2();
				}
				$(this).val(null).trigger('change');
		});

		$('#social-networks-container').on('click', '.remove-social-network', function() {
				var network = $(this).closest('.social-network-row').data('network');
				var hiddenInputHtml = '<input type="hidden" name="abfd-user-social-networks-' + network + '" value="" />';
				$('#social-networks-container').append(hiddenInputHtml);
				$(this).closest('.social-network-row').remove();
				refreshSelect2();
		});

		// Media Library
		var mediaUploader;
		$('#abfd-user-photograph-button').on('click', function(e) {
				e.preventDefault();
				if (mediaUploader) {
						mediaUploader.open();
						return;
				}
				mediaUploader = wp.media.frames.file_frame = wp.media({
						title: '<?php _e('Media', 'author-box-for-divi'); ?>',
						button: {
								text: '<?php _e('Use this image', 'author-box-for-divi'); ?>'
						},
						multiple: false
				});
				mediaUploader.on('select', function() {
					var attachment = mediaUploader.state().get('selection').first().toJSON();
					var imageUrl = attachment.sizes.large ? attachment.sizes.large.url : attachment.url;
					$('#abfd-user-photograph').val(imageUrl);
				});
				mediaUploader.open();
		});
});
</script>

<style>
		#social-networks-container .remove-social-network {
				cursor: pointer;
				vertical-align: middle;
				color: #a00;
		}

		.select2-container--default .select2-selection--single .select2-selection__placeholder {
				color: black;
		}
</style>

<?php
wp_nonce_field('abfd', 'abfd-nonce');
