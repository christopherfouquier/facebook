<?php

namespace facebook;

use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookSession;

/**
 * Facebook
 *
 * @author Fouquier Christopher <christopher.fouquier@gmail.com>
 */
class Facebook {
	
	private $appId;
	private $appSecret;
	private $scope;
	private $redirectUrl;

	/**
	 * @param $appId Facebook Application ID
	 * @param $appSecret Facebook Application secret
	 */
	public function __construct($appId, $appSecret, $redirectUrl = null, $scope = array()) {
		$this->appId = $appId;
		$this->appSecret = $appSecret;
		$this->scope = $scope;
		$this->redirectUrl = $redirectUrl;
	
		FacebookSession::setDefaultApplication($this->appId, $this->appSecret);
	}

	public function setSession($session) {
		$this->session = $session;
	}

	public function getSession() {
        return $this->session;
	}

	/**
     * @return string|Facebook\GraphUser Login URL or GraphUser
     */
    public function connect() {
        $helper = new FacebookRedirectLoginHelper($this->redirectUrl);
        
        if (isset($_SESSION) && isset($_SESSION['fb_token'])) {
            $this->setSession(new FacebookSession($_SESSION['fb_token']));
        }
        else {
            $this->setSession($helper->getSessionFromRedirect());
        }

        if ($this->getSession()) {
            try {
                $_SESSION['fb_token'] = $this->getSession()->getToken();
                $profile = $this->getUser();
                if ($profile->getEmail() === null) {
                    throw new \Exception("L'email n'est pas disponible");
                }
                return $profile;
            }
            catch (\Exception $e) {
                unset($_SESSION['fb_token']);
                return $helper->getReRequestUrl($this->scope);
            }
        }
        else {
            return $helper->getLoginUrl($this->scope);
        }
	}

	public function api($path, $method = 'GET', $params = array()) {
        $request = new FacebookRequest($this->getSession(), $method, $path, $params);
        $response = $request->execute();
        return $response;
	}

	public function getUser() {
		$graphObject = $this->api('/me')->getGraphObject('Facebook\GraphUser');
        return $graphObject;
	}

	public function getFeed() {
		$graphObject = $this->api('/me/feed')->getGraphObject()->asArray();
        return $graphObject;
	}
}