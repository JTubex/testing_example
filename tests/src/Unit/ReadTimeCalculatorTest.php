<?php

namespace Drupal\Tests\testing_example\Unit;

use Drupal\Core\Language\Language;
use Drupal\testing_example\ReadTimeCalculator;
use Drupal\Tests\UnitTestCase;

/**
 * @group testing_example
 */
class ReadTimeCalculatorTest extends UnitTestCase {

  protected $languageManager;

  protected $readTimeCalculator;

  public function setUp() {
    parent::setUp();
    $this->languageManager = $this->createMock('Drupal\Core\Language\LanguageManagerInterface');
    $this->readTimeCalculator = new ReadTimeCalculator($this->languageManager);
  }

  public function testReadTime() {
    $this->languageManager->method('getCurrentLanguage')
      ->will($this->onConsecutiveCalls(
        new Language(['id' => 'nl']),
        new Language(['id' => 'nl']),
        new Language(['id' => 'nl']),
        new Language(['id' => 'fr']),
        new Language(['id' => 'es'])
      ))
    ;

    self::assertEquals(4, $this->readTimeCalculator->calculate($this->getRandomGenerator()->sentences(671)));
    self::assertEquals(1, $this->readTimeCalculator->calculate($this->getRandomGenerator()->sentences(1)));
    self::assertEquals(1, $this->readTimeCalculator->calculate(''));
    self::assertEquals(76, $this->readTimeCalculator->calculate($this->getRandomGenerator()->sentences(9021)));
    self::assertEquals(2, $this->readTimeCalculator->calculate($this->getRandomGenerator()->sentences(360)));
  }

  public function tearDown() {
    parent::tearDown();
    unset($this->readTimeCalculator);
  }
}
