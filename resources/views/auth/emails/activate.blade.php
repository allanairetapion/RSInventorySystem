Welcome to Remote Staff Ticketing System!

<p>Hi {{$user['first_name']}}, You are receiving this email because you recently created an account to our website. </p>
<p>Before you can login, however, you need to confirm your account by 
	clicking the link below or by copying and pasting it into your browser's address bar. </p>
<a href="{{ $link = url('tickets/activate', $user['_token']).'?email='.urlencode($user['email']) }}"> {{ $link }} </a>
<br>
<p>Your account will be activated once you visited the site.</p>

<p>You can find your credentials below: </p>
Email: {{$user['email']}} <br>
Password: {{$user['password']}} <br>

<p>If you have any problems or questions, please don't hesitate to ask the supports.</p>