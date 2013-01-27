<?php

namespace PresenterHelpers;

use Nette;
use Nette\Application\Application;
use Nette\Application\UI\Presenter;


/** @property-read Presenter $presenter */
class BaseHelper extends Nette\Object
{
	/** @var Application */
	protected $application;



	/**
	 * @param  Application
	 * @return void
	 */
	function injectBase(Application $application)
	{
		$this->application = $application;
	}



	/** @return Nette\Application\IPresenter */
	function getPresenter()
	{
		return $this->application->presenter;
	}
}
