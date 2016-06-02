<?php

/**
 * The User Account Class
 *
 * @author Faizan Ayubi, Hemant Mann
 */
use Shared\Controller as Controller;
use Framework\Registry as Registry;
use Framework\RequestMethods as RequestMethods;
use Shared\Services\Youtube as Youtube;
use Framework\StringMethods as StringMethods;

class Account extends Controller {

	/**
	 * @before _secure
	 */
	public function index() {
		$this->seo(array("title" => "Profile"));
		$view = $this->getActionView();
	}
	
	public function create() {
		$this->seo(array("title" => "Create Account"));
		$view = $this->getActionView();

		if (RequestMethods::post("action") == "create") {
			$users = Registry::get("MongoDB")->users;
			
			try {
				$email = RequestMethods::post("email", "[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$");
				$password = RequestMethods::post("password");
				
				$user = Models\User::first(array('email' => $email));
				if (!$user) {
					$password = StringMethods::encrypt($password);

					$user = new Models\User([
						'name' => RequestMethods::post("name", "[a-zA-Z]+\s+[a-zA-Z]+"),
					    'email' => $email,
					    'password' => $password,
					    'live' => 1
					]);
					$user->save();

				    $view->set("message", "User Registered Successfully");
				}
			} catch (\Exception $e) {
				$view->set("message", $e->getMessage());
			}
			
		}
	}

	public function apikey() {
		$this->seo(array("title" => "Generate API Key"));
		$view = $this->getActionView();
	}
}
