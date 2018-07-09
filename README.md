# Laravel Twitter Text Formatter #

A simple wrapper based on [this code](https://github.com/netgloo/php-samples/tree/master/php-twitter-text-formatter), 
providing a service provider, a config file, and a class you can inject into your controllers. For me this is a more friendly
way to include the work in my projects.  

## Installation ##

`composer require joe-pritchard/laravel-twitter-text-formatter`

## Configuration ##

To override the default display templates, publish and modify the config file:

`php artisan vendor:publish --provider=JoePritchard\\LaravelTwitterTextFormatter\\Providers\\ServiceProvider`   

The following config options can be then changed ne changed in `config/twitter-formatter.php`:

  - _show_retweeted_by_ (Control whether or not to show the "retweeted by..." text below retweets that appear in your timeline. Defaults to `true`)
  - _retweeted_by_template_ (The template that should be used to display the "retweeted by..." section. Defaults to `<em> Retweeted by {{user_name}}</em>`)
  - _hashtag_link_template_ (defaults to `<a href="{{hashtag_link}}" rel="nofollow" target="_blank">#{{hashtag_text}}</a>`) 
  - _url_link_template_ (defaults to `<a href="{{url_link}}" rel="nofollow" target="_blank" title="{{url_title}}">{{url_text}}</a>`)
  - _user_mention_link_template_ (defaults to `<a href="{{user_mention_link}}" rel="nofollow" target="_blank" title="{{user_mention_title}}">@{{user_mention_text}}</a>`)
  - _media_link_template_ (defaults to `<a href="{{media_link}}" rel="nofollow" target="_blank" title="{{media_title}}">{{media_text}}</a>`) 

## Usage ##

### In Controllers/Classes ###

Just call `JoePritchard\TwitterTextFormatter\Formatter::format()` on the tweet you'd like to format. 
The format method takes one argument, which is a tweet as returned by the twitter API. For more details on how to retrieve
that, head to the official API docs or take a look at [this article](http://blog.netgloo.com/2015/08/16/php-getting-latest-tweets-and-displaying-them-in-html/), 
which is where I first encountered Andrea Zanelli's work before deciding to roll it into this package.

### In Views ###

In blade files etc it may be more convenient to use the helper method: `{!! twitter_format($tweet) !!}` 