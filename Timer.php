<?php
	class Timer {
		private static $timer = [];
		private static $childs = [];

		public static $format = 'H:i:s.m';

		private static function getTime()
		{
			return microtime(true);
		}

		private static function toFormat($duration)
		{
			return date(self::$format, $duration);
		}

		private static function treat($array)
		{
			$array['total'] = self::toFormat($array['end'] - $array['start']);
			$array['start'] = self::toFormat($array['start']);
			$array['end'] = self::toFormat($array['end']);

			return $array;
		}

		private static function prepare()
		{
			foreach (self::$timer as $key => $value) {
				if (array_key_exists($key, self::$childs)) {
					foreach ($value as $keyC => $valueC) {
						self::$timer[$key][$keyC] = self::treat($valueC);
					}
				} else {
					if (in_array($key, ['start', 'end'])) {
						if (in_array($key, ['start'])) {
							self::$timer = self::treat(self::$timer);
						}
					} else {
						self::$timer[$key] = self::treat($value);
					}
				}
			}
		}

		public static function start($key = null, $child = false)
		{
			$time = self::getTime();

			if ($key) {
				if ($child) {
					if (array_key_exists($key, self::$childs)) {
						self::$childs[$key]++;
					} else {
						self::$childs[$key] = 0;
					}

					self::$timer[$key][self::$childs[$key]]['start'] = $time;
				} else {
					self::$timer[$key]['start'] = $time;
				}
			} else {
				self::$timer['start'] = $time;
			}
		}

		public static function end($key = null, $child = false)
		{
			$time = self::getTime();

			if ($key) {
				if ($child) {
					self::$timer[$key][self::$childs[$key]]['end'] = $time;
				} else {
					self::$timer[$key]['end'] = $time;
				}
			} else {
				self::$timer['end'] = $time;
			}
		}

		public static function show($die = false)
		{
			self::prepare();

			echo '<pre>';
			print_r(self::$timer);
			echo '</pre>';

			if ($die) {
				die;
			}
		}

		public static function clear()
		{
			self::$timer = [];
			self::$childs = [];
		}
	}