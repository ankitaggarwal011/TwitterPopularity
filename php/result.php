<?php
    function getPopularParameter($query) {
        if (substr($query, 0, 1) == "#") {
            $query = "%23".substr($query, 1, strlen($query));
        }
        else {
            $query = '"'.$query.'"';
        }
        $web = 'https://twitter.com/search?src=typd&q=';
        $id = 'stream-items-id';
        $html = file_get_contents($web.$query.'+since%3A'.date('Y-m-d', strtotime('-1 day')));
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        $node = $dom->getElementById($id);
        if ($node) {
            return $node->getElementsByTagName('li')->length;
        }
        return 0;
    }

    error_reporting(E_ALL ^ E_NOTICE);
    ini_set('max_execution_time', 50); // 50 seconds
    $q1 = $_POST['hashtag1'];
    $q2 = $_POST['hashtag2'];
    if($q1 != $q2) {
        $output1 = getPopularParameter($q1);
        $output2 = getPopularParameter($q2);
        if ($output1 < $output2) {
            echo 'Yay! '.$q2.' is more popular on Twitter right now.';
        }
        else if ($output1 > $output2) {
            echo 'Yay! '.$q1.' is more popular on Twitter right now.';
        }
        else {
            echo 'Yay! Both '.$q1.' and '.$q2.' are popular on Twitter right now.';
        }
    }
    else {
        echo 'Please enter two different trends.';
    }
?>
