<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <script type="text/javascript">if (parent.frames.length !== 0) {
            top.location = '<?=site_url('pos')?>';
        }</script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?= $assets ?>images/icon.png"/>
    <link href="<?= $assets ?>styles/theme.css" rel="stylesheet"/>
    <link href="<?= $assets ?>styles/style.css" rel="stylesheet"/>
    <link href="<?= $assets ?>styles/helpers/login.css" rel="stylesheet"/>
    <script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
    <!--[if lt IE 9]>
    <script src="<?= $assets ?>js/respond.min.js"></script>
    <![endif]-->

</head>

<body class="login-page">
<noscript>
    <div class="global-site-notice noscript">
        <div class="notice-inner">
            <p><strong>JavaScript seems to be disabled in your browser.</strong><br>You must have JavaScript enabled in
                your browser to utilize the functionality of this website.</p>
        </div>
    </div>
</noscript>
<div class="page-back">
    <div class="text-center"><?php if ($Settings->logo2) {
            echo '<img src="' . base_url('assets/uploads/logos/' . $Settings->logo2) . '" alt="' . $Settings->site_name . '" style="margin-bottom:10px;" />';
        } ?></div>
    <div id="login">

        <div class=" container">

            <div class="login-form-div">
                <div class="login-content">
                    <?php if ($Settings->mmode) { ?>
                        <div class="alert alert-warning">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <?= lang('site_is_offline') ?>
                        </div>
                    <?php }
                    if ($error) { ?>
                        <div class="alert alert-danger">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $error; ?></ul>
                        </div>
                    <?php }
                    if ($message) { ?>
                        <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $message; ?></ul>
                        </div>
                    <?php } ?>
                    <?php echo form_open("auth/login", 'class="login" data-toggle="validator"'); ?>
                    <div class="div-title">
                        <h3 class="text-primary"><?= lang('login_to_your_account') ?></h3>
                    </div>
                    <div class="textbox-wrap">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <input type="email" required="required" class="form-control" name="identity"
                                   placeholder="<?= lang('email') ?>"/>
                        </div>
                    </div>
                    <div class="textbox-wrap">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-key"></i></span>
                            <input type="password" required="required" class="form-control " name="password"
                                   placeholder="<?= lang('pw') ?>"/>
                        </div>
                    </div>
                    <?php if ($Settings->captcha) { ?>
                        <div class="textbox-wrap">

                            <div class="row">
                                <div class="col-sm-6 div-captcha-left">
                                    <span class="captcha-image"><?php echo $image; ?></span>
                                </div>
                                <div class="col-sm-6 div-captcha-right">
                                    <div class="input-group">
                                        <span class="input-group-addon"><a href="<?= base_url(); ?>auth/reload_captcha"
                                                                           class="reload-captcha"><i
                                                    class="fa fa-refresh"></i></a></span>
                                        <?php echo form_input($captcha); ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    <?php } /* echo $recaptcha_html; */ ?>

                    <div class="form-action clearfix">
                        <div class="checkbox pull-left">
                            <div class="custom-checkbox">
                                <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"'); ?>
                            </div>
                            <span class="checkbox-text pull-left"><label
                                    for="remember"><?= lang('remember_me') ?></label></span>
                        </div>
                        <button type="submit" class="btn btn-success pull-right"><?= lang('login') ?> &nbsp; <i
                                class="fa fa-sign-in"></i></button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
                <div class="login-form-links link2">
                    <h4 class="text-danger"><?= lang('forgot_your_password') ?></h4>
                    <span><?= lang('dont_worry') ?></span>
                    <a href="#forgot_password" class="text-danger forgot_password_link"><?= lang('click_here') ?></a>
                    <span><?= lang('to_rest') ?></span>
                </div>
                <?php if ($Settings->allow_reg) { ?>
                    <div class="login-form-links link1">
                        <h4 class="text-info"><?= lang('dont_have_account') ?></h4>
                        <span><?= lang('no_worry') ?></span>
                        <a href="#register" class="text-info register_link"><?= lang('click_here') ?></a>
                        <span><?= lang('to_register') ?></span>
                    </div>
                <?php } ?>
            </div>

        </div>
    </div>

    <div id="forgot_password" style="display: none;">
        <div class=" container">

            <div class="login-form-div">
                <div class="login-content">
                    <?php if ($error) { ?>
                        <div class="alert alert-danger">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $error; ?></ul>
                        </div>
                    <?php }
                    if ($message) { ?>
                        <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $message; ?></ul>
                        </div>
                    <?php } ?>
                    <div class="div-title">
                        <h3 class="text-primary"><?= lang('forgot_password') ?></h3>
                    </div>
                    <?php echo form_open("auth/forgot_password", 'class="login" data-toggle="validator"'); ?>
                    <div class="textbox-wrap">
                        <div class="input-group">
                            <span class="input-group-addon "><i class="fa fa-envelope"></i></span>
                            <input type="email" name="forgot_email" class="form-control "
                                   placeholder="<?= lang('email_address') ?>" required="required"/>
                        </div>
                    </div>
                    <div class="form-action clearfix">
                        <a class="btn btn-success pull-left login_link" href="#login"><i
                                class="fa fa-chevron-left"></i> <?= lang('back') ?>  </a>
                        <button type="submit" class="btn btn-primary pull-right"><?= lang('submit') ?> &nbsp;&nbsp; <i
                                class="fa fa-envelope"></i></button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>


        </div>
    </div>
    <?php if ($Settings->allow_reg) { ?>
        <div id="register">
            <div class=" container">

                <div class="registration-form-div">
                    <form>
                        <div class="div-title reg-header">
                            <h3 class="text-primary"><?= lang('register_account_heading') ?></h3>

                        </div>
                        <div class="clearfix">
                            <div class="col-sm-6 registration-left-div">
                                <div class="reg-content">
                                    <div class="textbox-wrap">
                                        <div class="input-group">
                                            <span class="input-group-addon "><i class="fa fa-user"></i></span>
                                            <input type="text" class="form-control "
                                                   placeholder="<?= lang('first_name') ?>" required="required"/>
                                        </div>
                                    </div>
                                    <div class="textbox-wrap">
                                        <div class="input-group">
                                            <span class="input-group-addon "><i class="fa fa-user"></i></span>
                                            <input type="text" class="form-control "
                                                   placeholder="<?= lang('last_name') ?>" required="required"/>
                                        </div>
                                    </div>
                                    <div class="textbox-wrap">
                                        <div class="input-group">
                                            <span class="input-group-addon "><i class="fa fa-envelope"></i></span>
                                            <input type="email" class="form-control "
                                                   placeholder="<?= lang('email_address') ?>" required="required"/>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="col-sm-6 registration-right-div">
                                <div class="reg-content">
                                    <div class="textbox-wrap">
                                        <div class="input-group">
                                            <span class="input-group-addon "><i class="fa fa-user"></i></span>
                                            <input type="text" class="form-control "
                                                   placeholder="<?= lang('username') ?>" required="required"/>
                                        </div>
                                    </div>
                                    <div class="textbox-wrap">
                                        <div class="input-group">
                                            <span class="input-group-addon "><i class="fa fa-key"></i></span>
                                            <input type="password" class="form-control " placeholder="<?= lang('pw') ?>"
                                                   required="required"/>
                                        </div>
                                    </div>
                                    <div class="textbox-wrap">
                                        <div class="input-group">
                                            <span class="input-group-addon "><i class="fa fa-key"></i></span>
                                            <input type="password" class="form-control "
                                                   placeholder="<?= lang('confirm_password') ?>" required="required"/>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="registration-form-action clearfix">
                            <a href="#login" class="btn btn-success pull-left login_link">
                                <i class="fa fa-chevron-left"></i> <?= lang('back') ?>
                            </a>
                            <button type="submit" class="btn btn-primary pull-right"><?= lang('register_now') ?> <i
                                    class="fa fa-user"></i></button>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    <?php } ?>
</div>

<script src="<?= $assets ?>js/jquery.js"></script>
<script src="<?= $assets ?>js/bootstrap.min.js"></script>
<script src="<?= $assets ?>js/jquery.cookie.js"></script>
<script src="<?= $assets ?>js/login.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var hash = window.location.hash;
        if (hash && hash != '') {
            $("#login").hide();
            $(hash).show();
        }
    });
</script>
</body>
</html>
