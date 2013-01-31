<?php

use Model\Services\FacebookFacade;


class HomepagePresenter extends BasePresenter
{
	/** @var FacebookFacade */
	protected $facebook;



	// === DEPENDENCIES =============================================================

	function inject(FacebookFacade $f)
	{
		$this->facebook = $f;
	}



	// === ACTIONS =============================================================

	function actionDefault()
	{
		$this->setView( $this->user->loggedIn ? 'logged' : 'default' );
	}



	function actionFacebookLogin($token)
	{
		if (($sToken = $this->getToken('login', FALSE, $session)) && $token === $sToken) {
			unset($session->token);

			try {
				$this->user->login( $this->facebook->getUser() );

			} catch (FacebookApiException $e) {
				switch ($e->getCode()) {
					case 190: // unauthorized
						// silence
						break;

					default:
						$this->flashMessage('Při přihlašování došlo k chybě. Zkuste to prosím znovu.', 'error');
						break;
				}
			}
		}

		$this->redirect('default');
	}



	// === SIGNALS =============================================================

	function handleLogout($token)
	{
		if (($sToken = $this->getToken('logout', FALSE, $session)) !== NULL && $token === $sToken) {
			unset($session->token);
			$this->user->logout(TRUE);
			$this->redirectUrl( $this->facebook->getLogoutUrl(array(
				'next' => $this->link('//default'),
			)) );
		}

		$this->redirect('default');
	}



	// === RENDER =============================================================

	function renderDefault()
	{
		$this->template->loginUrl = $this->facebook->getLoginUrl(array(
			'scope' => 'email,user_birthday',
			'redirect_uri' => $this->link('//facebookLogin', array(
				'token' => $this->getToken('login'),
			)),
		));
	}



	function renderLogged()
	{
		$this->template->avatar = $this->facebook->getProfilePictureUrl( $this->user->identity->facebook_id );
		$this->template->name = $this->user->identity->name;
		$this->template->logout_token = $this->getToken('logout');
	}



	// === HELPERS =============================================================

	private function getToken($namespace, $generate = TRUE, & $session = NULL)
	{
		$session = $this->getSession("fb.$namespace");
		return isset($session->token) ? $session->token : ( $generate ? ($session->token = Nette\Utils\Strings::random()) : NULL );
	}
}
