
<p>We received a request to reset your password for your Ticketing System account. </p>

Click the link below to set up your new password:<br> 

<a href="{{ $link = url('admin/changePassword', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
<br>

<p>If you don't mean to reset your password, then you can just ignore this email.</p>