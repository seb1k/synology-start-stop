# synology-start-stop
Turn on and turn off a Synology NAS via wake-on-lan / API in PHP


Configure the first lines of the PHP file with your synology information


    $IP="192.168.1.10";
    $MAC="00-11-32-AA-BB-CC";
    
    $API_USER="admin";
    $API_PASS="adminpassword";


Turn on the Wake-on-LAN option in your synology.


Use the Start Stop button to turn on and off your synology from the web page.



> **Note**
> 
> The wake on lan functionality can be disabled on power loss.
> 
> Boot and shutdown can take up to 3 minutes

