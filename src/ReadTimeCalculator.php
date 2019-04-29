<?php

namespace Drupal\testing_example;

use Drupal\Core\Language\LanguageManagerInterface;

class ReadTimeCalculator {

  protected $languageManager;

  public function __construct(LanguageManagerInterface $languageManager) {
    $this->languageManager = $languageManager;
  }

  public function calculate($content) {
    $readSpeed = $this->getLanguageDependentReadSpeed();
    $wordCount = str_word_count(strip_tags($content));
    $readTime = ceil($wordCount / $readSpeed);
    return $readTime < 1 ? 1 : $readTime;
  }

  private function getLanguageDependentReadSpeed() {
    $readSpeed = 200;
    $id = $this->languageManager->getCurrentLanguage()->getId();

    switch($id) {
      case 'nl':
        $readSpeed = 180;
        break;
      case 'fr':
        $readSpeed = 120;
        break;
    }

    return $readSpeed;
  }

}
