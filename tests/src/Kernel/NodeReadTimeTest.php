<?php

namespace Drupal\Tests\testing_example\Kernel;

use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\node\Traits\ContentTypeCreationTrait;
use Drupal\Tests\node\Traits\NodeCreationTrait;

/**
 * @group testing_example
 */
class NodeReadTimeTest extends KernelTestBase {

  use NodeCreationTrait;
  use ContentTypeCreationTrait;

  public static $modules = ['testing_example', 'user', 'system', 'field', 'node', 'text', 'filter'];

  public function setUp() {
    parent::setUp();

    $this->installEntitySchema('user');
    $this->installEntitySchema('node');

    $this->installConfig(['field', 'node', 'filter']);

    $this->createContentType([
      'type' => 'page',
      'name' => 'Basic page',
    ]);
    FieldStorageConfig::create([
      'field_name' => 'field_read_time',
      'entity_type' => 'node',
      'type' => 'decimal',
      'settings' => ['precision' => 8, 'scale' => 4],
    ])->save()
    ;
    FieldConfig::create([
      'field_name' => 'field_read_time',
      'entity_type' => 'node',
      'bundle' => 'page',
    ])->save()
    ;
  }

  public function testNodeReadTime() {
    $node = $this->createNode([
      'type' => 'page',
      'body' => [
        'value' => $this->getRandomGenerator()->sentences(200),
      ],
    ]);

    self::assertEquals(2, $node->get('field_read_time')->value);
  }

  public function testEmptyBody() {
    $node = $this->createNode([
      'type' => 'page',
      'body' => [
        'value' => '',
      ],
    ]);

    self::assertEquals(1, $node->get('field_read_time')->value);
  }

}
