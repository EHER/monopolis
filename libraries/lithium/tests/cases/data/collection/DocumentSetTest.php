<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace lithium\tests\cases\data\collection;

use stdClass;
use lithium\data\Connections;
use lithium\data\entity\Document;
use lithium\data\collection\DocumentSet;
use lithium\data\collection\DocumentArray;
use lithium\tests\mocks\data\model\MockDocumentPost;
use lithium\tests\mocks\data\model\MockDocumentSource;
use lithium\tests\mocks\data\source\mongo_db\MockResult;
use lithium\tests\mocks\data\model\MockDocumentMultipleKey;

/**
 * DocumentSet tests
 */
class DocumentSetTest extends \lithium\test\Unit {

	protected $_model = 'lithium\tests\mocks\data\model\MockDocumentPost';

	protected $_preserved = array();

	public function setUp() {
		if (empty($this->_preserved)) {
			foreach (Connections::get() as $conn) {
				$this->_preserved[$conn] = Connections::get($conn, array('config' => true));
			}
		}
		Connections::reset();

		Connections::add('mongo', array('type' => 'MongoDb'));
		Connections::add('couch', array('type' => 'http', 'adapter' => 'CouchDb'));

		MockDocumentPost::config(array('connection' => 'mongo'));
		MockDocumentMultipleKey::config(array('connection' => 'couch'));
	}

	public function tearDown() {
		foreach ($this->_preserved as $name => $config) {
			Connections::add($name, $config);
		}
	}

	public function testPopulateResourceClose() {
		$resource = new MockResult();
		$doc = new DocumentSet(array('model' => $this->_model, 'result' => $resource));
		$model = $this->_model;

		$result = $doc->rewind();
		$this->assertTrue($result instanceof Document);
		$this->assertTrue(is_object($result['_id']));

		$expected = array('_id' => '4c8f86167675abfabdbf0300', 'title' => 'bar');
		$this->assertEqual($expected, $result->data());

		$expected = array('_id' => '5c8f86167675abfabdbf0301', 'title' => 'foo');
		$this->assertEqual($expected, $doc->next()->data());

		$expected = array('_id' => '6c8f86167675abfabdbf0302', 'title' => 'dib');
		$result = $doc->next()->data();
		$this->assertEqual($expected, $result);

		$this->assertNull($doc->next());
	}
}

?>