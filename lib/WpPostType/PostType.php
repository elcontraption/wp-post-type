<?php namespace WpPostType;

use Exception;

class PostType {

    /**
     * Post type registration name
     *
     * Lowercase, no spaces
     *
     * @var string
     */
    protected $name;

    /**
     * Post type name: singular
     *
     * @var string
     */
    protected $singular;

    /**
     * Post type name: plural
     *
     * @var string
     */
    protected $plural;

    /**
     * Post type labels
     *
     * @var array
     */
    protected $labels;

    /**
     * Post type arguments, not including labels
     *
     * @var array
     */
    protected $args;

    /**
     * Register a new post type
     *
     * @param mixed $name   The post type name or array of names
     * @param array  $labels An array of labels for this post type
     * @param array  $args   The remaining arguments
     */
    public function __construct($names, $labels = array(), $args = array())
    {
        // Assign registration, singular, and plural names
        $this->assignNames($names);

        // Default labels
        $this->labels = wp_parse_args($labels, $this->defaultLabels());

        // Default args
        $this->args = wp_parse_args($args, $this->defaultArgs());

        // Register the post type
        if ( ! post_type_exists($this->name)) {
            add_action('init', array($this, 'register'));
        }
    }

    /**
     * Register the post type
     */
    public function register()
    {
        $postType = register_post_type($this->name, $this->args);
    }

    /**
     * Default labels
     */
    protected function defaultLabels()
    {
        return array(
            'name'                  => _x($this->plural, 'taxonomy general name'),
            'singular_name'         => _x($this->singular, 'taxonomy singular name'),
            'menu_name'             => __($this->plural),
            'name_admin_bar'        => __($this->singular),
            'all_items'             => __('All ' . $this->plural),
            'add_new'               => __('Add New ' . $this->singular),
            'add_new_item'          => __('Add New ' . $this->singular),
            'edit_item'             => __('Edit ' . $this->singular),
            'new_item'              => __('New ' . $this->singular),
            'view_item'             => __('View ' . $this->singular),
            'search_items'          => __('Search ' . $this->plural),
            'not_found'             => __('No ' . $this->plural . ' found'),
            'not_found_in_trash'    => __('No ' . $this->plural . ' found in Trash'),
            'parent_item_colon'     => __('Parent' . $this->plural)
        );
    }

    /**
     * Default args
     */
    protected function defaultArgs()
    {
        return array(
            'labels'                => $this->labels,
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
        );
    }

    /**
     * Assign registration, singular, and plural names
     *
     * @param mixed $names Array of names
     */
    protected function assignNames($names)
    {
        // If string, assign name
        if (is_string($names)) {
            $this->name = sanitize_title($names);

            // Default to human-friendly version of $name
            $this->singular = $this->getFriendlyName($names);

            // Default to basic pluralization of $singular
            $this->plural = $this->singular . 's';

            return;
        }

        // If passing an array, both singluar and plural *must* be set
        if ( ! (isset($names['singular']) && isset($names['plural']))) {
            throw new Exception("Both 'singular' and 'plural' must be set when passing an array to new PostType().");
        }

        $this->singular = $names['singular'];
        $this->plural = $names['plural'];

        // If 'name' is not set, assign from singular
        if ( ! isset($names['name'])) {
            $this->name = sanitize_title($this->singular);
            return;
        }

        $this->name = $names['name'];
    }

    protected function getFriendlyName($name)
    {
        return ucwords(strtolower(str_replace('-', ' ', str_replace('_', ' ', $name))));
    }

}
