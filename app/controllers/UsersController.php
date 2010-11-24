<?php

namespace app\controllers;

use \app\models\User;

class UsersController extends \lithium\action\Controller {

	public function index() {
		$users = User::all();
		return compact('users');
	}

	public function view() {
		$user = User::first($this->request->id);
		return compact('user');
	}

	public function add() {
		$user = User::create();

		if (($this->request->data) && $user->save($this->request->data)) {
			$this->redirect(array('Users::view', 'args' => array($user->id)));
		}
		return compact('user');
	}

	public function edit() {
		$user = User::find($this->request->id);

		if (!$user) {
			$this->redirect('Users::index');
		}
		if (($this->request->data) && $user->save($this->request->data)) {
			$this->redirect(array('Users::view', 'args' => array($user->id)));
		}
		return compact('user');
	}
}

?>