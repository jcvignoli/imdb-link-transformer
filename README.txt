=== IMDb link transformer ===
Contributors: jcv
Donate link: https://www.paypal.me/jcvignoli & https://en.tipeee.com/lost-highway
Author URI: https://www.jcvignoli.com/blog
Plugin URI: https://www.jcvignoli.com/blog/imdb-link-transformer-wordpress-plugin
Version: 3
Tags:  cinema, film, formatting, imdb, link, movie, plugin, review, tag, widget, moviepilot, taxonomy, popup
Requires at least: 5.7
Tested up to: 5.7.1
Stable tag: 3

IMDb link transformer adds to your movie review many useful information parsed from www.imdb.com. Cache, widget, admin options.

== Description ==

Visit [Official website](https://www.jcvignoli.com/blog/imdb-link-transformer-wordpress-plugin "Official website") to comment, get help, and so on.

**IMDb changed its search method** Please prefer "imdbltid" method in your post/widget rather than "imdblt"

**IMDb link transformer** aims to ease the movies information search process, for both writer and reader. All movies names which are tagged between < !--imdb-->nameMovie< !--/imdb--> are automatically turned into an url. This url can open a new window (a popup) containing many useful data related to the movie itself. IMDb link transformer **transforms all the words you tagged into links to an informative windows**. It means one can view the filmmaker, the casting or the goofs that [IMDb](https://www.imdb.com "Internet movie database") (or similar) website includes with one click; it can show either the director or the movie related data (biography, filmography, miscellaneous related to the director; casting, goofs, AKA titles, crew and many others related to the movie). 

This plugin also add **display buttons** in writing interfaces (both HTML and Visual).

You can also activate the imdb link transformer **widget**, which will display information parsed from IMDb (or similar website) straight on your sidebar (or where is attached your widget). After activating the widget, every time you will add the key "imdb-movie-widget" to the custom field to your message *and* the name of the movie to "value", the information related to selected movie will be displayed on the sidebar. 

In the same way, this plugin can display **many movie's related data inside a post**, when putting a movie name in [imdblt][/imdblt] or a movie ID in [imdbltid][/imdbltid] tags (since the recent IMDB search changes, the latter is prefered). No widget needed, and movie's data can be displayed anywhere inside posts.

**IMDb link transformer** is a great tool to inform yourself and to provide many trivias to your posts! It is very versatile and multi-functional. Blogger can display data in many ways (popup, widget, straight into the post), and can fine-tune data with admin options and css.

== Installation ==

= required =

Php 7 is required.

1. Unzip and put "imdb-link-transformer" folder into your plugin folder (usually wp-content/plugins/)
2. Activate the plugin (using the settings tab from admin board)
3. Configure the plugin (settings admin board). Values should be automatically completed, but check them anyway.
4. Create the cache directories (cache and photo directories). The plugin is preconfigured to work with "/wp-content/cache/imdb" and "/wp-content/cache/imdb/images". Deactivate the cache (advanced - cache management section) otherwise, if you don't want to use the cache. Either you use or you don't, the plugin will work - but be aware that without cache, process will take a long time, each time... and source websites could eventually ban you IP.
5. Give read & write permissions to these two cache directories (see 4).

= basic options =

There is three ways to use IMDb link transformer: popup link creator, widget and inside a post. Each option can be combined with any other, as blogger wants; there is no limitation, feel you free to use all three at once!

1. When writing your post, add either < !--imdb-->movie's name< !--/imdb--> tags to your movie's name (if you disabled visual editor, and that you have HTML interface) or click on imdb link transformer's button after selecting the movie's name. As a result of this, a **link which will open a popup** will be created. The popup contains many data and is extensively browsable.
2. **Widget** can be activated, and used in a way where informations will be displayed inside it. Once the widget is activated, select closely what you want to display on your sidebar: options are available on 'imdb admin settings' tab. Also add "imdb-movie-widget" or "imdb-movie-widget-bymid" to your message's custom field; the value you add in will be the movie that is going to be displayed inside the widget. Check FAQs.
3. The plugin can **show IMDb data inside a post**. When writing your post, put the movie name inside tags [imdblt][/imdblt] (which gives ie [imdblt]Fight club[/imdblt]) or better, using imdb movie's id instead of the name: [imdbltid]0137523[/imdbltid]
4. You may also edit the "/* ---- imdbincluded */" part from imdb.css in order to customize layout according your taste.
5. To activate Highslide (nice code displaying a integrated windows instead of popups) you have to download the library from [IMDBLt website](https://www.jcvignoli.com/blog/wp-content/files/wordpress-imdb-link-transformer-highslide.zip "IMDBLt website"). Once the file downloaded, put the folder "highslide" into the "js" one and check general options in order to activate it. Please note Highslide JS is licensed under a Creative Commons Attribution-NonCommercial 2.5 License, which means you need the author's permission to use Highslide JS on commercial websites.

= Fine tuning: =

1. The files inc/imdb-movie.inc.php, popup.php, imdb_movie.php and imdb_person.php could be modified to match your theme; check also /css/imdb.css if you want to customize default theme.
2. A (front) page can be created to include all you movies' related messages. Have a look there : [personal critics page](https://www.jcvignoli.com/blog/critiques "Lost highway critics page").
3. If your language is not included... translate .po file (inside /lang directory) to yours! And [send it to me](https://www.jcvignoli.com/blog/imdb-link-transformer-wordpress-plugin "IMDb link transformer home"), of course, thus many people would enjoy IMDb in a new language.

= Advanced =

1. If you are **not interested in having links opening popup windows** but look only for informations displayed (both in widget and posts), look for "widget options / Remove popup links?" and switch the option to "yes". There will be no more links opening a popup (both in widget and posts).
2. You may use imdb_call_external() function for externals calls to imdb functions. Have a look to help section (Inside post part)
3. Would you like to display automatically a widget in accordance with your post title? Just turn on "Widget/Inside post Options -> Misc ->Auto widget" option. Especially useful for blogs focusing on movies, where every post is related to cinema.
4. You are an expert in tags and categories. And using movie details as taxonomy (sort of tags) has always been your greatest dream. IMDb link transformer will make you happy ! Use taxonomy; check plugin's help to figure out how to.

= How to update? =

* Remove the old **IMDb link transformer** and install the new one. Or use the automated update from Wordpress.
* If needed, go to **IMDb link transformer** settings, and click on "reset". Every release with new movie details should have a reset on "Widget/Inside post Options" section from Wordpress admin.

== Screenshots ==

1. Popup displayed when an imdb link is selected. In background (on the right), one can see the widget
2. How movie's data is displayed "inside a post"
3. How movie's data is displayed in a "widget"
4. Admin preferences
5. The field and the value to fill up if you want to use the widget ("imdb-movie-widget" & "imdb-movie-widget-bymid" options)
6. New button added for bloggers who prefer Visual writing way
7. New button added for bloggers who prefer HTML writing way
8. Writing code to display movie's data "inside a post"
9. Help section contains many tips and how-to

== Frequently Asked Questions ==

= How to use the plugin? =

The ways to use IMDb link transformer are broadly explained in **How to** page from plugin's Settings (install plugin first, and have a look to "IMDb link transformer help")

= Can I suggest a feature/report a bug regarding the plugin? =

Of course, visit the [IMDb link transformer home](https://www.jcvignoli.com/blog/imdb-link-transformer-wordpress-plugin "IMDb link transformer home"). Does not hesitate to share your comments and desires; the plugin does more or less what I need. Since then, only users can still improve it.

= I don't want to have links to a popup window! =

Look for "Widget/Inside post Options / Misc / Remove all links?" and switch the option to "yes". You won't have links anymore, for both widget and inside a post and as well as internal (popup) and external links.

= I want to keep data forever on my disk/server =

Look for "Cache management / Cache general options / Cache expire" and click on "never" to keep forever data download from IMDb. However, be warned: changes made on IMDb (or similar website) for a downloaded movie won't be refreshed anymore. Still, in this case, if you keep forever data but notwithstanding you want to refresh a specific movie, you could look to the cache options to delete cache files related to the movie you want to refresh. Pay a visit to Cache options.

= Is it possible to add several movies to sidebar/inside my post?  =

Yes, of course it is. Just add as many custom fields you want. 

= When using the widget, I get a "Fatal error: Call to a member function imdbid() on a non-object[...]" instead of movie's details ? =

I'm not sure about the cause. It could happen that you get banned from IMDb website, if you use an old plugin release which doesn't include search cache. But solution is pretty straight: either switch from IMDb to Moviepilot "General options -> Search, imdb part -> Get rid of IMDb" (and look at help section to get you moviepilot API ID), or try to change imdb server you use from "General options -> Search, imdb part -> IMDb address" to another one.

= Known issues =

* When the imdb widget is put under another widget which display a list (ie, "recent posts" plugin), the widget won't display what it should. Actually, it won't display anything. **Workaround:** put the imdb widget one level above the widget calling a list.

* If you activate both "Display highslide popup" option and in [Next-Gen Gallery's](https://wordpress.org/plugins/nextgen-gallery/ "Next-Gen Gallery home") highslide effect option, NGG picture display will be broken. **Workaround:** Do not use "Display highslide popup" option or use another effect option for NGG.

== Contacts ==

Please visit [contact page](https://www.jcvignoli.com/blog/about)

== Credits ==

* Classes come from [imdbphp project](https://github.com/tboothman/imdbphp/ "imdbphp project git homepage"). 
* Popup design thanks to Jeremie Boniface
* Brazilian translation thanks to Murillo Ferrari 
* Spanish translation thanks to Andres Cabrera
* Bulgarian translation thanks to Peter
* Romanian translation thanks to [Web Geek Sciense](https://webhostinggeeks.com "Web Hosting Geeks")
* Croatian translation thanks to [Borisa Djuraskovic](https://www.webhostinghub.com/ "Hub webhosting")
* Ukranian translation thanks to Michael Yunat [https://getvoip.com](https://getvoip.com "Getvoip com")
* Several icons made by [Yusuke Kamiyamane](https://p.yusukekamiyamane.com/ "Yusuke Kamiyamane homepage")

== Changelog == 

Have a look to the [changelog](https://svn.wp-plugins.org/imdb-link-transformer/trunk/changelog.txt "latest changelog") to discover what amazing functions have been lately added.

But broadly speaking:

= 2.1.3 =
Changed the way to use highslide js (on Wordpress request, piece of code not GPL compliant); it is mandatory now to download the library from [IMDBLt website](https://www.jcvignoli.com/blog/wp-content/files/wordpress-imdb-link-transformer-highslide.zip "IMDBLt website") in order to get this damn cool window. Once the file downloaded, put the folder "highslide" into the "js" one and check general options in order to activate it

= 2.0.8 =

* Speed improvement
* Brand new cache management
* Admin interface iconized

= 2.0.2 =

* Taxonomy considerably expanded
* added trailer's movie detail

= 2.0.1 =

* Added taxonomies
