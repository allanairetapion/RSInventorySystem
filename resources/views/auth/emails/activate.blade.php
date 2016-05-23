Click here to activate your password: <a href="{{ $link = url('tickets/activate', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
