<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Auth extends Controller
{
	public function action_index()
	{
		$data = "";
		
		if(!Auth::instance()->logged_in())
		{
			Auth::instance()->login('username', 'password');
			
			if(Auth::instance()->logged_in())
				$data = Debug::vars(Auth::instance()->get_user());
			else
				$data = HTML::anchor('/auth/register/', 'Register');
		}
		else
		{
			// Show user data
			$data = Debug::vars(Auth::instance()->get_user());
			
			// Logout user
			Auth::instance()->logout();
		}
		
		$this->response->body($data);
	}
	
	public function action_register()
	{
		//register user
		Auth::instance()->register(array(
			'username' => 'username',
			'password' => 'password',
			'email' => 'email@email.com',
			'first_name' => 'First',
			'last_name' => 'Last',
			'address' => 'Address',
			'post_office' => '1234',
		));
		
		$this->response->body(HTML::anchor('/auth/', 'Login'));
	}
}