<!DOCTYPE html>
<html>

<head>

    <meta name="viewport" content="width=device-width,initial-scale=1.0" />

    <link rel="stylesheet" href="css/bootstrap.css" />
    <link rel="stylesheet" href="css/formstyles.css" />
    <link rel="stylesheet" href="fonts/fontawesome/css/all.min.css" />
    <link rel="stylesheet" href="fonts/fontawesome/css/fontawesome.min.css" />
    <link rel="stylesheet" href="fonts/fontawesome/css/brands.min.css" />
    <link rel="stylesheet" href="fonts/fontawesome/css/solid.min.css" />
    <link rel="stylesheet" href="fonts/fontawesome/css/regular.min.css" />


</head>

<body>
    <div class="signup">
        <div class="container">
            <div class="row justify-content-center">
                <div
                    class=" signup-container px-sm-3 px-md-4 px-lg-5 py-4 shadow-lg align-self-center col-sm-12 col-md-10 col-lg-8 col-xl-6">

                    <a href="/index.html" class="exit-btn"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
                    <div class="logo-container text-center">
                        <img src="./images/logo.png" alt />
                        <h2 class="mt-4 color">ثبت نام</h2>
                    </div>
                    <div class="form-container">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label class="mb-1"><strong>نام کاربری</strong></label>
                                <input type="text" class="form-control px-5" id=" user-field" name="username"
                                    placeholder="نام کاربری" />
                                <div id="user-error" class=" text-danger fs-6">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="mb-1"><strong>رمزعبور</strong></label>
                                <input type="password" class="form-control px-5" id="pass-field" placeholder="رمزعبور">
                                <div id="pass-error" class="pass-error text-danger fs-6">

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="mb-1"><strong>رمزعبور جدید</strong></label>
                                <input type="password" class="form-control px-5" id="new-pass-field"
                                    placeholder="رمزعبور">
                                <div id="pass-error" class="pass-error text-danger fs-6">

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="mb-1"><strong>تکرار رمزعبور جدید</strong></label>
                                <input type="password" class="form-control px-5" id="conf-new-pass-field"
                                    placeholder=" تکرار رمزعبور">
                                <div id="conf-pass-error" class="text-danger fs-6">

                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-block">ثبت نام </button>
                            </div>
                        </form>
                    </div>
                    <div class="new-account mt-3">
                        <p>از قبل حساب کاربری دارید؟ <a class="text-primary pe-1" href="page-login.html">ورود</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>