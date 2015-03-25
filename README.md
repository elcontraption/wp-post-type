# WP Post Type

A WordPress custom post type creator class.

## Installation

Via composer:

```
composer require elcontraption/wp-post-type
```

## Create a post type

Create an 'event' post type:

```php
use \ElContraption\WpPostType\PostType;

$events = new PostType('event');
```

The first argument may either be a string representing the name of the post type (typically singular), or an array defining `singular`, and `plural` names:

```php
$events = new PostType(array(

    // These are required when passing an array for the first parameter
    'singular' => 'Gallery',
    'plural' => 'Galleries',

    // Optional
    'name' => 'gallery'
));
```

## Labels
Labels are assigned based on the singular and plural names. You may override any post type label by passing an array of labels for the second parameter:

```php
$labels = array(

    // Override the 'add_new' label
    'add_new' => 'My Custom Label'
);

$events = new PostType('event', $labels);
```

## Arguments
The default WordPress post type arguments are respected, which means your custom post type will not be public by default [see defaults](https://codex.wordpress.org/Function_Reference/register_post_type#arguments). You may set your own arguments by passing an array for the third parameter:

```php
$args = array(

    // Make this post type public
    'public' => true
);

$events = new PostType('event', $labels, $args);
```
