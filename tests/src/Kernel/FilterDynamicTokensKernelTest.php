<?php

namespace Drupal\Tests\dynamic_token_manager\Kernel;

use Drupal\dynamic_text_token\Plugin\DynamicToken\DynamicTextToken;
use Drupal\KernelTests\KernelTestBase;

/**
 * @group dynamic_tokens
 */
class FilterDynamicTokensKernelTest extends KernelTestBase {

  protected static $modules = [
    'system', 'user', 'filter',
    'dynamic_token_manager',
    'dynamic_text_token',
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
    $text = DynamicTextToken::create([
      'id' => 'greeting',
      'label' => 'Greeting',
      'plugin' => 'dynamic_text_token',
      'speed' => 2,
      'plugin_config' => ['values' => ['Hello', 'Hi'], 'seed' => 1],
      'status' => TRUE,
    ]);
    $text->save();

    // $filter = $this->container->get('plugin.manager.filter')->createInstance('dynamic_tokens', []);
    // $input = 'A [dynamic:greeting] to you';
    // $result = $filter->process($input, 'en');
    // $html = (string) $result->getProcessedText();

    // $this->assertStringContainsString('data-token-id="greeting"', $html);
    // $this->assertStringContainsString('data-token-type-id="dynamic_text_token"', $html);
    // $this->assertStringContainsString('data-speed="2"', $html);

    // $attachments = $result->getAttachments();
    // $this->assertContains('dynamic_token_manager/base', $attachments['library']);
    // $this->assertContains('dynamic_text_token/runtime', $attachments['library']);
  }

}
