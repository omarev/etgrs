<?php 

// $url = '';
// $alertRecepient = 'user@example.org'; // Recepient
// $delayPeriod = 60; // Delay between requests in seconds

$shortopts = '';
$longopts = [
    "url:",
    "alert-recepient:",
    "delay-period:",
];

$opts = getopt($shortopts, $longopts);


if (empty($opts['url'])) {
    echo "Invalid option url!\n";
    return;
}

if (empty($opts['alert-recepient'])) {
    echo "Invalid option alert-recepient!\n";
    return;
}

if (empty($opts['delay-period'])) {
    echo "Invalid option delay-period!\n";
    return;
}
$url = $opts['url'];
$alertRecepient = $opts['alert-recepient'];
$delayPeriod = $opts['delay-period'];

$options = array(
    'http'=>array(
      'method'=>"GET",
      'header'=>
        "Accept-language: en\r\n" .
        "Cookie: foo=bar\r\n" .  // check function.stream-context-create on php.net
        "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad 
    )
);

echo "Starting .. \n";
echo "========================= \n";
echo "url: $url \n";
echo "alert-recepient: $alertRecepient \n";
echo "delay-period: $delayPeriod .. \n";
echo "========================= \n";

while(true) {

    echo date('H:i:s') . "\n";

    $context = stream_context_create($options);
    $data = file_get_contents($url, false, $context);
    
    $json = json_decode($data);

    if (empty($json->data)) {
        $success = mail($alertRecepient, __FILE__, 'Alert: request updated!');

        if (!$success) {
            $errorMessage = error_get_last()['message'];
            echo $errorMessage. "\n";
        }else {
            echo "Notification sent!\n";
        }

        
        return;
    }
    sleep($delayPeriod);
}
