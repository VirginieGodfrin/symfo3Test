<?php

namespace Test\BlogBundle\Beta;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;


class BetaListener{

	//processeur
	protected $betaHtml;
	protected $endDate;

	public function __construct(BetaHtmlAdder $betaHtml, $endDate){
		$this->betaHtml = $betaHtml;
		$this->endDate = new \Datetime($endDate);
	}

	public function processBeta(FilterResponseEvent $event){
		$remainingDays = $this->endDate->diff(new \Datetime())->days;

		if($remainingDays <= 0){
			return;
		}

		$response = $this->betaHtml->addBeta($event->getResponse(), $remainingDays);

		$event->setResponse($response);
	}

}