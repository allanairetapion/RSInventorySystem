Click here to reset your password: <a href="{{ $link = url('tickets/changePassword', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
