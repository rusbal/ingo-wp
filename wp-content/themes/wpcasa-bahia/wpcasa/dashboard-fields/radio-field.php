<?php foreach ( $field['options'] as $k => $value ) : ?>

<input name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( empty( $k ) ? $key . '_0' : $k ); ?>" type="radio" value="<?php echo esc_attr( $k ); ?>" <?php if ( isset( $field['value'] ) || isset( $field['default'] ) ) checked( isset( $field['value'] ) ? $field['value'] : $field['default'], esc_attr( $k ) ); ?> <?php if ( ! empty( $field['disabled'] ) ) echo 'disabled'; ?> />
<label for="<?php echo esc_attr( empty( $k ) ? $key . '_0' : $k ); ?>"><?php echo esc_html( $value ); ?></label>

<?php endforeach; ?>

<?php if ( ! empty( $field['description'] ) ) : ?><small class="description"><?php echo $field['description']; ?></small><?php endif; ?>
