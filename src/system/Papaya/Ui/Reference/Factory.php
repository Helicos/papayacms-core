<?php

class PapayaUiReferenceFactory extends PapayaObject {

  private $_patterns = array(
    'page' => '(
      ^
      (?:(?P<category_id>\\d+)\\.)? # category id
      (?P<page_id>\\d+) # page id
      (?:\\.(?P<language>[a-zA-Z]{2,4}))? # language identifier
      (?:\\.(?P<mode>[a-z]+))? # output mode
      (?:\\?(?P<query>[^?#]*))? # query string
      (?:\\#(?P<fragment>[^?#]*))? # query string
      $
     )x',
    'absolute_url' => '(
      ^
      (?P<url>\w+://[^\\r\\n]*)
      $
     )x',
    'relative_url' => '(
      ^
      (?P<path>[^?#]+)
      (?P<query>[^#]+)?
      (?P<fragment>[^\\s]+)?
      $
     )x'
  );

  public function byString($string) {
    foreach ($this->_patterns as $type => $pattern) {
      if (preg_match($pattern, $string, $matches)) {
        switch ($type) {
        case 'page' :
          $reference = $this->createPageReference($matches);
          break;
        case 'absolute_url' :
          $reference = new PapayaUiReference(new PapayaUrl($string));
          break;
        case 'relative_url' :
        default :
          $reference = new PapayaUiReference(
            clone $this->papaya()->request->getUrl()
          );
          $reference->setRelative($string);
        }
        return $reference;
      }
    }
    return new PapayaUiReference(
      clone $this->papaya()->request->getUrl()
    );
  }

  private function createPageReference($options) {
    $reference = new PapayaUiReferencePage();
    $reference->papaya($this->papaya());
    if (!empty($options['page_id'])) {
      $reference->setPageId($options['page_id']);
    }
    if (!empty($options['language'])) {
      $reference->setPageLanguage($options['language']);
    }
    if (!empty($options['category_id'])) {
      $reference->setCategoryId($options['category_id']);
    }
    if (!empty($options['mode'])) {
      $reference->setOutputMode($options['mode']);
    }
    return $reference;
  }
}