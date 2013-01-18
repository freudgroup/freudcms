<?php
/**
 * Custom code for Social Passport application
 *
 * @category Drupal
 * @package SocialPassport
 * @subpackage Module
 * @version $Id$
 * @author Pablo Viquez <pablo.viquez@possible.com>
 */
$wasDisplayed = false;

/**
 * Implements hook_node_info(). Define module-provided node types.
 *
 * We use hook_node_info() to define our node content type.
 * @return array
 */
function socialpassport_node_info() {
    $ctype = array(
        'social_video' => array(
            'name' => 'Social Passport Video',
            'base' => 'socialpassport',
            'description' => 'Social Passport Custom Content Type for Videos',
            'help' => '',
            'has_title' => true,
            'title_label' => 'Social Passport Video Field',
            'locked' => true,
        ),
    );

    return $ctype;
}

/**
 * Implements hook_node_type_insert().
 *
 * Much like hook_node_insert() lets us know that a node is being
 * inserted into the database, hook_node_type_insert() lets us know
 * that a new content type has been inserted.
 *
 * Since Drupal will at some point insert our new content type,
 * this gives us a chance to add the fields we want.
 *
 * It is called for all inserts to the content type database, so
 * we have to make sure we're only modifying the type we're
 * concerned with.
 *
 * @param string $content_type
 * @return void
 */
function socialpassport_node_type_insert($content_type) {
    // If it's not the socialpassport node, return
    if ($content_type->type != 'socialpassport') {
        return;
    }

    // Add default body field to a node type.
    //$typeBody = node_add_body_field($content_type, t('Social Passport'));

    // Create the new fields
    $fields = _socialpassport_installed_fields();
    foreach($fields as $fieldName => $fieldData) {
        field_create_field($fieldData);
    }

    // Create all new instances for the fields
    $instances = _socialpassport_installed_instances();
    foreach($instances as $instance) {
        $instance['entity_type'] = 'node';
        //$instance['bundle'] = 'socialpassport';
        field_create_instance($instance);
    }
}

/**
 * Implements hook_field_formatter_view()
 *
 * @see hook_field_formatter_view()
 *
 * @param string $object_type The type of $object.
 * @param stdClass $object The object being displayed.
 * @param array $field The field structure.
 * @param array $instance The field instance.
 * @param string $langcode The language associated with $items.
 * @param array $items Array of values for this field.
 * @param array $display The display settings to use, as found in the 'display'
 *                       entry of instance definitions.
 * @return array
 */
function socialpassport_field_formatter_view($object_type, $object, $field, $instance, $langcode, $items, $display) {
    global $wasDisplayed;

    // Display the videos
    // DEBUG - START
    error_log(__CLASS__ . '::' . __FUNCTION__ . '(' . __LINE__ . ')');
    error_log(print_r($object->socialpassport_videourl, true));
    error_log(print_r($object->socialpassport_localvideourl, true));
    // DEBUG - END

    // DEBUG - START
    error_log(__CLASS__ . '::' . __FUNCTION__ . '(' . __LINE__ . ')');
    error_log(print_r("Was displayed? " . ($wasDisplayed) ? "true" : 'false', true));
    // DEBUG - END

    $element = array();
    $elementData = array(
        'socialpassport_videourl' => $object->socialpassport_videourl,
        'socialpassport_localvideourl' => $object->socialpassport_localvideourl,
    );

    $element[0]['#type'] = 'markup';
    $element[0]['#markup'] = theme('socialpassport_videodisplay', array('data' => $elementData));
    //$element[0]['#markup'] = theme('socialpassport_videourl', array('data' => $elementData));

    $wasDisplayed = true;
    return $element;
}

/**
 * Returns the list of the new fields
 *
 * @see socialpassport_node_type_insert
 * @return array
 */
function _socialpassport_installed_fields() {
    $fields = array(
        'socialpassport_videourl' => array(
            'field_name' => 'socialpassport_videourl',
            'carfinality' => 2,
            'type' => 'text',
            'settings' => array(
            ),
        ),
        'socialpassport_localvideourl' => array(
            'field_name' => 'socialpassport_localvideourl',
            'carfinality' => 1,
            'type' => 'managed_file',
            'settings' => array(
            ),
        ),
    );

    return $fields;
}

/**
 * Define the field instances for our content type.
 *
 * The instance lets Drupal know which widget to use to allow the user to enter
 * data and how to react in different view modes.
 *
 * @return array An associative array specifying the instances we wish to add
 *               to our new node type.
 */
function _socialpassport_installed_instances() {
    $instances = array(
        'socialpassport_videourl' => array(
            'field_name' => 'socialpassport_videourl',
            'label' => 'The YouTube video URL',
            'widget' => array(
                'type' => 'text_textfield',
            ),
        ),
        'socialpassport_localvideourl' => array(
            'field_name' => 'socialpassport_localvideourl',
            'label' => 'Local MP4 video URL',
            'widget' => array(
                'type' => 'managed_file',
            ),
        ),
    );

    return $instances;
}

/**
 * Custom theme function.
 *
 * Formats the node-specific information overriding the node presentation
 *
 * @param string $data
 * @return string
 */
function theme_socialpassport_videodisplay($data) {
    $output = 'Hello world!';

    return $output;
}

/**
 * Implements hook_theme().
 *
 * This lets us tell Drupal about our theme functions and their arguments.
 */
function socialpassport_videodisplay_theme($existing, $type, $theme, $path) {
    return array(
        'socialpassport_videourl' => array(
            'variables' => array('data' => NULL),
        ),
    );
}




