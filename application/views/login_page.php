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

    <link rel="stylesheet" href="<?php echo base_url('plugins/dist/css/news.css'); ?>">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        body {
            background: #ebeeef;
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
            margin: 12em auto;
            background: rgba(0, 120, 110, 0.4);
            background-size: cover;
            padding: 1em 4em 2em;
            border-radius: 25px;
        }

        .fnt{
            color:#FAFAFA ;
        }

        .fnt2{
            color: rgba(119, 119, 119, 0.9);
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
<body class="hold-transition login-page" style="">


<!--------------------->
<header>
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="logo">
                    <div class="login-logo">
                        <a href="<?php echo site_url() ?>"><img src="<?php echo base_url('images/sl-logo.png') ?>"></a>
                    </div>
                </div>
            </div>

                <?php echo form_open('login/loginSubmit', ' id="login_form" style="display: contents;" role="form"'); ?>
                <div class="col-sm-6">
                    <div class="row label-custom">
                        <div class="col-sm-5">
                            <div class="form-group">
                                <div class="login-bottom-text checkbox hidden-sm login-text-label">
                                    <p>
                                        Username
                                    </p>
                                </div>
                                <input type="text" name="Username" id="from_username" class="form-control" placeholder="Please enter you username" readonly onfocus="this.removeAttribute('readonly');">
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <div class="login-bottom-text checkbox hidden-sm login-text-label">
                                    <p>
                                        Password
                                    </p>
                                </div>
                                <input  type="password" class="form-control" name="Password" id="from_password" placeholder="******"
                                        autocomplete="off" readonly
                                        onfocus="this.removeAttribute('readonly');">
                                <a href="<?php echo site_url('Login/forget_password') ?>" class="login-bottom-text forgot-password">Forgotten account?</a>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <button type="submit" class="btn btn-default btn-header-login btn-block btn-flat">Log In</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</header>



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


<article class="container">
    <div class="row">
        
        <div class="col-sm-10">
            <div class="login-main">
                <h4 class="txt-7 mt-1"> Together We Are Stronger.</h4>
                <img src="<?php echo base_url('images/bg-login.png') ?>" alt="">
            </div>
        </div>

        <!--<div class="col-sm-2">

            <div class="">

                <h3 class="txt-6">Member Registration</h3>

            </div>
        </div>-->
    </div>

</article>
<!--------------------->



<div class="col-sm-4">
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
