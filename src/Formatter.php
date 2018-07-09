<?php
declare(strict_types=1);

/**
 * Formatter.php
 *
 * @project  laravel-twitter-text-formatter
 * @author   Andrea Zanelli <andreazanelli5@gmail.com>
 *
 * Created:  09/07/18 17:17
 *
 */

namespace JoePritchard\LaravelTwitterTextFormatter;


/**
 * Class Formatter
 * @package JoePritchard\LaravelTwitterTextFormatter
 */
class Formatter
{
    /**
     * Return the tweet text formatted with the tweet's entities.
     *
     * @param $tweet (Object)
     * @param $configs (Array)
     * @return (String)
     */
    public static function format($tweet): string
    {
        // Is retweeted?
        if (isset($tweet->retweeted_status)) {
            $user_name = $tweet->user->name;
            $retweeted_by = '';

            // If show retweeted by, then prepare the "retweeted by" text
            if (config('twitter-formatter.show_retweeted_by')){
                $retweeted_by = config('twitter-formatter.retweeted_by_template');
                $retweeted_by = str_replace(
                    '{{user_name}}',
                    $user_name,
                    $retweeted_by
                );
            }

            // Return the parsed re-tweet
            $res = self::parse_tweet_text($tweet->retweeted_status);
            return $res . $retweeted_by;
        }

        // Return the parsed tweet
        return self::parse_tweet_text($tweet);
    }

    /**
     * Return the formatted text taking entities from the $tweet object.
     *
     * Credits: this function is a modified version of the one from Jacob
     * Emerick's Blog (http://goo.gl/lhu8Ix)
     *
     * @param $tweet
     *
     * @return string
     */
    private static function parse_tweet_text($tweet): string
    {
        // Collects the set of entities
        $entity_holder = [];

        // Hashtags
        if (isset($tweet->entities->hashtags)) {
            $template = config('twitter-formatter.hashtag_link_template');
            foreach ($tweet->entities->hashtags as $hashtag) {
                // Link: https://twitter.com/hashtag/{{1}}?src=hash
                $hashtag_link = 'https://twitter.com/hashtag/' . strtolower($hashtag->text) . '?src=hash';

                $replace = str_replace(
                    ['{{hashtag_link}}', '{{hashtag_text}}'],
                    [$hashtag_link, $hashtag->text],
                    $template
                );
                self::add_entity($entity_holder, $hashtag, $replace);
            }
        }

        // Urls
        if (isset($tweet->entities->urls)) {
            $template = config('twitter-formatter.url_link_template');
            foreach ($tweet->entities->urls as $url) {
                $replace = str_replace(
                    ['{{url_link}}', '{{url_title}}', '{{url_text}}'],
                    [$url->url, $url->expanded_url, $url->display_url],
                    $template
                );

                self::add_entity($entity_holder, $url, $replace);
            }
        }

        // User mentions
        if (isset($tweet->entities->user_mentions)) {
            $template = config('twitter-formatter.user_mention_link_template');
            foreach ($tweet->entities->user_mentions as $user_mention) {
                // Link: https://twitter.com/{{1}}
                $user_mention_link = 'https://twitter.com/' . strtolower($user_mention->screen_name);

                $replace = str_replace(
                    ['{{user_mention_link}}', '{{user_mention_title}}', '{{user_mention_text}}'],
                    [$user_mention_link, $user_mention->name, $user_mention->screen_name],
                    $template
                );
                self::add_entity($entity_holder, $user_mention, $replace);
            }
        }

        // Media
        if (isset($tweet->entities->media)) {
            $template = config('twitter-formatter.media_link_template');
            foreach ($tweet->entities->media as $media) {
                $replace = str_replace(
                    ['{{media_link}}', '{{media_title}}', '{{media_text}}'],
                    [$media->url, $media->expanded_url, $media->display_url],
                    $template
                );
                self::add_entity($entity_holder, $media, $replace);
            }
        }

        // Sort the entities in reverse order by their starting index
        krsort($entity_holder);

        // Replace the tweet's text with the entities
        $text = $tweet->text;

        foreach ($entity_holder as $entity) {
            $text = self::mb_substr_replace(
                $text,
                $entity->replace,
                $entity->start,
                $entity->length,
                'utf-8'
            );
        }

        return $text;
    }

    /**
     * Add an entity to the entity_holder.
     *
     * @param $entity_holder
     * @param $tweet_entity
     * @param $replace
     */
    private static function add_entity(array &$entity_holder, \stdClass $tweet_entity, string $replace): void
    {
        $entity = new \stdClass();
        $entity->start = $tweet_entity->indices[0];
        $entity->end = $tweet_entity->indices[1];
        $entity->length = $entity->end - $entity->start;
        $entity->replace = $replace;
        $entity_holder[$entity->start] = $entity;
    }

    /**
     * String replacement supporting UTF-8 encoding.
     *
     * @param string      $string
     * @param string      $replacement
     * @param int         $start
     * @param int|null    $length
     * @param string|null $encoding
     *
     * @return string
     */
    private static function mb_substr_replace(string $string, string $replacement, int $start, int $length = null, string $encoding = null): string
    {
        $strlen = mb_strlen($string, $encoding);
        $first_piece = mb_substr($string, 0, $start, $encoding) . $replacement;
        $second_piece = '';
        if (isset($length)) {
            $second_piece = mb_substr($string, $start + $length, $strlen, $encoding);
        }
        return $first_piece . $second_piece;
    }
}