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

This custom hook (filter) provides the ability to add a custom message to be sent to your Slack Workspace. For e.g. To send a custom message when a Post draft is created by a user, you could do:

```php
add_filter( 'ping_my_slack_post_message', [ $this, 'custom_message' ] );

public function custom_message( $message, $post ): array {
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
