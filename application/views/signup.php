<?php $this->load->view('includes/header'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/login.css'); ?>">
    <title>Sign Up</title>
    <?php $this->load->view('header_auth', ['title' => 'Signup']); ?>
</head>

<body>
    <div id="userForm" class="container"></div>
    <script type="text/template" id="user-template">
        <div class="login-wrap">
            <div class="login-comp">
                <form action="<?php echo base_url('auth/register'); ?>" method="post">
                    <p class="login-txt">SignUp</p>
                    <div class="form-field login-form-field">
                    <label>Username: 
                    <input type="text" name="username" placeholder="Username" required>
                    </div>
                    <div class="form-field login-form-field">
                    <label>Email: </label>
                    <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="form-field login-form-field">
                    <label>Password: </label>
                    <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <div>
                    <?php if ($this->session->flashdata('error')) : ?>
                        <div class="alert alert-danger">
                            <p><?php echo $this->session->flashdata('error'); ?></p>
                        </div>
                    <?php endif; ?>
                    </div>
                    <button type="submit" class="login-button">Register</button>
                    <div class="signup-link"><p>Already have an account? <a href="<?php echo base_url('login'); ?>">Login</a></p></div>
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

    <script src="<?php echo base_url('assets/js/user.js'); ?>"></script>
</body>

</html>