<?php
require_once(dirname(__FILE__).'/../../../../bootstrap.php');

class PapayaUiListviewSubitemTest extends PapayaTestCase {

  /**
  * @covers PapayaUiListviewSubitem::getAlign
  * @covers PapayaUiListviewSubitem::setAlign
  */
  public function testGetAlignAfterSetAlign() {
    $subitem = new PapayaUiListviewSubitem_TestProxy();
    $subitem->setAlign(PapayaUiOptionAlign::RIGHT);
    $this->assertEquals(
      PapayaUiOptionAlign::RIGHT, $subitem->getAlign()
    );
  }

  /**
  * @covers PapayaUiListviewSubitem::getAlign
  */
  public function testGetAlignFetchFromColumn() {
    $column = $this->getMock('PapayaUiListviewColumn', array(), array(''));
    $column
      ->expects($this->once())
      ->method('getAlign')
      ->will($this->returnValue(PapayaUiOptionAlign::CENTER));
    $listview = $this->getMock('PapayaUiListview');
    $columns = $this->getMock(
      'PapayaUiListviewColumns', array('has', 'get'), array($listview)
    );
    $columns
      ->expects($this->once())
      ->method('has')
      ->with($this->equalTo(1))
      ->will($this->returnValue(TRUE));
    $columns
      ->expects($this->once())
      ->method('get')
      ->with($this->equalTo(1))
      ->will($this->returnValue($column));
    $listview
      ->expects($this->atLeastOnce())
      ->method('columns')
      ->will($this->returnValue($columns));
    $subitems = $this->getMock(
      'PapayaUiListviewSubitems',
      array(),
      array($this->getMock('PapayaUiListviewItem', array(), array('', '')))
    );
    $subitems
      ->expects($this->atLeastOnce())
      ->method('getListview')
      ->will($this->returnValue($listview));
    $subitem = new PapayaUiListviewSubitem_TestProxy();
    $subitem->collection($subitems);
    $this->assertEquals(
      PapayaUiOptionAlign::CENTER, $subitem->getAlign()
    );
  }

  /**
  * @covers PapayaUiListviewSubitem::getAlign
  */
  public function testGetAlignUseDefaultValue() {
    $listview = $this->getMock('PapayaUiListview');
    $columns = $this->getMock(
      'PapayaUiListviewColumns', array('has', 'get'), array($listview)
    );
    $columns
      ->expects($this->once())
      ->method('has')
      ->with($this->equalTo(1))
      ->will($this->returnValue(FALSE));
    $listview
      ->expects($this->atLeastOnce())
      ->method('columns')
      ->will($this->returnValue($columns));
    $subitems = $this->getMock(
      'PapayaUiListviewSubitems',
      array(),
      array($this->getMock('PapayaUiListviewItem', array(), array('', '')))
    );
    $subitems
      ->expects($this->atLeastOnce())
      ->method('getListview')
      ->will($this->returnValue($listview));
    $subitem = new PapayaUiListviewSubitem_TestProxy();
    $subitem->collection($subitems);
    $this->assertEquals(
      PapayaUiOptionAlign::LEFT, $subitem->getAlign()
    );
  }

  /**
  * @covers PapayaUiListviewSubitem::setActionParameters
  */
  public function testSetActionParameters() {
    $subitem = new PapayaUiListviewSubitem_TestProxy();
    $subitem->setActionParameters(array('foo'));
    $this->assertAttributeEquals(
      array('foo'), '_actionParameters', $subitem
    );
  }
}

class PapayaUiListviewSubitem_TestProxy extends PapayaUiListviewSubitem {

  public function appendTo(PapayaXmlElement $parent) {
  }
}