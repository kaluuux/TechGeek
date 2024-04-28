<?php $this->load->view('includes/header'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
<div id="loginFormContainer" data-url="<?php echo base_url('auth/login_process'); ?>"></div>
<script type="text/template" id="login-template">
    <form id="actualLoginForm">
        <p>Login to your account:</p>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter email" value="<%= email %>" />
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter password" />
        <button type="submit">Login</button>
    </form>
</script>
    <script src="<?php echo base_url('assets/js/login.js'); ?>"></script>
</body>
</html>
