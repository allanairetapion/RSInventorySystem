Welcome to Remote Staff Ticketing System!

<p>You are receiving this email because an account was created for you! </p>
<p>Please activate your account by clicking the link below or by copying and pasting it into your browser's address bar.</p>

Follow this link to activate your account: <a href="{{ $link = url('admin/activate', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
