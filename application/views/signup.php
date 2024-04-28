<?php $this->load->view('includes/header'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <div id="userForm"></div>
    <script type="text/template" id="user-template">
        <p>Register a new user: <strong><%= username %></strong></p>
        <form action="<?php echo base_url('auth/register'); ?>" method="post">
        <label>Username: 
        <input type="text" name="username" required><br>
        <label>Email: </label><input type="email" name="email" required><br>
        <label>Password: </label><input type="password" name="password" required><br>
        <button type="submit">Register</button>
        </form>
    </script>

    <script src="<?php echo base_url('assets/js/user.js'); ?>"></script>
</body>
</html>