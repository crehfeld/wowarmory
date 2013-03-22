<?php
namespace WowArmory\Service;

use WowArmory\Service\Service
  , WowArmory\ThrottledUrlFetcher
  , WowArmory\Task\UrlTaskProviderFactory
  , WowArmory\Throttler\TimeIntervalThrottler
  ;

class UrlFetchingService implements Service
{
	protected $dbh;
	
	function __construct(\PDO $dbh)
	{
		$this->dbh = $dbh;
	}
	
    function run()
	{
		$secondsBetweenRequests = 86400 / 3000;
		$secondsBetweenRequests = 2;
        
        $sql = "
		select url
		   , last_attempt
		from urls_to_fetch
		where (http_response_code is null or http_response_code != 200)
		  and (now() - interval 1 day > last_attempt)
		order
		  by last_attempt asc, created_at asc
		limit 1
		";
		$pdoStmt = $this->dbh->prepare($sql);
        
        
        $callback = function() use ($pdoStmt) {
            $pdoStmt->execute();
            return $pdoStmt->fetch();
        };
        
        $factory = new UrlTaskProviderFactory($this->dbh);
        $taskProvider = $factory->create($callback);
        
        

		$fetcher = new ThrottledUrlFetcher(
			$taskProvider,
			new TimeIntervalThrottler($secondsBetweenRequests)
		);

		$fetcher->run();
	}
}