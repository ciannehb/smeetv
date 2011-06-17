Aquarium README FILE

Contents:
  + Introduction to Aquarium
  + Features
  + Requirements
  + Installation
  + Usage
  + About Tom Reitz
  + Change Log


Introduction To Aquarium:
   Aquarium is an open source filter package written in PHP. It filters nearly
   all four-letter words, profanities, curses, and pornographic language from
   any text, while filtering nearly no benign words. Use Aquarium to keep your
   blog, forum, guestbook or other dynamic application clean and suitable for
   all ages. If you haven't already, please visit the Aquarium website at 
      http://aquarium-filter.sourceforge.net/


Aquarium Features:
   Aquarium has a number of nice features:
     + Aquarium is fast: it can process over 350 words per second.
     + Uses an encrypted dictionary of almost 100 objectionable words, so you
       don't need to compile your own bad-words file and keep it plain-text on
       a server.
     + The specially developed algorithm detects sound-alike as well as
       look-alike words to produce almost perfect text filtering.
     + Easy to install and set up
     + Integrates easily into other PHP and HTML web pages.


Aquarium Requirements:
     + Access to a server with PHP installed on it.


Aquarium Installation:
   To install Aquarium, complete the following steps:
     (1) Download Aquarium as a zip (Widows) or as a tarball (*nix) from
            http://www.sourceforge.net/project/showfiles.php?group_id=199790
     (2) Extract the contents of the compressed folder somewhere (such as your
         desktop).
     (3) Upload the entire 'aquarium' folder to your server's root directory.


Aquarium Usage:
     + In any PHP file where you want to use Aquarium, add the PHP code:
         <?php require_once("/aquarium/filter.php"); ?>
     + Then filter the text in a variable $unfiltered (for example), by adding
       the line below to your code:
         <?php $filtered = filter($unfiltered); ?>
     + Now the variable $filtered is an array: 
            element 0 is the original text with any bad words replaced with '*'
            element 1 is the number of seconds it took to filter the text.
            element 2 is the total number of words in the text
            element 3 is the total number of bad words that were filtered.
     + If you use Aquarium on your site, please consider putting up an
       acknowledgment link. Enjoy!


About Tom Reitz:
   I'm an undergraduate student at the University of Wisconsin at Madison. I
   study computer science and math. I got into web design at the end of high
   school, eventually I learned PHP & SQL, both of which I now use for all my
   major web authoring projects.

   I created Aquarium during the summer of 2007 as a personal design project.
   And I decided to release it for free because I like open-source software.
   For more information about me, please visit my website:
      http://www.cs.wisc.edu/~tomas

   If you have any questions, comments, concerns, suggestions, improvements
   or ideas that may help to improve future releases of Aquarium, please
   contact me at <treitz@reitzinternet.com> or on this project's sourceforge.net
   website:
      http://aquarium-filter.sourceforge.net/

   I also keep a list of sites that use Aquarium. If you would like to be
   included on the list, please email me your name and URL.

   Finally, freewill donations are gratefully accepted. You can donate to this
   project through sourceforge.net's site at:
       http://sourceforge.net/donate/index.php?group_id=199790


Change Log:

  - Ver 1.0.0
    This is the first release! I have thoroughly tested it and do not
    anticipate many problems, but something is bound to come up... Enjoy!