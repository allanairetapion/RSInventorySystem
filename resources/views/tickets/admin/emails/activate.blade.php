Click here to activate your password: <a href="{{ $link = url('admin/activate', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
