<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>{{ trans('confide.email.password_reset.subject') }}</title>
<link rel="stylesheet" href="{{ url('/assets/css/frontend.css?v=' . Config::get('system.version')) }}" />
<style type="text/css">
body {
	margin:20px;
}
</style>
</head>
<body>

<div class="panel panel-info panel-dark">
	<div class="panel-heading">
		<span class="panel-title">{{ trans('confide.email.password_reset.subject') }}</span>
	</div>
	<div class="panel-body">

@foreach ($errors->all() as $error)

  <div class="alert alert-danger">{{ $error }}</div>

@endforeach

<?php
if ($reset) {
?>
<p>{{ trans('confide.alerts.password_reset') }}</p>
<a href="{{ $domain }}" class="btn btn-success btn-lg btn-block">Click here to continue</a>
<?php
} else {
?>

		<form method="post" action="{{ url('/mobile/reset_password/' . $token) }}">

			<div class="form-group">
				<label for="password">{{ trans('global.new_password') }}</label>
				<input type="password" name="password" class="form-control">
			</div>

			<div class="form-group">
				<label for="password_confirmation">{{ trans('confide.password_confirmation') }}</label>
				<input type="password" name="password_confirmation" class="form-control">
			</div>

			<button type="submit" class="btn btn-success btn-lg btn-block">{{ trans('global.reset') }}</button>
		</form>
<?php
}
?>
	</div>
</div>

</body>
</html>