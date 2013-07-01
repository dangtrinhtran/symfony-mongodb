<?php

namespace Likipe\BackendBundle\Services;

class LogService {

	public function writeLog($message) {
		$file = __DIR__ . '/../../../../web/log/likipe_blog.log';
		if ( file_exists($file) && is_writable($file)) {
			$fp = fopen($file, 'a');
			$time = time('now') + 7*3600;
			#$dateLog = date("l, d F Y h:i:s A", $time);
			$dateLog = gmdate("l, d F Y h:i:s A", $time);
			$sMessage = $dateLog . ' - ' . $message;
			fwrite($fp, $sMessage . "\r\n");

			fclose($fp);
		}
	}

}
