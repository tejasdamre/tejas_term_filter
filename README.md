**Tejas Term Filter Module**
**Overview:**
The Tejas Term Filter module provides a custom form element called term_filter that can be used in custom forms within Drupal. This element is designed to allow users to select taxonomy terms with options for inclusion or exclusion.

**Features:**
Radio Field: Options include "None", "Include", and "Exclude", with the default set to "None".
Select Field: Allows the selection of a term from a specified taxonomy vocabulary. Terms are sorted alphabetically.
Dynamic Behavior: The select field for term selection is only visible if the radio field is set to "Include" or "Exclude".

**How to Use:**
To use the term_filter form element in your custom form, include the following in your form definition:

$form['term_filter'] = [
    '#type' => 'term_filter',
    '#vid' => 'my_vocabulary', // Vocabulary ID
    '#title' => $this->t('Filter by term'),
    '#description' => $this->t('Optionally select a term and whether it should be included or excluded in the result set'),
];


**Setting Default Values:**
To prepopulate the form element with default values, pass the #default_value property as follows:

$form['term_filter'] = [
    '#type' => 'term_filter',
    '#vid' => 'my_vocabulary', // Vocabulary ID
    '#default_value' => [
        'filter_options' => 'include', // Other options: 'none', 'exclude'
        'filter_terms' => '1', // Term ID from the specified vocabulary
    ],
    '#title' => $this->t('Filter by term'),
    '#description' => $this->t('Optionally select a term and whether it should be included or excluded in the result set'),
];

Default Value Structure:
filter_options: Accepts 'none', 'include', or 'exclude'.
filter_terms: The Term ID of the default taxonomy term to be preselected.
