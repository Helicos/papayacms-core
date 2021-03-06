<?php
require_once(dirname(__FILE__).'/../../../../bootstrap.php');

class PapayaDatabaseResultIteratorTest extends PapayaTestCase {

  /**
  * @covers PapayaDatabaseResultIterator::__construct
  */
  public function testConstructor() {
    $iterator = new PapayaDatabaseResultIterator(
      $databaseResult = $this->getMock('PapayaDatabaseResult')
    );
    $this->assertAttributeSame(
      $databaseResult, '_databaseResult', $iterator
    );
  }

  /**
  * @covers PapayaDatabaseResultIterator::__construct
  */
  public function testConstructorWithAllParameters() {
    $iterator = new PapayaDatabaseResultIterator(
      $this->getMock('PapayaDatabaseResult'),
      PapayaDatabaseResult::FETCH_ORDERED
    );
    $this->assertAttributeSame(
      PapayaDatabaseResult::FETCH_ORDERED, '_fetchMode', $iterator
    );
  }

  /**
  * @covers PapayaDatabaseResultIterator::rewind
  * @covers PapayaDatabaseResultIterator::key
  * @covers PapayaDatabaseResultIterator::current
  * @covers PapayaDatabaseResultIterator::next
  * @covers PapayaDatabaseResultIterator::valid
  */
  public function testIterate() {
    $databaseResult = $this->getMock('PapayaDatabaseResult');
    $databaseResult
      ->expects($this->any())
      ->method('fetchRow')
      ->with(PapayaDatabaseResult::FETCH_ASSOC)
      ->will(
        $this->onConsecutiveCalls(
          array('id' => 21),
          array('id' => 42),
          FALSE
        )
      );
    $databaseResult
      ->expects($this->any())
      ->method('seek')
      ->with(0);
    $iterator = new PapayaDatabaseResultIterator($databaseResult);
    $this->assertEquals(
      array(
        0 => array('id' => 21),
        1 => array('id' => 42)
      ),
      iterator_to_array($iterator)
    );
  }

  /**
  * @covers PapayaDatabaseResultIterator::current
  */
  public function testIterateWithMapping() {
    $mapping = $this->getMock('PapayaDatabaseInterfaceMapping');
    $mapping
      ->expects($this->any())
      ->method('mapFieldsToProperties')
      ->with($this->isType('array'))
      ->will($this->returnCallback(array($this, 'callbackMapFieldsToProperties')));
    $databaseResult = $this->getMock('PapayaDatabaseResult');
    $databaseResult
      ->expects($this->any())
      ->method('fetchRow')
      ->with(PapayaDatabaseResult::FETCH_ASSOC)
      ->will(
        $this->onConsecutiveCalls(
          array('id' => 21),
          FALSE
        )
      );
    $databaseResult
      ->expects($this->any())
      ->method('seek')
      ->with(0);
    $iterator = new PapayaDatabaseResultIterator($databaseResult);
    $iterator->setMapping($mapping);
    $this->assertEquals(
      array(
        0 => array('identifier' => 21)
      ),
      iterator_to_array($iterator)
    );
  }

  public function callbackMapFieldsToProperties($record) {
    return array('identifier' => $record['id']);
  }

  /**
  * @covers PapayaDatabaseResultIterator::rewind
  */
  public function testRewindAfterIteration() {
    $databaseResult = $this->getMock('PapayaDatabaseResult');
    $databaseResult
      ->expects($this->any())
      ->method('fetchRow')
      ->with(PapayaDatabaseResult::FETCH_ASSOC)
      ->will(
        $this->onConsecutiveCalls(
          array('id' => 21),
          array('id' => 42),
          FALSE,
          array('id' => 23),
          FALSE
        )
      );
    $databaseResult
      ->expects($this->any())
      ->method('seek')
      ->with(0);
    $iterator = new PapayaDatabaseResultIterator($databaseResult);
    iterator_to_array($iterator);
    $this->assertEquals(
      array(
        0 => array('id' => 23)
      ),
      iterator_to_array($iterator)
    );
  }

  /**
  * @covers PapayaDatabaseResultIterator::setMapping
  * @covers PapayaDatabaseResultIterator::getMapping
  */
  public function testSetMappingGetAfterSet() {
    $iterator = new PapayaDatabaseResultIterator($this->getMock('PapayaDatabaseResult'));
    $iterator->setMapping($mapping = $this->getMock('PapayaDatabaseInterfaceMapping'));
    $this->assertSame(
      $mapping, $iterator->getMapping()
    );
  }
}
