<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="resources/css/custom.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Submitit</title>
</head>

<body>
    <div>
        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <h1 class="navbar-brand-box">
                        <div class="logo logo-dark" align="center" style="background-color: #17252a; padding: 20px 0; box-shadow: 0 5px 18px 0px rgba(0,0,0,8%);">
                            <span class="logo-sm">
                                <img src="{{env('APP_URL')}}/images/logo-header.png" alt height="36" />
                            </span>
                        </div>
                    </h1>
                </div>
            </div>
        </header>
    </div>
    <div style="font-size:15px;">
        {!! $emailTemplate !!}
    </div>
</body>

</html>