<?php

namespace Drupal\Tests\lightning_media_twitter\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\language\Entity\ConfigurableLanguage;
use Drupal\media\Entity\Media;

/**
 * @group lightning_media
 * @group lightning_media_twitter
 */
class LibraryInclusionTranslationTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['system', 'user'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installEntitySchema('user');

    $this->container->get('module_installer')->install([
      'content_translation',
      'lightning_media_twitter',
    ]);
    ConfigurableLanguage::createFromLangcode('hu')->save();
  }

  public function test() {
    $media = Media::create([
      'bundle' => 'tweet',
      'name' => $this->randomString(),
      'embed_code' => 'https://twitter.com/PhenaProxima/status/1158431969920901126',
      'field_media_in_library' => TRUE,
    ]);
    $media->addTranslation('hu', [
      'field_media_in_library' => FALSE,
    ]);
    $this->assertSame(SAVED_NEW, $media->save());

    $this->assertTrue($media->field_media_in_library->value);
    $this->assertTrue($media->getTranslation('hu')->field_media_in_library->value);
  }

}
