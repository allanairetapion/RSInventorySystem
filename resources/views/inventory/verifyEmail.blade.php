<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Verify Your Email Address</h2>

        <div>
            Thank You for creating an account on devs.rsoperation.remotestaff.com.au
            Please Clink the link below to verify your email address
            {{ URL::to('register/verify/' . $ims_users->confirmation_code) }}.<br>

        </div>

    </body>
</html>