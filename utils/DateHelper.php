<?php

namespace App\Utils;

use DateTime;
use DateTimeZone;

class DateHelper
{
	public static function toUTC(?string $fechaLocal): ?string
	{
		if (!$fechaLocal) return null;

		$dt = new DateTime($fechaLocal, new DateTimeZone('America/Bogota'));
		$dt->setTimezone(new DateTimeZone('UTC'));
		return $dt->format('Y-m-d H:i:s');
	}

	public static function toLocal(?string $fechaUTC): ?string
	{
		if (!$fechaUTC) return null;

		$dt = new DateTime($fechaUTC, new DateTimeZone('UTC'));
		$dt->setTimezone(new DateTimeZone('America/Bogota'));
		return $dt->format('Y-m-d H:i:s');
	}
}
