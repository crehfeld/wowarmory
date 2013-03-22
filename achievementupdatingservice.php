<?php

/*
 * grabs the latest list of achievements from the blizzard api, and updates our local db with the info.
 * 
This could be left as a running process that sleeps for a day or whatever interval you want updates
but right now it updates once and stops due to the break statement. 
*/

require 'cli-config.php';
use WowArmory\Throttler\TimeIntervalThrottler
  , WowArmory\Parser\Json\AchievementListParser
  ;

set_time_limit(200);
$sleepSeconds = 1;
$throttler = new TimeIntervalThrottler($sleepSeconds);

$regions = array('us');



while (true) {
    foreach ($regions as $region) {
        $url = "http://{$region}.battle.net/api/wow/data/character/achievements";
        $parser = new AchievementListParser(file_get_contents($url));
        $updater = new AchievementListUpdater($parser, $dbh, $region);
        $updater->update();
    }
    $throttler->sleep();
    break;
}