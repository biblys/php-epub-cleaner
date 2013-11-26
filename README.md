php-epub-cleaner
================

Created by Clément Bourgoin  
Contact : http://nokto.net/contact/

En français : http://nokto.net/php-epub-cleaner/

This PHP script will :  
1. upload an ePub file on the server  
2. unzip it in a temporary folder  
3. open every .html, .htm or .xhtml files and apply corrections  
4. rezip the folder as an ePub  
5. download the new ePub  

Demo
----

A demo can be found here : http://labs.nokto.net/php-epub-cleaner/

Please note that every epub files uploaded for cleaning will be cached on the demo server. This demo should be used for demo purposes only. For production use and commercial files, please install your own version of the application.

Installation
------------

Just drop the php-epub-cleaner folder on your PHP webserver.

Customization
-------------

I've created this script to clean HTML generated with http://word2cleanhtml.com/ according to french typographic rules but you can create your own set of rules by modifying the $replacements array.
