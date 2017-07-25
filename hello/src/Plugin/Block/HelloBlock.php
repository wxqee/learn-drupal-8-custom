<?php
namespace Drupal\hello\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a user details block.
 *
 * @Block(
 *   id = "hello_block",
* admin_label = @Translation("Hello!") *)
*/
class HelloBlock extends BlockBase {
	/**
	 * {@inheritdoc}
	 */
	public function build() {
		return array(
			'#markup' => $this->t("Hello World!"),
		);
	}
}
