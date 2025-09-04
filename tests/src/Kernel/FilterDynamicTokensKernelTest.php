<?php

namespace Drupal\Tests\dynamic_token_manager\Kernel;

use Drupal\dynamic_text_token\Entity\DynamicTextTokenInstance;
use Drupal\KernelTests\KernelTestBase;

/**
 * @group dynamic_tokens
 */
class FilterDynamicTokensKernelTest extends KernelTestBase {

  protected static $modules = [
    'system', 'user', 'filter',
    'dynamic_token_manager',
    'dynamic_text_token',
    // 'dynamic_date_difference_token',
  ];

  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('user');
    $this->installConfig(['dynamic_token_manager']);
    $this->installConfig(['dynamic_text_token']);
  }

  public function testSanity() {
    $this->assertEquals(1, 1);
  }

  public function testFilterReplacesTokens() {
    $text = DynamicTextTokenInstance::create([
      'id' => 'greeting',
      'label' => 'Greeting',
      'plugin' => 'dynamic_text_token',
      'speed' => 2,
      'plugin_config' => ['values' => ['Hello', 'Hi'], 'seed' => 1],
      'status' => TRUE,
    ]);
    $text->save();

    // $date = DynamicTokenInstance::create([
    //   'id' => 'launch',
    //   'label' => 'Launch',
    //   'plugin' => 'dynamic_date_difference_token',
    //   'speed' => 1,
    //   'plugin_config' => ['target_datetime' => '2030-01-01T00:00:00Z'],
    //   'status' => TRUE,
    // ]);
    // $date->save();

    $filter = $this->container->get('plugin.manager.filter')->createInstance('dynamic_tokens', []);
    $input = 'A [dynamic:greeting] and B [dynamic:launch]';
    $result = $filter->process($input, 'en');
    $html = (string) $result->getProcessedText();

    $this->assertStringContainsString('data-token-id="greeting"', $html);
    $this->assertStringContainsString('data-token-type-id="dynamic_text_token"', $html);
    $this->assertStringContainsString('data-speed="2"', $html);

    // $this->assertStringContainsString('data-token-id="launch"', $html);
    // $this->assertStringContainsString('data-token-type-id="dynamic_date_difference_token"', $html);
    // $this->assertStringContainsString('data-target-datetime="2030-01-01T00:00:00Z"', $html);

    $attachments = $result->getAttachments();
    $this->assertContains('dynamic_token_manager/base', $attachments['library']);
    $this->assertContains('dynamic_text_token/runtime', $attachments['library']);
    // $this->assertContains('dynamic_date_difference_token/runtime', $attachments['library']);
  }

}
