php-epub-cleaner
================

Created by Clément Bourgoin
Contact : http://nokto.net/

This PHP script will :
1. upload an ePub file on the server
2. unzip it in a temporary folder
3. open every .html, .htm or .xhtml files and apply corrections
4. rezip the folder as an ePub
5. download the new ePub 


Installation
------------

Just drop the php-epub-cleaner folder on your PHP webserver.

Customization
-------------

I've created this script to clean HTML generated with http://word2cleanhtml.com/ according to french typographic rules but you can create your own set of rules by modifying the $replacements array.
