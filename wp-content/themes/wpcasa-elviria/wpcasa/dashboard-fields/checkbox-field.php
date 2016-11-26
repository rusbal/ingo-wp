<label for="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?>">
<input
	type="checkbox"
	class="input-checkbox"
	name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?>"
	id="<?php echo esc_attr( $key ); ?>"
	<?php checked( ! empty( $field['value'] ), true ); ?>
	value="1"
	<?php if ( ! empty( $field['required'] ) ) echo 'required'; ?>
	<?php if ( ! empty( $field['disabled'] ) ) echo 'disabled'; ?>
	/>
<?php if ( ! empty( $field['description'] ) ) : ?><span class="description"><?php echo $field['description']; ?></span><?php endif; ?>
</label>