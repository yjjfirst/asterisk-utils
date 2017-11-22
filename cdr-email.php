<?php

fclose(STDOUT);
$stream = fopen('/tmp/zINXVZ8cOwmr','w+');
$STDOUT = $stream;

date_default_timezone_set('America/New_York');

$username = 'asterisk';
$password = '123456';
$database = 'asterisk';

$dbc = mysqli_connect('localhost', $username, $password, $database)
    or die('Error connecting to MySQL server.');

$date = date("Y-m-d");
//echo $date;

$start = $date . ' 00:00:00';
$end = $date . ' 23:59:59';
//echo $start, ' ' , $end;

$query = "SELECT * FROM cdr WHERE start BETWEEN '$start' AND '$end'";
//echo $query;

$result = mysqli_query($dbc, $query)
    or die('Error querying datebase.');

$outbounds = 0;
$inbounds = 0;
$missed = 0;

$content =array();
$i = 0;
while($row = mysqli_fetch_array($result)) {
    $content[$i] = array(
	"start" => $row['start'],
        "clid" => $row['clid'],
        "src" => $row['src'],
        "dst" => $row['dst'],
        "duration" => $row['duration'],
        "disposition" =>$row['disposition']);

    if (strstr($row['clid'], 'hsbaemployment') != FALSE) $outbounds ++;
    if (strlen($row['src']) != 3) $inbounds ++;
    if (strlen($row['src']) != 3) {
        if ($row['disposition'] == 'NO ANSWER') $missed ++;
        if ($row['disposition'] == 'ANSWERED' && $row['dcontext'] == 'voicemail') $missed ++; 
    }

    $i ++;
}

echo '<html><body>';
echo array2table($content);
echo '</html></body>';
echo '<br>';
echo "<br>$outbounds outbound calls";
echo "<br>$inbounds received calls";
echo "<br>$missed missed calls";

rewind($stream);
$contents_string = stream_get_contents($stream);

$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/html; charset=iso-8859-1';

mail('hsbaemployment@gmail.com',"Call Log - $date", $contents_string,implode("\r\n", $headers));
mail('yjjfirst@hotmail.com',"Call Log - $date", $contents_string,implode("\r\n", $headers)); 


fclose($stream);
mysqli_close($dbc);

function array2table($array, $recursive = false, $null = '&nbsp;')
{
    // Sanity check
    if (empty($array) || !is_array($array)) {
        return false;
    }

    if (!isset($array[0]) || !is_array($array[0])) {
        $array = array($array);
    }

    // Start the table
    $table = "<table>\n";

    // The header
    $table .= "\t<tr>";
    // Take the keys from the first row as the headings
    foreach (array_keys($array[0]) as $heading) {
        $table .= '<th>' . $heading . '</th>';
    }
    $table .= "</tr>\n";

    // The body
    foreach ($array as $row) {
        $table .= "\t<tr>" ;
        foreach ($row as $cell) {
            $table .= '<td>';

            // Cast objects
            if (is_object($cell)) { $cell = (array) $cell; }
            
            if ($recursive === true && is_array($cell) && !empty($cell)) {
                // Recursive mode
                $table .= "\n" . array2table($cell, true, true) . "\n";
            } else {
                $table .= (strlen($cell) > 0) ?
                    htmlspecialchars((string) $cell) :
                    $null;
            }

            $table .= '</td>';
        }

        $table .= "</tr>\n";
    }

    $table .= '</table>';
    return $table;
}
