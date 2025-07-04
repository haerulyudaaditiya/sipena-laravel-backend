<?php

namespace App\Helpers;

class LocationHelper
{
  /**
   * Menghitung jarak antara dua titik koordinat GPS menggunakan formula Haversine.
   * Hasilnya dalam meter.
   */
  public static function distance($lat1, $lon1, $lat2, $lon2)
  {
    if (($lat1 == $lat2) && ($lon1 == $lon2)) {
      return 0;
    }

    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;

    // Konversi dari mil ke meter (1 mil = 1609.344 meter)
    return ($miles * 1.609344 * 1000);
  }
}
