<?php
header('Content-type: text/html; charset=utf-8');
require_once('db.inc.php');
include('table.class.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>Bootstrap, from Twitter</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta name="description" content=""/>
        <meta name="author" content=""/>
        <link href="assets/css/bootstrap.css" rel="stylesheet"/>
        <link href="assets/css/docs.css" rel="stylesheet"/>
        <link href="assets/js/google-code-prettify/prettify.css" rel="stylesheet"/>
        <style>
            #user_data > thead > tr > th {
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12">
                    <table id="user_data" class="table table-hover table-condensed table-bordered">
                        <thead>
                            <tr class="info">
                                <th class="type-int">#</th>
                                <th class="type-string">First Name</th>
                                <th class="type-string">Last Name</th>
                                <th class="type-string">Country</th>
                                <th class="type-string">City</th>
                                <th class="type-string">Address</th>
                                <th class="type-string">Email</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * from users";
                            $result = $db->query($sql);
                            while ($row = $result->fetch_assoc()) :
                                ?>
                                <tr id='row_<?php echo $row['id']; ?>'>
                                    <td><?php echo $row['id'] ?></td>
                                    <td><?php echo $row['firstname'] ?></td>
                                    <td><?php echo $row['lastname'] ?></td>
                                    <td><?php echo $row['country'] ?></td>
                                    <td><?php echo $row['city'] ?></td>
                                    <td><?php echo $row['address'] ?></td>
                                    <td><?php echo $row['email'] ?></td>
                                    <td><input type='submit' value='Edit' id="<?php echo $row['id']; ?>" class="edit btn btn-info" /></td>
                                    <td><input type='submit' value='Delete' id="<?php echo $row['id']; ?>" class="delete btn btn-danger" /></td>
                                </tr>
                                <?php
                            endwhile;
                            $result->close();
                            ?>
                        </tbody>
                        <tfoot>
                            <?php
                            $table = new Table;
                            echo $table->add_row();
                            ?>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="assets/js/jquery-latest.min.js"></script>
        <script src="assets/js/google-code-prettify/prettify.js"></script>
        <script src="assets/js/stupidtable.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#user_data").stupidtable();
                
                $(".edit").live("click", function(e) {
                    var user_id = $(this).attr('id');
                    e.preventDefault();
                    $.ajax({
                        url: 'table.class.php',
                        data: {action: 'edit', id: user_id},
                        dataType: 'html',
                        success: function(response) {
                            $('#row_' + user_id).html("").html(response);
                        }
                    });
                });

                $(".delete").live("click", function(e) {
                    var user_id = $(this).attr('id');
                    e.preventDefault();
                    $.ajax({
                        url: 'table.class.php',
                        data: {action: 'delete', id: user_id},
                        dataType: 'json',
                        success: function(response) {
                            if(response.status == 'success') {
                                $('#row_' + user_id).remove();
                            }
                        }
                    });
                });

                $(".save").live("click", function(e) {
                    e.preventDefault();
                    var user_id = $(this).attr('id');
                    var data = {
                        'firstname': $("#row_"+user_id+' input[name=firstname]').val(),
                        'lastname': $("#row_"+user_id+' input[name=lastname]').val(),
                        'country': $("#row_"+user_id+' input[name=country]').val(),
                        'city': $("#row_"+user_id+' input[name=city]').val(),
                        'address': $("#row_"+user_id+' input[name=address]').val(),
                        'email': $("#row_"+user_id+' input[name=email]').val()
                    };
                    
                    $.ajax({
                        type: 'POST',
                        url: 'table.class.php?action=update&id='+user_id,
                        data: {data: data},
                        dataType: 'json',
                        success: function(response) {
                            if(response.status == 'success') {
                                $('#row_' + user_id).html("").html(response.row);
                            } else {
                                alert(response.error);
                            }
                        }
                    });
                });
                
                $("#create_row").live("click", function(e) {
                    e.preventDefault();
                    var data = {
                        'firstname': $("#new_firstname").val(),
                        'lastname': $("#new_lastname").val(),
                        'country': $("#new_country").val(),
                        'city': $("#new_city").val(),
                        'address': $("#new_address").val(),
                        'email': $("#new_email").val()
                    };
                    
                    $.ajax({
                        type: 'POST',
                        url: 'table.class.php?action=create',
                        data: {data: data},
                        dataType: 'json',
                        success: function(response) {
                            if(response.status == 'success') {
                                $('#user_data tbody tr:last').after(response.row);
                                $("#new_firstname").val("");
                                $("#new_lastname").val("");
                                $("#new_country").val("");
                                $("#new_city").val("");
                                $("#new_address").val("");
                                $("#new_email").val("");
                            } else {
                                alert(response.error);
                            }
                        }
                    });
                });

                $(".cancel").live("click", function(e) {
                    var user_id = $(this).attr('id');
                    e.preventDefault();
                    $.ajax({
                        url: 'table.class.php',
                        data: {action: 'cancel', id: user_id},
                        dataType: 'html',
                        success: function(response) {
                            $('#row_' + user_id).html("").html(response);
                        }
                    });
                });
            });
        </script>
    </body>
</html>