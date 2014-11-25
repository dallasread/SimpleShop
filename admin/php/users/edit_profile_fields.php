<?php 
	if (property_exists($user, "ID")) { $id = $user->ID; }
	if (isset($id) && (!current_user_can("edit_user", $id) || in_array('administrator', get_user_by( "id", $id )->roles))) { return false; }
?>
<table class="form-table">
    <tr>
        <th><label for="manage_simpleshop">Access to SimpleShop</label></th>
        <td>
            <input id="manage_simpleshop" name="manage_simpleshop" type="checkbox" value="1" <?php if ( isset($id) && user_can($id, "manage_simpleshop") ) { echo " checked=\"checked\""; } ?> />
        </td>
    </tr>
</table>