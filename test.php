<?php
	$elements = array();
	////
	// An array of 10,000 elements with random string values
	////
	for($i = 0; $i < 10000; $i++) {
		$elements[] = (string)rand(10000000, 99999999);
	}
	$time_start = microtime(true);
	////
	// for test
	////
        $numb = count($elements);
	for($i = 0; $i < $numb; $i++) { }
	$time_end = microtime(true);
	$for_time = $time_end - $time_start;
        $time_start = microtime(true);
	////
	// for with count() inside loop test
	////
	for($i = 0; $i < count($elements); $i++) { }
	$time_end = microtime(true);
	$for_count_time = $time_end - $time_start;
	$time_start = microtime(true);
	////
	// foreach test
	////
	foreach($elements as $element) { }
	$time_end = microtime(true);
	$foreach_time = $time_end - $time_start;
	echo "For took: " . number_format($for_time * 1000, 3) . "ms\n";
        echo "For with count() took: " . number_format($for_count_time * 1000, 3) . "ms\n";
	echo "Foreach took: " . number_format($foreach_time * 1000, 3) . "ms\n";

	//Wed Jul 11 05:36:22 +0000 2018
	$date = date_create("Wed Jul 11 05:36:22 +0000 2018");
	//date_sub($date,date_interval_create_from_date_string("20 days"));
	//$date = date_format($date,"Y-m-d");
	$date2 = date_create(date('Y-m-d'));
	$sub = date_diff($date2 , $date);
	var_dump($sub->d);
	exit;

?>
