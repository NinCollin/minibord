# minibord
A minimalistic PHP/MySQL bulletin-board thingy mostly worked on at night. Code may be messy due to lack of sleep.

It's a basic, table-based layout, but it works. It supports posting topics, posting replies, and has built-in RSS support.

The v1 branch adds user accounts, a slightly revised theming system, smilies, forums, profiles, and some other things.

![A Classic Theme](https://rainynight.city/linkz/mini1.png)
![An AckBord Theme](https://rainynight.city/linkz/mini2.png)

## installing
It needs some version of PHP (tested on 7, should work on 8) and a MySQL or MariaDB server.

1. Create a new MySQL database
2. Import the minibord.sql file to this database
3. Rename the core.example.php in the corefiles/ folder to core.php, and edit it to you liking (and add your MySQL login info)
4. Make some mini posts

## general update instructions
1. Make a backup of your core.php file, any custom themes you've made, and a backup of your MySQL database.
2. Download the [latest release](https://github.com/NinCollin/minibord/releases) of minibord.
3. Extract all files in this archive over the ones in your minibord folder. If you've made any modifications to any files/images, they will be overritten, so you should back those up first.
4. Rename the core.example.php file in `corefiles/` to core.php and edit this file to match the configuration options of your original core.php
5. The theming system is still a work-in-progress, so if you made any custom themes, you may have to edit them when upgrading. Various samples are contained in the `themes/` folder
6. Follow the additional upgrade instructions on the release page.

## enabling html 
minibord v1.03 has optional support for HTML in posts/bio/layouts, but it requires an external library (htmLawed)

Do keep in mind that allowing HTML (and by extension CSS) does impose some security risks even with a filter (like clickjacking and such,) so enable at your own risk.

1. Download the latest release of [htmLawed](https://www.bioinformatics.org/phplabware/internal_utilities/htmLawed/)
2. Extract the `htmLawed.php` file and place it in the `lib/` folder of minibord
3. Edit your core.php and set `$options['enableHTML'] = true;`
4. Make sure `$options['enablehtmLawed'] = true;` and `$options['htmLawedPath'] = 'lib/htmLawed.php';` (this is the default)
5. (Optional) Edit `$options['htmLawedconfig']` to your liking (the documentation for various configuration options [is provided here](https://www.bioinformatics.org/phplabware/internal_utilities/htmLawed/htmLawed_README.htm))

## issues
For all minibord issues, please head to the **minibord Development Forum** on https://board.rainynight.city 

This site also doubles as a live demo of minibord.

## license
minibord is licensed under the AGPL-3.0 license; this means if you use a modified version of minibord on your website, you must provide your modified copy  of minibord. This is not to say you have to constantly maintain a download of your minibord version every time you make a change, but you must provide it upon request.

## pull requests
At this time, I'm not currently accepting pull requests for minibord as it's more of a personal project of mine. It's free and open source software though, so you are free to fork it.
