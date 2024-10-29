<div class="et_pb_row abfd_et_pb_row abfd-container-divi">
	<div class="et_pb_column">
		<div class="abfd-container">
			<a href="<?= $author_link ?>" <?= $link_attrs ?> class="abfd-photograph-link">
				<div class="abfd-photograph" style="background-image: url('<?php echo $fields['photograph']; ?>');"></div>
		</a>

		<div class="abfd-details">
			<div class="abfd-name">
				<?php if ($hyperlink_author_page) { ?>
					<a href="<?= $author_link ?>" <?= $link_attrs ?>>
						<?php echo $name_prefix . ' ' . $user->display_name; ?>
					</a>
				<?php } else { ?>
					<?php echo $name_prefix . ' ' . $user->display_name; ?>
				<?php } ?>
			</div>

			<?php if (!empty($user->description)): ?>
				<div class="abfd-biography">
					<?php echo wpautop($user->description); ?>
				</div>
			<?php endif; ?>

			<?php if (!empty($fields['social-networks'])): ?>
				<div class="abfd-social-networks">
					<?php if ($email_icon) { ?>
						<span style="<?php if( self::$is_pro && 'icon' !== $icon_shape ) echo 'background-color: ' .  $icon_color; ?>"><a href="mailto:<?= $user->user_email ?>" class="icon"
								style="color:<?= self::$is_pro && 'icon' !== $icon_shape ? 'white' : $icon_color ?>">
								<i class="abfd-social-network-icon fa-solid fa-envelope"></i>
							</a></span>
					<?php } ?>
					<?php if ($website_icon) { ?>
						<span style="<?php if( self::$is_pro && 'icon' !== $icon_shape ) echo 'background-color: ' .  $icon_color; ?>"><a href="<?= $user->user_url ?>" class="icon"
								<?= $link_attrs ?>
								style="color:<?= self::$is_pro && 'icon' !== $icon_shape ? 'white' : $icon_color ?>">
								<i class="abfd-social-network-icon fa-solid fa-globe"></i>
							</a></span>
					<?php } ?>

					<?php
					foreach (ABFD::$social_networks as $key => $network) {
						if (!empty($fields['social-networks'][$key])) {
							if ($icon_original) {
								$icon_color = $network[1];
							}
							?><span style="<?php if( self::$is_pro && 'icon' !== $icon_shape ) echo 'background-color: ' .  $icon_color; ?>"><a style="color:<?= self::$is_pro && 'icon' !== $icon_shape ? 'white' : $icon_color ?>"
									href="<?php echo $fields['social-networks'][$key]; ?>" class="icon"
									<?= $link_attrs ?>>
										<i class="abfd-social-network-icon fa-<?php echo $network[2]; ?> fa-<?php echo $key; ?>"></i>
									</a></span>
							<?php
						}
					}
					?>
				</div>
			<?php endif; ?>

			<div class="abdf-author-box-bottom">
				<?php do_action('abfd-author-box-bottom', $user->ID, $link_attrs); ?>
			</div>

		</div>
	</div>
	</div>
</div>
