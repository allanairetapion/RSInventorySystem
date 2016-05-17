Click here to reset your password: <a href="{{ $link = url('admin/changePassword', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
