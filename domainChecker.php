<?php

$cf['token'] = 'YOUR_CF_API_TOKEN_HERE';
$cf['mail'] = 'YOUR_CF_EMAIL_HERE';
$cf['headers'] = array('X-Auth-Email: '.$cf['mail'], 'Authorization: Bearer '.$cf['token']);


$api = 'https://api.cloudflare.com/client/v4/zones?page=1&per_page=50&order=name&direction=asc&match=all';
$now = time();

$ch = curl_init($api);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $cf['headers']);
$a = curl_exec($ch);

$data = json_decode($a, TRUE) or die('Err parsing JSON');


$output = array();

foreach($data['result'] as $key => $result)
	{
		$domain = $result['name'];
		list($name, $tld) = explode('.', $domain);
		
		$query = shell_exec('whois '.$domain);
		
		if($tld == 'ru' | $tld == 'su' | $tld == 'рф')
			{
				preg_match('#paid\-till\:(?:\s+)((?:\d+)\-(?:\d+)\-(?:\d+))T#iu', $query, $res);
			}
		else
			{
				preg_match('#Registry(?:\s+)Expiry(?:\s+)Date\:(?:\s+)((?:\d+)\-(?:\d+)\-(?:\d+))T#iu', $query, $res);
			}
		
		if(!empty($res[1]))
			{
				$date = $res[1];
			}
		else
			{
				$date = $query;
			}
		
		$convert = strtotime($date);
		
		if(is_int($convert))
			{
				$secs = $convert - $now;
				
				$days = floor($secs / 86400);
			}
		else
			{
				$days = 'Unknown';
			}
		
		if($days < 30 && $days > 7)
			{
				$status = 'MINOR';
			}
		elseif(($days <= 7 && $days > 2) OR $days == 'Unknown')
			{
				$status = 'MAJOR';
			}
		elseif($days < 2)
			{
				$status = 'CRITICAL';
			}
		else
			{
				$status = 'NORMAL';
			}
		
		// echo $status.'	<b>'.$domain.'</b>: <i>'.$date.'</i> - <b>'.$days.'</b> days left<br /><br />'.PHP_EOL.PHP_EOL;
		
		$output[] = array('domain' => $domain, 'paid_till' => $date, 'days_left' => $days, 'status' => $status);
		
		// flush();
	}

$json = json_encode($output);

print $json;
