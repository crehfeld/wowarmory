<?php

namespace WowArmory\Processor;
use WowArmory\Processor\AbstractProcessor
  , WowArmory\Parser\Json\AuctionHouseDumpFileListParser
  , WowArmory\Parser\Parser
  , WowArmory\UrlBuilder
;

class AuctionHouseDumpFileListProcessor extends AbstractProcessor
{
	
	protected function init()
	{
        parent::init();
	}
	
	protected function addToDb(Parser $parser)
	{
		$this->addMostRecentFileUrl($parser);
		return true;
	}

    protected function addMostRecentFileUrl(AuctionHouseDumpFileListParser $p)
    {
        $file = $p->mostRecentFile();
        if ($file) {
            $additional_data = ['region' => $p->region(), 'realm' => $p->realm()];
            $this->addOrUpdateUrlToCrawlQueue(
                $file['url'],
                'aucfile',
                json_encode($additional_data)
            );
        }
    }

}