<?php
require_once(dirname(__FILE__).'/../../../../bootstrap.php');

class PapayaResponseContentCsvTest extends PapayaTestCase {


  /**
   * @covers PapayaResponseContentCsv::length
   */
  public function testLength() {
    $content = new PapayaResponseContentCsv(new EmptyIterator(), []);
    $this->assertEquals(-1, $content->length());
  }

  /**
   * @covers PapayaResponseContentFile::output
   */
  public function testOutputUsingNumericColumnIndex() {
    $content = new PapayaResponseContentCsv(
      new ArrayIterator(
        [
          ['1', '2'],
          ['3', '4']
        ]
      ),
      ['one' , 'two']
    );
    ob_start();
    $content->output();
    $this->assertEquals(
      "one,two\r\n1,2\r\n3,4\r\n",
      ob_get_clean()
    );
  }

  /**
   * @covers PapayaResponseContentFile::output
   */
  public function testOutputUsingNamedColumnIndex() {
    $content = new PapayaResponseContentCsv(
      new ArrayIterator(
        [
          ['one' => 'first value', 'two' => 'second value'],
          ['two' => 4, 'one' => 3],
          ['two' => 5],
          ['one' => 6]
        ]
      ),
      ['one' => 'First Column', 'two' => "Second"]
    );
    ob_start();
    $content->output();
    $this->assertEquals(
      "First Column,Second\r\nfirst value,second value\r\n3,4\r\n,5\r\n6,\r\n",
      ob_get_clean()
    );
  }

  /**
   * @covers PapayaResponseContentFile::output
   */
  public function testOutputWithoutColumns() {
    $content = new PapayaResponseContentCsv(
      new ArrayIterator(
        [
          ['1', '2'],
          ['3', '4']
        ]
      )
    );
    ob_start();
    $content->output();
    $this->assertEquals(
      "1,2\r\n3,4\r\n",
      ob_get_clean()
    );
  }

  /**
   * @covers PapayaResponseContentFile::output
   */
  public function testOutputMappingRowAndField() {
    $content = new PapayaResponseContentCsv(
      new ArrayIterator([1, 2])
    );
    $content->callbacks()->onMapRow = function($original) {
      $data = [
        1 => ['one', $original],
        2 => ['two', $original]
      ];
      return $data[$original];
    };
    $content->callbacks()->onMapField = function($original) {
      return strtoupper($original);
    };
    ob_start();
    $content->output();
    $this->assertEquals(
      "ONE,1\r\nTWO,2\r\n",
      ob_get_clean()
    );
  }

}