<?php
namespace Drupal\hello\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class HelloForm extends FormBase {
	public function buildForm(array $form, FormStateInterface $form_state) {
		$form['job_title'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Job Title'),
			'#description' => $this->t('Enter your Job Title. It must be at least 5 characters in
			length.'),
			'#required' => TRUE,
		];
		// CheckBoxes.
		$form['tests_taken'] = [
			'#type' => 'checkboxes',
			'#options' => ['SAT' => t('SAT'), 'ACT' => t('ACT')],
			'#title' => $this->t('What standardized tests did you take?'),
			'#description' => 'If you did not take any of the tests, leave unchecked',
		];
		// Color.
		$form['color'] = [
			'#type' => 'color',
			'#title' => $this->t('Color'),
			'#default_value' => '#ffffff',
			'#description' => 'Pick a color by clicking on the color above',
		];
		// Date.
		$form['expiration'] = [
			'#type' => 'date',
			'#title' => $this->t('Content expiration'),
			'#default_value' => ['year' => 2020, 'month' => 2, 'day' => 15],
			'#description' => 'Enter a date in the form of YYYY MM DD',
		];
		// Email.
		$form['email'] = [
			'#type' => 'email',
			'#title' => $this->t('Email'),
			'#description' => 'Enter your email address',
		];
		// Number.
		$form['quantity'] = [
			'#type' => 'number',
			'#title' => t('Quantity'),
			'#description' => $this->t('Enter a number, any number'),
		];
		// Password.
		$form['password'] = [
			'#type' => 'password',
			'#title' => $this->t('Password'),
			'#description' => 'Enter a password',
		];
		// Password Confirm.
		$form['password_confirm'] = [
			'#type' => 'password_confirm',
			'#title' => $this->t('New Password'),
			'#description' => $this->t('Confirm the password by re-entering'),
		];
		// Range.
		$form['size'] = [
			'#type' => 'range',
			'#title' => t('Size'),
			'#min' => 10,
			'#max' => 100,
			'#description' => $this->t('This is a slider control, pick a value between 10 and 100'),
		];
		// Radios.
		$form['settings']['active'] = [
			'#type' => 'radios',
			'#title' => t('Poll status'),
			'#options' => [0 => $this->t('Closed'), 1 => $this->t('Active')],
			'#description' => $this->t('Select either closed or active'),
		];
		// Search.
		$form['search'] = [
			'#type' => 'search',
			'#title' => $this->t('Search'),
			'#description' => $this->t('Enter a search word or phrase'),
		];
		// Select.
		$form['favorite'] = [
			'#type' => 'select',
			'#title' => $this->t('Favorite color'),
			'#options' => [
				'red' => $this->t('Red'),
				'blue' => $this->t('Blue'),
				'green' => $this->t('Green'),
			],
			'#empty_option' => $this->t('-select-'),
			'#description' => $this->t('Which color is your favorite?'),
		];

		// Tel.
		$form['phone'] = [
			'#type' => 'tel',
			'#title' => $this->t('Phone'),
			'#description' => $this->t('Enter your phone number, beginning with country code,
			e.g., 1 503 555 1212'),
		];

		// TableSelect.
		$options = [
			1 => ['first_name' => 'Indy', 'last_name' => 'Jones'],
			2 => ['first_name' => 'Darth', 'last_name' => 'Vader'],
			3 => ['first_name' => 'Super', 'last_name' => 'Man'],
		];

		$header = [
			'first_name' => t('First Name'),
			'last_name' => t('Last Name'),
		];

		$form['table'] = [
			'#type' => 'tableselect',
			'#title' => $this->t('Users'),
			'#title_display' => 'visible',
			'#header' => $header,
			'#options' => $options,
			'#empty' => t('No users found'),
		];

		// Textarea.
		$form['text'] = [
			'#type' => 'textarea',
			'#title' => $this->t('Text'),
			'#description' => $this->t('Enter a lot of text here'),
		];
		// Textfield.
		$form['subject'] = [
			'#type' => 'textfield',
			'#title' => t('Subject'),
			'#size' => 60,
			'#maxlength' => 128,
			'#description' => $this->t('Just another text field'),
		];

		// Weight.
		$form['weight'] = [
			'#type' => 'weight',
			'#title' => t('Weight'),
			'#delta' => 10,
			'#description' => $this->t('A Drupal weight filter'),
		];
		// Group submit handlers in an actions element with a key of "actions" so
		// that it gets styled correctly, and so that other modules may add actions
		// to the form.
		$form['actions'] = [
			'#type' => 'actions',
		];
		// Add a submit button that handles the submission of the form.
		$form['actions']['submit'] = [
			'#type' => 'submit',
			'#value' => $this->t('Submit'),
			'#description' => $this->t('Submit, #type = submit'),
		];
		return $form;

	}

	public function getFormId() {
		return 'hello_form';
	}

	public function validateForm(array &$form, FormStateInterface $form_state) {
		$job_title = $form_state->getValue('job_title');
		if (strlen($job_title) < 5) {
			// Set an error for the form element with a key of "title".
			$form_state->setErrorByName('job_title', $this->t('Your job title must be at least 5
				characters long.'));
		}
	}

	public function submitForm(array &$form, FormStateInterface $form_state) {
		// Find out what was submitted.
		$values = $form_state->getValues();
		foreach ($values as $key => $value) {
			$label = isset($form[$key]['#title']) ? $form[$key]['#title'] : $key;

			// Many arrays return 0 for unselected values so lets filter that out.
			if (is_array($value)) {
				$value = array_filter($value);
			}

			// Only display for controls that have titles and values.
			if ($value && $label) {
				$display_value = is_array($value) ? preg_replace('/[\n\r\s]+/', ' ', print_r($value, 1)) : $value;
				$message = $this->t('Value for %title: %value', array('%title' => $label, '%value' => $display_value));
				drupal_set_message($message);
			}
		}
	}
}

