<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Mongo Auth driver.
 * [!!] this Auth driver does not support roles nor autologin.
 *
 * @package    Kohana/Auth
 * @author     Sašo Matejina
 * @copyright  (c) 2011 DEVNULL
 */
class Kohana_Auth_Mongo extends Auth 
{
	/**
	 * Constructor loads the user list into the class.
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Logs a user in.
	 *
	 * @param   string   username
	 * @param   string   password
	 * @param   boolean  enable autologin (not supported)
	 * @return  boolean
	 */
	protected function _login($username, $password, $remember)
	{
		$user_model = new Model_AuthCollection();
		$params = array(
			'$or' => array(
				array('username' => $username),
				array('email' => $username),
			), 
			'password' => $password);
		$user = $user_model->findOne($params);
		
		if ($user)
		{
			// Complete the login && unset _id and password
			unset($user['_id']);
			unset($user['password']);
			return $this->complete_login($user);
		}

		// Login failed
		return FALSE;
	}

	/**
	 * Forces a user to be logged in, without specifying a password.
	 *
	 * @param   mixed    username
	 * @return  boolean
	 */
	public function force_login($username)
	{
		// Complete the login
		return $this->complete_login($username);
	}

	/**
	 * Get the stored password for a username.
	 *
	 * @param   mixed   username
	 * @return  string
	 */
	public function password($username)
	{
		$user_model = new Model_AuthCollection();
		$user = $user_model->findOne(array('username' => $username));
		
		if($user)
			return $user['password'];
		else
			return false;
	}

	/**
	 * Compare password with original (plain text). Works for current (logged in) user
	 *
	 * @param   string  $password
	 * @return  boolean
	 */
	public function check_password($password)
	{
		$user = $this->get_user();

		if ($username === FALSE)
		{
			return FALSE;
		}

		return ($password === $this->password($user['username']));
	}
	
	/**
	 * Check uniqe user data
	 * @param string $key
	 * @param string $value
	 * @throws Exception
	 * @return boolean
	 */
	public function check_uniqe($key = false, $value = false)
	{
		if(!$key || !$value)
			throw new Exception('Missing key or value for check_uniqe');
		
		$user_model = new Model_AuthCollection();
		$data = $user_model->findOne(array($key => $value));
		
		if(!$data)
			return true;
		else
			return false;
	}
	
	/**
	 * Register new user / validation should be done before this!!!
	 * @param array $data
	 * @return boolean
	 */
	public function register($data = array())
	{
		$user = new Model_Auth();
		
		$data['password'] = $this->hash($data['password']);
		
		foreach($data as $key => $value)
		{
			$user->{$key} = $value;
		}
		
		if($user->save())
			return true;
		
		return false;
	}

} // End Auth File