<table class="form-table">
    <tbody>
    <tr>
            <th scope="row">
                <label for="">Vehicle Name</label>
            </th>
            <td>
                <input type="text" name="addressya_vehicle_name" class="regular-text" value="<?php echo esc_attr( get_post_meta( $post->ID, 'addressya_vehicle_name', true ) ); ?>">
            </td>
        </tr>
      
    </tbody>
</table>