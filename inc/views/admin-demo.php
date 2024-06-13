<div class="settings">
    <h1 class="wp-heading-inline">
        Settings Demo Section
    </h1>
    <form method="post" action="options.php">
        <?php settings_fields($args['theme']); ?>
        <table class="form-table" aria-label="Demo" aria-describedby="Demo table desc">
            <tr>
                <th>
                    <label for="<?php echo $args['field']; ?>">
                        Demo Value:
                    </label>
                </th>
                <td>
                    <input type="text" class="regular-text" id="<?php echo $args['field']; ?>" name="<?php echo $args['field']; ?>" value="<?php echo get_option($args['field']); ?>">
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>