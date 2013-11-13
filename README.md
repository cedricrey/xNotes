xNotes
======

This is a little project inspired by Google Notebook, definitly closed in 2012

It allows to save notes in XML format on a personal HTTP/PHP server.
Doesn't need a SQL database, only a php 5 server.

Plenty of bug, surely very slow.
Developped for my own need, but if you want it, be my guess free of any licence.

I tryed to make it responsive, i.e usable on mobile and touchable device.

Of course, with no guaranty.

Install
=======

- 1/ Download project
- 2/ Unzip and put 'src' folder content on a server
- 3/ Go on http://yourdomain/folder_where_you_put_xnotes/install.php
- 4/ Put an admin Login and PWD
- 5/ Delete 'install.php' file from the server
- 6/ You can use with your login going on http://yourdomain/folder_where_you_put_xnotes/ (you could create other user with this login)

Use
===

You have to create Notebook (new notebook button) and name it. It will correspond to a XML file on your login folder (user folder)
Then, you can : 
- write notes (main panel)
and/or
- create sections and write notes inside

All your note will be saved on XML files (easy recover and readable).
