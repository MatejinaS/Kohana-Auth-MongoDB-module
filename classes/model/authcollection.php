<?php defined('SYSPATH') or die('No direct script access.');

class Model_AuthCollection extends Mongo_Collection 
{
	protected $name = 'users';
	protected $db = 'default';
}