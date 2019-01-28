<script type="text/x-jquery-tmpl" id="tag-row-tmpl">
	<?= renderTagTableRow('#{rowNum}') ?>
</script>

<script type="text/javascript">
	jQuery(function($) {
		<?php if (CWebUser::getType() == USER_TYPE_SUPER_ADMIN): ?>
			$('input[name=mass_update_groups]').on('change', function() {
				var mass_update_groups = $(this).is(':checked').val(),
					add_new = !(mass_update_groups == <?= ZBX_ACTION_REMOVE ?>);

				$('#groups_').multiSelect('modify', {'addNew': add_new});
			});
		<?php endif ?>

		$('#tags-table').dynamicRows({template: '#tag-row-tmpl'});

		$('#mass_replace_tpls').on('change', function() {
			$('#mass_clear_tpls').prop('disabled', !this.checked);
		}).trigger('change');

		$('#inventory_mode').on('change', function() {
			$('.formrow-inventory').toggle($(this).val() !== '<?php echo HOST_INVENTORY_DISABLED; ?>');
		}).trigger('change');

		$('#tls_connect, #tls_in_psk, #tls_in_cert').on('change', function() {
			// If certificate is selected or checked.
			if ($('input[name=tls_connect]:checked').val() == <?= HOST_ENCRYPTION_CERTIFICATE ?>
					|| $('#tls_in_cert').is(':checked')) {
				$('#tls_issuer, #tls_subject').closest('tr').show();
			}
			else {
				$('#tls_issuer, #tls_subject').closest('tr').hide();
			}

			// If PSK is selected or checked.
			if ($('input[name=tls_connect]:checked').val() == <?= HOST_ENCRYPTION_PSK ?>
					|| $('#tls_in_psk').is(':checked')) {
				$('#tls_psk, #tls_psk_identity').closest('tr').show();
			}
			else {
				$('#tls_psk, #tls_psk_identity').closest('tr').hide();
			}
		});

		// Refresh field visibility on document load.
		if (($('#tls_accept').val() & <?= HOST_ENCRYPTION_NONE ?>) == <?= HOST_ENCRYPTION_NONE ?>) {
			$('#tls_in_none').prop('checked', true);
		}
		if (($('#tls_accept').val() & <?= HOST_ENCRYPTION_PSK ?>) == <?= HOST_ENCRYPTION_PSK ?>) {
			$('#tls_in_psk').prop('checked', true);
		}
		if (($('#tls_accept').val() & <?= HOST_ENCRYPTION_CERTIFICATE ?>) == <?= HOST_ENCRYPTION_CERTIFICATE ?>) {
			$('#tls_in_cert').prop('checked', true);
		}

		$('input[name=tls_connect]').trigger('change');

		// Depending on checkboxes, create a value for hidden field 'tls_accept'.
		$('#hostForm').on('submit', function() {
			var tls_accept = 0x00;

			if ($('#tls_in_none').is(':checked')) {
				tls_accept |= <?= HOST_ENCRYPTION_NONE ?>;
			}
			if ($('#tls_in_psk').is(':checked')) {
				tls_accept |= <?= HOST_ENCRYPTION_PSK ?>;
			}
			if ($('#tls_in_cert').is(':checked')) {
				tls_accept |= <?= HOST_ENCRYPTION_CERTIFICATE ?>;
			}

			$('#tls_accept').val(tls_accept);
		});
	});
</script>
