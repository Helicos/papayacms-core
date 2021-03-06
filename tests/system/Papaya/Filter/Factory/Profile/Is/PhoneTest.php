<?php
require_once(dirname(__FILE__).'/../../../../../../bootstrap.php');

class PapayaFilterFactoryProfileIsPhoneTest extends PapayaTestCase {

  /**
   * @covers PapayaFilterFactoryProfileIsPhone::getFilter
   */
  public function testGetFilterExpectTrue() {
    $profile = new PapayaFilterFactoryProfileIsPhone();
    $this->assertInstanceOf('PapayaFilterPhone', $profile->getFilter());
  }
}
