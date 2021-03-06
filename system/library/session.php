<?php
/**
 * @package		Kant Framework
 * @author		Emirhan Yumak
 * @copyright	Copyright (c) 2016 - 2020, Kant Yazılım A.Ş. (https://kant.ist/)
 * @license		https://opensource.org/licenses/mit
 * @link		https://kant.ist
*/

/**
* Session class
*/
class Session {
	protected $adaptor;
	protected $session_id;
	public $data = array();

	/**
	 * Constructor
	 *
	 * @param	string	$adaptor
	 * @param	object	$registry
	*/
	public function __construct($adaptor, $registry = '') {
		$class = 'Session\\' . $adaptor;
		
		if (class_exists($class)) {
			if ($registry) {
				$this->adaptor = new $class($registry);
			} else {
				$this->adaptor = new $class();
			}	
			
			register_shutdown_function(array($this, 'close'));
		} else {
			trigger_error('Error: Could not load cache adaptor ' . $adaptor . ' session!');
			exit();
		}	
	}

	/**
	 * 
	 *
	 * @return	string
	*/	
	public function getId() {
		return $this->session_id;
	}

	/**
	 *
	 *
	 * @param	string	$session_id
	 *
	 * @return	string
	*/	
	public function start($session_id = '') {
		ini_set('session.use_only_cookies', 'Off');
		ini_set('session.use_cookies', 'On');
		ini_set('session.use_trans_sid', 'Off');
		ini_set('session.cookie_httponly', 'On');
		ini_set('session.cookie_path', '/');
		ini_set('session.cookie_domain', COOKIE_DOMAIN);
		ini_set('session.cookie_lifetime', SESSION_EXPIRE);
		ini_set('session.cookie_samesite', 'Lax');

		if (!$session_id) {
			if (function_exists('random_bytes')) {
				$session_id = substr(bin2hex(random_bytes(26)), 0, 26);
			} else {
				$session_id = substr(bin2hex(openssl_random_pseudo_bytes(26)), 0, 26);
			}
		}

		if (preg_match('/^[a-zA-Z0-9,\-]{22,52}$/', $session_id)) {
			$this->session_id = $session_id;
		} else {
			exit('Error: Invalid session ID!');
		}

		if (PHP_VERSION_ID >= 70300) { 
			session_set_cookie_params([
				'lifetime' => SESSION_EXPIRE,
				'path' => '/',
				'domain' => SESSION,
				'secure' => true,
				'samesite' => 'Lax'
			]);
		} else { 
			session_set_cookie_params(
				SESSION_EXPIRE,
				'/; samesite=Lax',
				SESSION,
				true
			);
		}

		$this->data = $this->adaptor->read($session_id);
		
		return $session_id;
	}

	/**
	 * 
	*/
	public function close() {
		$this->adaptor->write($this->session_id, $this->data);
	}

	/**
	 * 
	*/	
	public function destroy() {
		$this->adaptor->destroy($this->session_id);
	}
}