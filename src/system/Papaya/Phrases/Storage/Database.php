<?php

class PapayaPhrasesStorageDatabase
  extends PapayaObject
  implements PapayaPhrasesStorage {

  private $_cache = array();
  private $_loadedGroups = array();
  private $_errors = array();

  /**
   * @var PapayaContentPhrases
   */
  private $_phrases = NULL;

  /**
   * @var PapayaContentPhraseMessages
   */
  private $_messages;

  public function get($identifier, $group, $languageId) {
    (string)$identifier = $identifier;
    $key = strtolower($identifier);
    if (!isset($this->_loadedGroups[$languageId][$group])) {
      $this->loadGroup($group, $languageId);
    }
    if (!isset($this->_cache[$languageId][$key])) {
      $phrase = $this->loadPhrase($key, $languageId);
      if (!$phrase->isLoaded()) {
        $this->log(
          sprintf('Phrase not found: %s.', $identifier),
          $identifier,
          NULL,
          $group,
          $languageId
        );
        $phrase['translation'] = $identifier;
      } elseif (empty($phrase['translation'])) {
        $this->log(
          sprintf('Phrase translation not found: %s.', $identifier),
          $identifier,
          $phrase['id'],
          $group,
          $languageId
        );
        $phrase['translation'] = $identifier;
      }
      $this->_cache[$languageId][$key] = iterator_to_array($phrase);
    }
    if (!empty($this->_cache[$languageId][$key])) {
      if (!isset($this->_cache[$languageId][$key]['GROUPS'][$group])) {
        if (isset($phrase) && $phrase->isLoaded()) {
          $phrase->addToGroup($group);
        }
      }
      return (string)$this->_cache[$languageId][$key]['translation'];
    }
    return (string)$identifier;
  }

  private function loadGroup($group, $languageId) {
    $group = strtolower(trim($group));
    $phrases = $this->phrases();
    $loaded = $phrases->load(
      array(
        'group' => $group,
        'language_id' => $languageId
      )
    );
    if ($loaded) {
      $this->_loadedGroups[$languageId][$group] = TRUE;
      foreach ($phrases as $phraseId => $phrase) {
        $key = $phrase['identifier'];
        if (!isset($this->_cache[$key])) {
          $this->_cache[$languageId][$key] = $phrase;
        }
        $this->_cache[$languageId][$key]['GROUPS'][$group] = TRUE;
      }
    }
  }

  public function loadPhrase($key, $languageId) {
    return $this->phrases()->getItem(
      array(
        'identifier' => $key,
        'language_id' => $languageId
      )
    );
  }

  public function phrases(PapayaContentPhrases $phrases = NULL) {
    if (isset($phrases)) {
      $this->_phrases = $phrases;
    } elseif (NULL === $this->_phrases) {
      $this->_phrases = new PapayaContentPhrases();
      $this->_phrases->papaya($this->papaya());
    }
    return $this->_phrases;
  }

  /**
   * @param PapayaContentPhraseMessages $messages
   * @return PapayaContentPhraseMessages
   */
  public function messages(PapayaContentPhraseMessages $messages = NULL) {
    if (isset($messages)) {
      $this->_messages = $messages;
    } elseif (NULL === $this->_messages) {
      $this->_messages = new PapayaContentPhraseMessages();
      $this->_messages->papaya($this->papaya());
    }
    return $this->_messages;
  }

  public function log($message, $phrase, $phraseId, $group, $languageId) {
    $phrase = (string)$phrase;
    if (
      !empty($phrase) &&
      !isset($this->_errors[$phrase]) &&
      $this->papaya()->options['PAPAYA_DEBUG_LANGUAGE_PHRASES']
    ) {
      $this->_errors[$phrase] = TRUE;
      $this->messages()->add(
        array(
          'text' => (string)$message,
          'phrase' => (string)$phrase,
          'phrase_id' => (int)$phraseId,
          'group' => (string)$group,
          'language_id' => (int)$languageId,
          'created' => time()
        )
      );
    }
  }

}