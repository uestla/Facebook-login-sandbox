<?php

use Nette\Application\UI;
use PresenterHelpers\AclHelper;


abstract class BasePresenter extends UI\Presenter
{
	/** @var AclHelper */
	protected $aclHelper;



	// === DEPENDENCIES =============================================================

	/**
	 * @param  AclHelper
	 * @return void
	 */
	function injectBase(AclHelper $ah)
	{
		$this->aclHelper = $ah;
	}



	// === STARTUP =============================================================

	/** @return void */
	protected function startup()
	{
		parent::startup();

		$privilegeFlag = $this->aclHelper->checkPrivileges();

		if ($privilegeFlag & AclHelper::INACTIVITY) {
			$this->user->logout(TRUE); // clear the identity
			$this->flashMessage('Byli jste odhlášeni z důvodu neaktivity.');
		}

		if ($privilegeFlag & AclHelper::LOGIN_REQUIRED) {
			$this->flashMessage('Pro tuto akci je třeba se přihlásit.');
			$this->user->logout(TRUE); // clear the identity
			$this->redirect('Homepage:default');
		}

		if ($privilegeFlag & AclHelper::NOT_ALLOWED) {
			$this->flashMessage('Pro danou akci nemáte dostatečná oprávnění!', 'error');
			$this->redirect('Homepage:default');
		}
	}
}
