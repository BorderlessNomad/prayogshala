<?php
require_once('db.inc.php');

class Table {

    public function validate_id($id = null) {
        if (empty($id)) {
            if (empty($id)) {
                echo json_encode(array('status' => 'failure', 'error' => 'Empty ID'));
                die;
            }
        }
    }

    public function validate_data($data = null) {
        if (empty($data)) {
            echo json_encode(array('status' => 'failure', 'error' => 'Empty Data'));
            die;
        }
        foreach ($data as $key => $value) {
            if (empty($value)) {
                echo json_encode(array('status' => 'failure', 'error' => 'Empty ' . $key));
                die;
            } else if ($key == 'email') {
                if (!preg_match("/^([a-z0-9+_-]+)(.[a-z0-9+_-]+)*@([a-z0-9-]+.)+[a-z]{2,6}$/ix", $value)) {
                    echo json_encode(array('status' => 'failure', 'error' => 'Invalid Email'));
                    die;
                }
            }
        }
    }

    public function add_row($row = null) {
        $row_data = "
				<tr id='row_new' class='success'>
					<td>&nbsp;</td>
					<td><input class='input-medium' type='text' id='new_firstname' placeholder='First Name' /></td>
					<td><input class='input-medium' type='text' id='new_lastname' placeholder='Last Name' /></td>
					<td><input class='input-medium' type='text' id='new_country' placeholder='Country' /></td>
					<td><input class='input-medium' type='text' id='new_city' placeholder='City' /></td>
					<td><input class='input-medium' type='text' id='new_address' placeholder='Address' /></td>
					<td><input class='input-medium' type='text' id='new_email' placeholder='Email' /></td>
					<td colspan='2'><input type='submit' value='Create User' id='create_row' class='btn btn-success' /></td>
				</tr>	
			";
        return $row_data;
    }

    public function edit_row($id) {
        global $db;
        $sql = "SELECT * FROM users WHERE id = " . $db->real_escape_string($id);
        $result = $db->query($sql);
        $row = $result->fetch_assoc();
        $row_data = "
				<td>" . $row['id'] . " <input type='hidden' name='id' value='" . $row['id'] . "' /></td>
				<td><input class='input-medium' type='text' name='firstname' value='" . $row['firstname'] . "' /></td>
				<td><input class='input-medium' type='text' name='lastname' value='" . $row['lastname'] . "' /></td>
				<td><input class='input-medium' type='text' name='country' value='" . $row['country'] . "' /></td>
				<td><input class='input-medium' type='text' name='city' value='" . $row['city'] . "' /></td>
				<td><input class='input-medium' type='text' name='address' value='" . $row['address'] . "' /></td>
				<td><input class='input-medium' type='text' name='email' value='" . $row['email'] . "' /></td>
				<td><input id='" . $row['id'] . "' class='save btn btn-success' type='submit' value='Save' /></td>
                                <td><input id='" . $row['id'] . "' class='cancel btn btn-inverse' type='submit' value='Cancel' /></td>
			";
        return $row_data;
    }

    public function display_row($id, $new = false) {
        global $db;
        $sql = "SELECT * FROM users WHERE id = " . $db->real_escape_string($id);
        $result = $db->query($sql);
        $row = $result->fetch_assoc();
        $row_data = "
				<td>" . $row['id'] . "</td>
				<td>" . $row['firstname'] . "</td>
				<td>" . $row['lastname'] . "</td>
				<td>" . $row['country'] . "</td>
				<td>" . $row['city'] . "</td>
				<td>" . $row['address'] . "</td>
				<td>" . $row['email'] . "</td>
				<td><input id='" . $row['id'] . "' class='edit btn btn-info' type='submit' value='Edit' /></td>
				<td><input id='" . $row['id'] . "' class='delete btn btn-danger' type='submit' value='Delete' /></td>
			";
        if ($new) {
            $row_data = "<tr id='row_" . $row['id'] . "'>" . $row_data . "<tr>";
        }
        $db->close();
        return $row_data;
    }

    public function delete_row($id) {
        global $db;
        $sql = "DELETE FROM users WHERE id = " . $db->real_escape_string($id);
        if ($result = $db->query($sql)) {
            $response = array(
                'status' => 'success',
                'message' => 'ID ' . $id . ' has been deleted successfully.'
            );
        } else {
            $response = array(
                'status' => 'failure',
                'error' => 'Error while deleting row.'
            );
        }
        return json_encode($response);
    }

    public function update_row($id = null, $data = null) {
        global $db;
        $id = $db->real_escape_string($id);
        $firstname = $db->real_escape_string($data['firstname']);
        $lastname = $db->real_escape_string($data['lastname']);
        $country = $db->real_escape_string($data['country']);
        $city = $db->real_escape_string($data['city']);
        $address = $db->real_escape_string($data['address']);
        $email = $db->real_escape_string($data['email']);
        $sql = "
		UPDATE 
			users 
		SET 
			firstname = '$firstname', 
			lastname = '$lastname', 
			country = '$country', 
			city = '$city', 
			address = '$address', 
			email = '$email'
		WHERE 
			id = '$id'
		";
        if ($db->query($sql)) {
            $response = array(
                'status' => 'success',
                'message' => 'ID ' . $id . ' has been updated successfully.',
                'row' => $this->display_row($id)
            );
        } else {
            $response = array(
                'status' => 'failure',
                'error' => 'Error while updating ID ' . $id . '.',
            );
        }
        return json_encode($response);
    }

    public function create_row($data = null) {
        global $db;
        $firstname = $db->real_escape_string($data['firstname']);
        $lastname = $db->real_escape_string($data['lastname']);
        $country = $db->real_escape_string($data['country']);
        $city = $db->real_escape_string($data['city']);
        $address = $db->real_escape_string($data['address']);
        $email = $db->real_escape_string($data['email']);
        $sql = "
		INSERT INTO 
			users 
                        (firstname, lastname, country, city, address, email)
		VALUES 
			('$firstname', '$lastname', '$country', '$city', '$address', '$email')
		";
        if ($db->query($sql)) {
            $response = array(
                'status' => 'success',
                'message' => 'User had been created successfully.',
                'row' => $this->display_row($db->insert_id, true)
            );
        } else {
            $response = array(
                'status' => 'failure',
                'error' => 'Email already exists.',
            );
        }
        return json_encode($response);
    }

}

if (!empty($_GET['action'])) {
    $table = new Table;

    switch ($_GET['action']) {
        case 'edit':
            $table->validate_id($_GET['id']);
            echo $table->edit_row($_GET['id']);
            break;

        case 'delete':
            $table->validate_id($_GET['id']);
            echo $table->delete_row($_GET['id']);
            break;

        case 'cancel':
            $table->validate_id($_GET['id']);
            echo $table->display_row($_GET['id']);
            break;

        case 'update':
            $table->validate_id($_GET['id']);
            $table->validate_data($_POST['data']);
            echo $table->update_row($_GET['id'], $_POST['data']);
            break;

        case 'create':
            $table->validate_data($_POST['data']);
            echo $table->create_row($_POST['data']);
            break;

        default:
            echo json_encode(array('status' => 'failure', 'error' => 'Invalid Action'));
            break;
    }
} else if (isset($_GET['action'])) {
    echo json_encode(array('status' => 'failure', 'error' => 'Invalid Request'));
}
?>