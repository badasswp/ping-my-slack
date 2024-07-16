# ping-my-slack

Get notifications on Slack when changes are made on your WP website.

![ping-my-slack](https://github.com/badasswp/ping-my-slack/assets/149586343/1da2be61-ab22-42ac-bf9b-63df814c093d)

## Why Ping My Slack?

Ever needed to keep track of what's happening on your website? No need to look further. It does a fantastic job of logging every single activity on your website to your preferred Slack channels. It is simple and fast!

* Get notified when a user creates, modifies or deletes a Post, Page or Image.
* Get notified when a user modifies the Site Settings (General, Tools, Permalinks).
* Get notified when a user creates, or posts a Comment.
* Get notified when a user installs or activates, a Theme or Plugin.
* Get notified when a user creates, modifies or deletes a User.
* Get notified when a user logs in & out.

### Hooks

#### `ping_my_slack_${$post_type}_message`

This custom hook (filter) provides the ability to add a custom `Post` message to be sent to your Slack Workspace. For e.g. To send a custom message when a Post draft is created by a user, you could do:

```php
add_filter( 'ping_my_slack_post_message', [ $this, 'post_message' ] );

public function post_message( $message, $post ): array {
    if ( 'draft' === $post->post_status ) {
        $message = sprintf(
            'Attention: A Post draft with ID: %d was just created, by %s!',
            $post->ID,
            get_user_by( 'id', $post->post_author )->display_name
        );
    }

    return (string) $message;
}
```

**Parameters**

- message _`{string}`_ By default this will be the passed message.
- post _`{WP_Post}`_ By default this will be the WP Post object.
<br/>

#### `ping_my_slack_admin_fields`

This custom hook (filter) provides a way to implement new Admin fields in the plugin's admin page. For e.g. To add a new text field, you could do:

```php
add_filter( 'ping_my_slack_admin_fields', [ $this, 'custom_field' ] );

public function custom_field( $fields ): array {
    return wp_parse_args(
        [
            'telephone' => [
                'type'  => 'text',
                'name'  => 'ping_my_slack_telephone',
                'html'  => esc_html__( 'My Telephone', 'ping-my-slack' ),
                'label' => esc_html__( '(555) 555-1234', 'ping-my-slack' ),
            ]
        ],
        $fields
    );
}
```

**Parameters**

- fields _`{mixed[]}`_ By default this will be an associative array, containing fields options.
<br/>

#### `ping_my_slack_on_ping_error`

This custom hook (action) fires immediately after the Ping call is made and an exception is thrown. For e.g. you can capture errors here like so:

```php
add_action( 'ping_my_slack_on_ping_error', [ $this, 'log_ping_errors' ], 10, 1 );

public function log_ping_errors( $e ): void {
    if ( $e instanceOf \Exception ) {
        wp_insert_post(
            [
                'post_type'   => 'ping_my_slack_logs',
                'post_title'  => sprintf(
                  'Fatal Error: %s'
                  (string) $e->getMessage()
                ),
            ]
        )
    }
}
```

**Parameters**

- e _`{Exception}`_ By default this will be an Exception.
<br/>

#### `ping_my_slack_comment_message`

This custom hook (filter) provides the ability to add a custom `Comment` message to be sent to your Slack Workspace. For e.g. To send a custom message when a Comment is trashed by a user, you could do:

```php
add_filter( 'ping_my_slack_comment_message', [ $this, 'comment_message' ], 10, 2 );

public function comment_message( $message, $comment ): array {
    if ( 'trash' === $comment->comment_approved ) {
        $message = sprintf(
            'Attention: A Comment with ID: %d was just trashed, by %s!',
            $comment->ID,
            wp_get_current_user()->display_name
        );
    }

    return (string) $message;
}
```

**Parameters**

- message _`{string}`_ By default this will be the passed message.
- comment _`{WP_Comment}`_ By default this will be the WP Comment object.
<br/>
