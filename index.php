<?php
	include 'Timer.php';

	Timer::$format = 'i:s.m';

	Timer::start();

	Timer::start('test');

	sleep(2);

	Timer::end('test');

	for ($i=0; $i <= 3; $i++) {
		Timer::start('test[]', true);

		sleep($i);

		Timer::end('test[]', true);
	}

	Timer::end();

	Timer::show();

	Timer::clear();
