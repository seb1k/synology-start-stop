<?php


$IP = "192.168.1.10";		// Synology IP address
$MAC = "00-11-32-AA-BB-CC"; // Synology MAC address

$API_USER="admin";			// Synology admin account
$API_PASS="adminpassword";	// Synology admin account password


if(isset($_GET["start"]))
	{
	wol("255.255.255.255", $MAC);
	}


if(isset($_GET["stop"]))
	{

	# request 1 : Auth
	$postdata = http_build_query(
    array(
        'api' => 'SYNO.API.Auth',
        'version' => '7',
		'method' => 'login',
		'account' => $API_USER,
		'passwd' => $API_PASS
		)
	);

	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => 'Content-Type: application/x-www-form-urlencoded',
			'content' => $postdata
		)
	);

	$context  = stream_context_create($opts);
	$authinfo= file_get_contents("http://$IP:5000/webapi/entry.cgi", false, $context);

	//print_r($authinfo);

	$jsoninfo=json_decode($authinfo);



	if($jsoninfo->success!=true)
		{
		echo('Error auth');
		}
	else
		{
		$sid=$jsoninfo->data->sid;

		# request 2 : Shutdown
			$postdata = http_build_query(
			array(
				'api' => 'SYNO.Core.System',
				'version' => '1',
				'method' => 'shutdown',
				'_sid' => $sid
			)
		);

		$opts = array('http' =>
			array(
				'method'  => 'POST',
				'header'  => 'Content-Type: application/x-www-form-urlencoded',
				'content' => $postdata
			)
		);

		$context  = stream_context_create($opts);
		$apiinfo= file_get_contents("http://$IP:5000/webapi/entry.cgi", false, $context);

		$jsoninfo=json_decode($apiinfo);

		if($jsoninfo->success!=true)
			echo('Error shutdown');
		}

	}









function wol($broadcast, $mac)
{
    $hwaddr = pack('H*', preg_replace('/[^0-9a-fA-F]/', '', $mac));

    // Create Magic Packet
    $packet = sprintf(
        '%s%s',
        str_repeat(chr(255), 6),
        str_repeat($hwaddr, 16)
    );

    $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

    if ($sock !== false) {
        $options = socket_set_option($sock, SOL_SOCKET, SO_BROADCAST, true);

        if ($options !== false) {
            socket_sendto($sock, $packet, strlen($packet), 0, $broadcast, 7);
            socket_close($sock);
        }
    }
}
?>
<!DOCTYPE html>
<head>

</head>
<body>


<br/>
<br/>

<button onclick="window.location.href='?start=1'" style='cursor:pointer'>Start</button>

<button onclick="window.location.href='?stop=1'" style='cursor:pointer'>Stop</button>


</body>