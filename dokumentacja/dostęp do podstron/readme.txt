This section deals with setting access for sub-page.

You can require users to log in displaying the login page.
You have to execute only this short code:

$_user->onlyForUsers($_route);

After login, user will be redirected back to particular page.

EXAMPLE USAGE:

Merge folders of this set with your system directories.
As guest, go to http://yoursite.org/example.html