<?php
$session = $this->session->userdata('status');
if ($session == 1) {
    header('Location:' . site_url() . '/dashboard');
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Log in | Community</title>
    <link rel="icon" href="<?php echo base_url().'/favicon.ico'; ?>" type="image/x-icon"/>
    <link rel="shortcut icon" href="<?php echo base_url().'/favicon.ico'; ?>" type="image/x-icon"/>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?php echo base_url('plugins/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('plugins/font-awesome/css/font-awesome.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('plugins/dist/css/AdminLTE.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('plugins/validation/css/bootstrapValidator.min.css'); ?>"/>
    <link rel="stylesheet" href="<?php echo base_url('plugins/animate/animate.css'); ?>"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        body {
            /*background: -webkit-linear-gradient(left, #81C784, #80DEEA);*/
            background-size: cover;
            background-attachment: fixed;
            font-family: 'Roboto', sans-serif;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }

        .fa-spin {
            -webkit-animation: fa-spin 20s infinite linear !important;
            animation: fa-spin 20s infinite linear !important;
        }

        .login-box-body {
            width: 100%;
            margin: 3em auto;
            background: rgba(0, 120, 90, 0.4);
            background-size: cover;
            padding: 1em 4em 2em;
            border-radius: 25px;
        }

        .fnt{
            color:#FAFAFA ;
        }

        .fnt2{
            color: rgba(120, 144, 156, 0.69);
            font-family:"Garamond";
            font-size: 200%;
            text-align: center;
            font-weight: bold;
        }

        h3{
            font-size: 150%;
        }

        input[type=text],input[type=password]{
            border-radius: 8px;
        }
    </style>
</head>
<body class="hold-transition login-page" style="background-image:url('<?php echo base_url('images/Mosq.jpg') ?>');">
<div class="col-sm-7">
    <div class="col-sm-7">
        <br><h3 class="fnt2">Welcome to <?php echo SYS_NAME ?> &#8480; <br>Community System</h3>
    </div>
    <div class="col-sm-6"></div>
</div>
<div class="col-sm-4">
    <div class="login-box" style="width: 100%;">
        <!-- /.login-logo -->
        <div class="login-box-body">
            <?php if ($this->session->flashdata('msg')) { ?>
                <div role="alert" class="alert alert-success"><?php echo $this->session->flashdata('msg'); ?></div>
            <?php } ?>
            <?php if (!empty($extra) && ($type == 'e')) { ?>
                <div role="alert" class="alert alert-danger"><?php echo $extra; ?></div>
            <?php } elseif (!empty($extra) && ($type == 's')) {
                ?>
                <div role="alert" class="alert alert-success"><?php echo $extra; ?></div>
                <?php
            } ?>
            <div class="text-center m-b-md">
                <img style="max-height: 120px;" src="<?php echo base_url('images/slk_flag.png') ?>" alt="Logo">
                <!--<small>Web Enterprise Resource Planning Solution.</small>-->
                <h4 class="fnt">Please Confirm Your User Credential</h4>
            </div>
            <br>
            <?php echo form_open('login/loginSubmit_gears', ' id="login_form" role="form"'); ?>
            <p class="fnt">Username</p>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="Username" id="from_username"
                       placeholder="Please enter you username" readonly
                       onfocus="this.removeAttribute('readonly');">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <p class="fnt">Password</p>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="Password" id="from_password" placeholder="******"
                       autocomplete="off" readonly
                       onfocus="this.removeAttribute('readonly');">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <br>
                <div class="col-xs-8">
                    <a href="<?php echo site_url('Login/forget_password') ?>" style="color: #304FFE;">&nbsp; Forgot password?</a>
                </div>
                <!-- /.col -->
                <div class="col-xs-12">
                    <br>
                    <button type="submit" class="btn btn-success btn-block btn-flat btn-block">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
            </form>
        </div>
        <!-- /.login-box-body -->
    </div>
</div>
<!-- /.login-box -->

<script src="<?php echo base_url('plugins/jQuery/jQuery-2.1.4.min.js'); ?>"></script>
<script src="<?php echo base_url('plugins/bootstrap/js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript"
        src="<?php echo base_url('plugins/validation/js/bootstrapValidator.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#login_form').bootstrapValidator({
            live: 'enabled',
            message: 'This value is not valid.',
            excluded: [':disabled'],
            fields: {
                Username: {validators: {notEmpty: {message: 'Username is required.'}}},
                Password: {validators: {notEmpty: {message: 'Password is required.'}}}
            },
        }).on('success.form.bv', function (e) {
        });

        setTimeout(function () {
            $("#from_password").val('');
            $("#from_username").val('');
        }, 500);
    });
</script>
</body>
</html>
