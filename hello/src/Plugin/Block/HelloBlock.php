<?php
namespace Drupal\hello\Plugin\Block;

use Drupal\user\Entity\User;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a user details block.
 *
 * @Block(
 *   id = "hello_block",
 *   admin_label = @Translation("Hello!")
 * )
 */
class HelloBlock extends BlockBase {
	/**
	 * {@inheritdoc}
	 */
	public function build() {
		return array(
			'#markup' => $this->_populate_markup(),
		);
	}

	private function _populate_markup() {
		$user = User::load(\Drupal::currentUser()->id());

		if ($user->get('uid')->value < 1) {
			return t('Welcome  Visitor!  The current time is: ' . date('m-d-Y h:i:s', time()));
		} else {
			$user_information = 'User Name: ' . $user->getUsername() . "<br/>";
			$user_information .= 'Language: ' . $user->getPreferredLangcode() . "<br/>"; $user_information .= 'Email: ' . $user->getEmail() . "<br/>";
			$user_information .= 'Timezone: ' . $user->getTimeZone() . "<br/>";
			$user_information .= 'Created: ' . date('m-d-Y h:i:s', $user->getCreatedTime()) . "<br/>"; $user_information .= 'Updated: ' . date('m-d-Y h:i:s', $user->getChangedTime()) . "<br/>"; $user_information .= 'Last Login:' . date('m-d-Y h:i:s', $user->getLastLoginTime()) . "<br/>";
			$roles = NULL;
			foreach($user->getRoles() as $role) {
				$roles .= $role . ",";
			}
			$roles = 'Roles: ' . rtrim($roles, ',');
			$user_information .= $roles;
			return $user_information;
		} // else
	} // function
}
