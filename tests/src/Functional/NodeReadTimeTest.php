<?php

namespace Drupal\Tests\testing_example\Functional;

use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\Tests\BrowserTestBase;

/**
 * @group testing_example
 */
class NodeReadTimeTest extends BrowserTestBase {

  public static $modules = ['node', 'testing_example'];

  protected $adminUser;

  public function setUp() {
    parent::setUp();

    $this->adminUser = $this->drupalCreateUser([
      'bypass node access',
      'administer nodes',
    ]);

    $this->drupalCreateContentType([
      'type' => 'page',
      'name' => 'Basic page',
      'display_submitted' => FALSE,
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

  public function testNodReadTimeTest() {
    $this->drupalLogin($this->adminUser);

    $node_title = $this->getRandomGenerator()->name();
    $edit = [
      'title[0][value]' => $node_title,
      'body[0][value]' => $this->getRandomGenerator()->sentences(361),
    ];
    $this->drupalPostForm('node/add/page', $edit, 'Save');

    $node = $this->drupalGetNodeByTitle($node_title);

    self::assertEquals(2, $node->get('field_read_time')->value);
  }

}
