<?php
require_once(dirname(__FILE__).'/../../../../../../../../bootstrap.php');

class PapayaUiDialogFieldFactoryExceptionInvalidProfileTest extends PapayaTestCase {

  /**
   * @covers PapayaUiDialogFieldFactoryExceptionInvalidProfile::__construct
   */
  public function testConstructor() {
    $exception = new PapayaUiDialogFieldFactoryExceptionInvalidProfile('SampleProfileName');
    $this->assertEquals(
      'Invalid field factory profile name "SampleProfileName".',
      $exception->getMessage()
    );
  }
}