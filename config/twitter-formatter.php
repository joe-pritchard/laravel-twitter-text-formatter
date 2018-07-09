<?php
declare(strict_types=1);
/**
 * twitter-formatter.php
 *
 * @project  laravel-twitter-text-formatter
 * @author   Joe Pritchard <joe@joe-pritchard.uk>
 *
 * Created:  09/07/18 17:20
 *
 */

return [
    /** Control whether or not to show the "retweeted by..." text below retweets that appear in your timeline  */
    'show_retweeted_by'          => true,
    /** The template that should be used to display the "retweeted by..." section */
    'retweeted_by_template'      => '<em> Retweeted by {{user_name}}</em>',

    /** Addition templates to modify other parts of the displayed tweet */
    'hashtag_link_template'      => '<a href="{{hashtag_link}}" rel="nofollow" target="_blank">'
        . '#{{hashtag_text}}</a>',

    'url_link_template'          => '<a href="{{url_link}}" rel="nofollow" target="_blank" '
        . 'title="{{url_title}}">{{url_text}}</a>',

    'user_mention_link_template' => '<a href="{{user_mention_link}}" rel="nofollow" target="_blank" '
        . 'title="{{user_mention_title}}">@{{user_mention_text}}</a>',

    'media_link_template'        => '<a href="{{media_link}}" rel="nofollow" target="_blank" '
        . 'title="{{media_title}}">{{media_text}}</a>',
];