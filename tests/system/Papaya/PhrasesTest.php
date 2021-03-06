<?php
require_once(dirname(__FILE__).'/../../bootstrap.php');

class PapayaPhrasesTest extends PapayaTestCase {

  /**
   * @covers PapayaPhrases
   */
  public function testConstructor() {
    $phrases = new PapayaPhrases(
      $storage = $this->getMock('PapayaPhrasesStorage'),
      $language = $this->getMock('PapayaContentLanguage')
    );
    $this->assertSame($storage, $phrases->getStorage());
    $this->assertSame($language, $phrases->getLanguage());
  }

  /**
   * @covers PapayaPhrases
   */
  public function testGetGroupsAfterSet() {
    $phrases = new PapayaPhrases(
      $this->getMock('PapayaPhrasesStorage'),
      $this->getMock('PapayaContentLanguage')
    );
    $groups = $this
      ->getMockBuilder('PapayaPhrasesGroups')
      ->disableOriginalConstructor()
      ->getMock();
    $phrases->groups = $groups;
    $this->assertSame($groups, $phrases->groups);
  }

  /**
   * @covers PapayaPhrases
   */
  public function testGetGroupsImplicitCreate() {
    $phrases = new PapayaPhrases(
      $this->getMock('PapayaPhrasesStorage'),
      $this->getMock('PapayaContentLanguage')
    );
    $this->assertInstanceOf('PapayaPhrasesGroups', $phrases->groups);
  }

  /**
   * @covers PapayaPhrases::defaultGroup
   */
  public function testDefaultGroupGetAfterSet() {
    $phrases = new PapayaPhrases(
      $this->getMock('PapayaPhrasesStorage'),
      $this->getMock('PapayaContentLanguage')
    );
    $phrases->defaultGroup('TestGroup');
    $this->assertEquals('TestGroup', $phrases->defaultGroup());
  }

  /**
   * @covers PapayaPhrases::defaultGroup
   */
  public function testDefaultGroupImplicitInit() {
    $phrases = new PapayaPhrases(
      $this->getMock('PapayaPhrasesStorage'),
      $this->getMock('PapayaContentLanguage')
    );
    $phrases->papaya($this->mockPapaya()->application());
    $this->assertEquals('test.html', $phrases->defaultGroup());
  }

  /**
   * @covers PapayaPhrases::get
   */
  public function testGetCreatesStringObject() {
    $language = $this->getMock('PapayaContentLanguage');
    $language
      ->expects($this->once())
      ->method('__get')
      ->with('id')
      ->will($this->returnValue(1));
    $storage =$this->getMock('PapayaPhrasesStorage');
    $storage
      ->expects($this->once())
      ->method('get')
      ->with('Some Phrase', 'TestGroup', 1)
      ->will($this->returnValue('Success'));
    $phrases = new PapayaPhrases($storage, $language);
    $phrases->papaya($this->mockPapaya()->application());
    $phrases->defaultGroup('TestGroup');
    $phrase = $phrases->get('Some Phrase');
    $this->assertInstanceOf('PapayaUiString', $phrase);
    $this->assertEquals('Success', (string)$phrase);
  }

  /**
   * @covers PapayaPhrases::getList
   */
  public function testGetListCreatesListObject() {
    $language = $this->getMock('PapayaContentLanguage');
    $language
      ->expects($this->once())
      ->method('__get')
      ->with('id')
      ->will($this->returnValue(1));
    $storage =$this->getMock('PapayaPhrasesStorage');
    $storage
      ->expects($this->once())
      ->method('get')
      ->with('Some Phrase', 'TestGroup', 1)
      ->will($this->returnValue('Success'));
    $phrases = new PapayaPhrases($storage, $language);
    $phrases->papaya($this->mockPapaya()->application());
    $phrases->defaultGroup('TestGroup');
    $list = iterator_to_array($phrases->getList(array('Some Phrase')));
    $this->assertInstanceOf('PapayaUiString', $list[0]);
    $this->assertEquals('Success', (string)$list[0]);
  }

  /**
   * @covers PapayaPhrases::getList
   */
  public function testGetText() {
    $language = $this->getMock('PapayaContentLanguage');
    $language
      ->expects($this->once())
      ->method('__get')
      ->with('id')
      ->will($this->returnValue(1));
    $storage =$this->getMock('PapayaPhrasesStorage');
    $storage
      ->expects($this->once())
      ->method('get')
      ->with('Some Phrase', 'TestGroup', 1)
      ->will($this->returnValue('Success'));
    $phrases = new PapayaPhrases($storage, $language);
    $phrases->papaya($this->mockPapaya()->application());
    $phrases->defaultGroup('TestGroup');
    $this->assertEquals('Success', $phrases->getText('Some Phrase'));
  }
}
