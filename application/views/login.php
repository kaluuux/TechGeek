<?php $this->load->view('includes/header'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/login.css'); ?>">
    <title>Login</title>
    <?php $this->load->view('header_auth', ['title' => 'Login']); ?>
</head>
<body>
    <div id="loginFormContainer" class="container" data-url="<?php echo base_url('auth/login_process'); ?>"></div>
    <script type="text/template" id="login-template">
        <div class="login-wrap">
            <div class="login-comp">
                <form id="actualLoginForm" class="login-form">
                    <p class="login-txt">Login</p>
                    <div class="form-field login-form-field">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Email" value="<%= email %>" />
                    </div>
                    <div class="form-field login-form-field">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Password" />
                    </div>
                    <div>
                        <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="login-button">Login</button>
                    <div class="signup-link"><p>New User? <a href="<?php echo base_url('auth/signup'); ?>">Sign Up</a></p></div>
                </form>
            </div>
            <div class="login-desc">
                <h1 class="animate-head">Welcome to Our Q&A Website</h1>
                <p class="animate-txt">This platform is dedicated to answering all your queries, from the mundane to the profound. Whether you're seeking expert advice, looking to share your knowledge, or simply curious about the world around you, you'll find a community ready to engage and enlighten.</p>
                <p class="animate-txt">Our mission is to foster a space where questions are celebrated, curiosity is encouraged, and learning is a lifelong journey. Join us in exploring the vast universe of knowledge!</p>
                <p class="animate-txt">Please login to unlock access to our vibrant community of inquisitive minds.</p>
            </div>
        </div>
    </script>
    <script src="<?php echo base_url('assets/js/login.js'); ?>"></script>
</body>
</html>
