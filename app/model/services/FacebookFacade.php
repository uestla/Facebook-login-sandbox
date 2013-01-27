<?php

namespace Model\Services;

use Nette;
use Facebook;
use BaseFacebook;
use Nette\Utils\Arrays;


/**
 * @method BaseFacebook setAppId(string $appId)
 * @method BaseFacebook setAppSecret(string $apiSecret)
 * @method BaseFacebook setFileUploadSupport(bool $fileUploadSupport)
 * @method BaseFacebook setAccessToken(string $access_token)
 * @method void setExtendedAccessToken()
 * @method string getAppId()
 * @method string getAppSecret()
 * @method bool getFileUploadSupport()
 * @method string getAccessToken()
 * @method string getUserAccessToken()
 * @method string getSignedRequest()
 * @method string getLoginUrl(array $params = array())
 * @method string getLogoutUrl(array $params = array())
 * @method string getLoginStatusUrl(array $params = array())
 * @method mixed api()
 * @method void destroySession()
 */
class FacebookFacade extends Nette\Object
{
	/** @var Facebook */
	protected $fb;



	/**
	 * @param  int
	 * @param  string
	 */
	function __construct($appID, $secret)
	{
		$this->fb = new Facebook(array(
			'appId' => $appID,
			'secret' => $secret,
		));
	}



	/**
	 * @param  string|NULL
	 * @return array|NULL
	 */
	function getUser($fbID = NULL)
	{
		if ($fbID === NULL) {
			if ($this->fb->getUser()) {
				return $this->fb->api('/me');
			}

			return NULL;

		} else {
			return $this->fb->api("/$fbID");
		}
	}



	/** @return array */
	function getFriends()
	{
		return Arrays::get( $this->fb->api('/me/friends'), 'data' );
	}



	/**
	 * @param  string
	 * @param  string
	 * @return string
	 */
	function getProfilePictureUrl($fbID, $type = 'square')
	{
		return "https://graph.facebook.com/$fbID/picture?type=$type";
	}



	/**
	 * Calls internal facebook SDK task
	 *
	 * @param  string
	 * @param  array
	 * @return mixed
	 */
	function __call($name, $args)
	{
		$ref = Nette\Reflection\ClassType::from($this->fb);

		if ($ref->hasMethod($name)) {
			return callback($this->fb, $name)->invokeArgs($args);
		} else {
			return parent::__call($name, $args);
		}
	}
}
