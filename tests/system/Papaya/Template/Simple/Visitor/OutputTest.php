<?php
require_once(dirname(__FILE__).'/../../../../../bootstrap.php');

class PapayaTemplateSimpleVisitorOutputTest extends PapayaTestCase {

  /**
   * @covers PapayaTemplateSimpleVisitorOutput::clear
   */
  public function testClear() {
    $visitor = new PapayaTemplateSimpleVisitorOutput();
    $nodes = new PapayaTemplateSimpleAstNodes(
      array(
        new PapayaTemplateSimpleAstNodeOutput('Hello')
      )
    );
    $nodes->accept($visitor);
    $visitor->clear();
    $this->assertEquals('', (string)$visitor);

  }

  /**
   * @covers PapayaTemplateSimpleVisitorOutput::visitNodeOutput
   * @covers PapayaTemplateSimpleVisitorOutput::__toString
   */
  public function testVisitWithOutput() {
    $visitor = new PapayaTemplateSimpleVisitorOutput();
    $nodes = new PapayaTemplateSimpleAstNodes(
      array(
        new PapayaTemplateSimpleAstNodeOutput('Hello'),
        new PapayaTemplateSimpleAstNodeOutput(' '),
        new PapayaTemplateSimpleAstNodeOutput('World!')
      )
    );
    $nodes->accept($visitor);
    $this->assertEquals('Hello World!', (string)$visitor);
  }

  /**
   * @covers PapayaTemplateSimpleVisitorOutput::visitNodeValue
   * @covers PapayaTemplateSimpleVisitorOutput::__toString
   */
  public function testVisitWithValue() {
    $callbacks = $this
      ->getMockBuilder('PapayaTemplateSimpleVisitorOutputCallbacks')
      ->disableOriginalConstructor()
      ->setMethods(array('onGetValue'))
      ->getMock();
    $callbacks
      ->expects($this->once())
      ->method('onGetValue')
      ->with('$FOO')
      ->will($this->returnValue('Universe'));
    $visitor = new PapayaTemplateSimpleVisitorOutput();
    $visitor->callbacks($callbacks);

    $nodes = new PapayaTemplateSimpleAstNodes(
      array(
        new PapayaTemplateSimpleAstNodeOutput('Hello'),
        new PapayaTemplateSimpleAstNodeOutput(' '),
        new PapayaTemplateSimpleAstNodeValue('$FOO', 'World'),
        new PapayaTemplateSimpleAstNodeOutput('!')
      )
    );
    $nodes->accept($visitor);
    $this->assertEquals('Hello Universe!', (string)$visitor);
  }

  /**
   * @covers PapayaTemplateSimpleVisitorOutput::visitNodeValue
   * @covers PapayaTemplateSimpleVisitorOutput::__toString
   */
  public function testVisitWithValueMappingReturnsNull() {
    $callbacks = $this
      ->getMockBuilder('PapayaTemplateSimpleVisitorOutputCallbacks')
      ->disableOriginalConstructor()
      ->setMethods(array('onGetValue'))
      ->getMock();
    $callbacks
      ->expects($this->once())
      ->method('onGetValue')
      ->with('$FOO')
      ->will($this->returnValue(NULL));
    $visitor = new PapayaTemplateSimpleVisitorOutput();
    $visitor->callbacks($callbacks);

    $nodes = new PapayaTemplateSimpleAstNodes(
      array(
        new PapayaTemplateSimpleAstNodeOutput('Hello'),
        new PapayaTemplateSimpleAstNodeOutput(' '),
        new PapayaTemplateSimpleAstNodeValue('$FOO', 'World'),
        new PapayaTemplateSimpleAstNodeOutput('!')
      )
    );
    $nodes->accept($visitor);
    $this->assertEquals('Hello World!', (string)$visitor);
  }

  /**
   * @covers PapayaTemplateSimpleVisitorOutput::callbacks
   */
  public function testCallbacksGetAfterSet() {
    $callbacks = $this->getMock('PapayaTemplateSimpleVisitorOutputCallbacks');
    $visitor = new PapayaTemplateSimpleVisitorOutput();
    $visitor->callbacks($callbacks);
    $this->assertSame($callbacks, $visitor->callbacks());
  }

  /**
   * @covers PapayaTemplateSimpleVisitorOutput::callbacks
   */
  public function testCallbacksGetImplicitCreate() {
    $visitor = new PapayaTemplateSimpleVisitorOutput();
    $this->assertInstanceOf('PapayaTemplateSimpleVisitorOutputCallbacks', $visitor->callbacks());
  }
}