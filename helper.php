<?php
declare(strict_types=1);
/**
 * helper.php
 *
 * @project  laravel-twitter-text-formatter
 * @author   Joe Pritchard <joe@joe-pritchard.uk>
 *
 * Created:  09/07/18 22:22
 *
 */
if (! function_exists('twitter_format')) {
    /**
     * Call the twitter formatter on the provided tweet
     *
     * @param  \stdClass $tweet
     * @return string
     */
    function twitter_format(\stdClass $tweet)
    {
        return JoePritchard\LaravelTwitterTextFormatter\Formatter::format($tweet);
    }
}