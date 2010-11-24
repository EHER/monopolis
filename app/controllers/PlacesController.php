<?php

namespace app\controllers;

use \app\models\Place;

class PlacesController extends \lithium\action\Controller {

	public function index() {
		$places = Place::all();
		return compact('places');
	}

	public function view() {
		$place = Place::first($this->request->id);
		return compact('place');
	}

	public function add() {
		$place = Place::create();

		if (($this->request->data) && $place->save($this->request->data)) {
			$this->redirect(array('Places::view', 'args' => array($place->id)));
		}
		return compact('place');
	}

	public function edit() {
		$place = Place::find($this->request->id);

		if (!$place) {
			$this->redirect('Places::index');
		}
		if (($this->request->data) && $place->save($this->request->data)) {
			$this->redirect(array('Places::view', 'args' => array($place->id)));
		}
		return compact('place');
	}
}

?>