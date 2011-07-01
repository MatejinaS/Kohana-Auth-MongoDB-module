<?php

defined('SYSPATH') or die('No direct script access.');
class Model_Auth extends Mongo_Document {
  protected $name = 'users';
  protected $db = 'default';
}

