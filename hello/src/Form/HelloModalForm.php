<?php

namespace Drupal\hello\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax;
use Drupal\Core\Ajax\OpenModalDialogCommand;

class HelloModalForm extends FormBase {
	public function buildForm(array $form, FormStateInterface $form_state) {
		$form['#attached']['library'][] = 'core/drupal.dialog.ajax';

		$form['node_title'] = array(
			'#type' => 'textfield',
			'#title' => $this->t('Node\'s title'),
			'#description' => $this->t('Enter a portion of the title to search for'),
		);

		$form['actions']['#type'] = 'actions';
		$form['actions']['submit'] = array(
			'#type' => 'submit',
			'#value' => $this->t('Search'),
			'#ajax' => array( // here we add Ajax callback where we will process
				'callback' => '::open_modal',  // the data that came from the form and that we will receive as a result in the modal window
			),
		);

		$form['#title'] = 'Search for Node by Title';

		return $form;
	}

	public function getFormId() {
		return 'hello_modal_form';
	}

	public function validateForm(array &$form, FormStateInterface $form_state) {
	}

	public function submitForm(array &$form, FormStateInterface $form_state) {
	}

	public function open_modal(&$form, FormStateInterface $form_state) {
		$node_title = $form_state->getValue('node_title');

		$query = \Drupal::entityQuery('node')->condition('title', $node_title, 'CONTAINS');
		$entity = $query->execute();

		$key = array_keys($entity);
		$id = !empty($key[0]) ? $key[0] : NULL;

		$response = new AjaxResponse();
		$title = 'Node ID';

		if ($id !== NULL) {
			$content = '<div class="test-popup-content"> Node ID is: ' . $id . '</div>';
			$options = array(
				'dialogClass' => 'popup-dialog-class',
				'width' => '300',
				'height' => '300',
			);
			$response->addCommand(new OpenModalDialogCommand($title, $content, $options));
		} else {
			$content = 'Not found record with this title <strong>' . $node_title .'</strong>';
			$options = array(
				'dialogClass' => 'popup-dialog-class',
				'width' => '300',
				'height' => '300',
			);
			$response->addCommand(new OpenModalDialogCommand($title, $content, $options));
		}

		return $response;
	} 
}
