Cimy User Manager

This plug-in can:
1. import data into existing users and also create new ones. It can import into normal WordPress fields or can be used to import also data into Cimy User Extra Fields;
2. export all users' profiles into a CSV file. It can as well export WordPress and Cimy User Extra Fields.

To import users and their data you must create a CSV file where every row represents an user and every string is divided by a comma ',' and delimited by double quote '"'
The first row must contains one or more of these fields (order has no influence):

 % USERID % - specify the existing userid (if valid then the username will be ignored)
 % USERNAME % - specify the existing username or if not existing then create a new one (if specifically requested by the user)
 % EMAIL % - change the e-mail (should be unique and is mandatory for new users)
 % ROLE % - change the role, choose between: [subscriber|contributor|author|editor|administrator]
 % PASSWORD % - change the password, if an empty password is provided then a 12 alpha/numeric length random one will be generated automatically
 % FIRSTNAME % - change the first name
 % LASTNAME % - change the last name
 % NICKNAME % - change the nickname
 % DISPLAYNAME % - add a display name
 % WEBSITE % - change the website
 % AIM % - change the AIM
 % YAHOO % - change the Yahoo IM
 % JABBER % - change the Jabber/Google Talk
 % DESCRIPTION % - change the description
 CIMY_UEF_FIELD_NAME - insert data into the Cimy User Extra Fields with the given name
   * for radio: specify the exact label (case sensitive!) of the checkbox you want to select
   * for checkbox: use 0 or 1 for selecting or deselecting it
   * for dropdown: specify exactly one of the selectable choices (case sensitive!)

starting from the second row every user (new or existing one) should be follow the same order specified in the first row.


BEFORE writing to me read carefully ALL the documentation AND the FAQ. Missing this step means you are wasting my time!
Bugs or suggestions can be mailed at: cimmino.marco@gmail.com

REQUIREMENTS:
PHP >= 5.0.0
WORDPRESS >= 3.1.x
MYSQL >= 4.1.2


INSTALLATION:
- just copy whole cimy-user-manager subdir into your plug-in directory and activate it


EXAMPLE:
In this example there are 3 users, one specified by username (admin) and other two by userid; notice that when you should want to leave it blank just put a comma, like Laura has no userid specified and no description.
If you want to add commas to your fields then you have to choose another fields separator in your CSV file and then specify it in the corresponding option before importing.

"% USERID %","% USERNAME %","% FIRSTNAME %","% DESCRIPTION %","EXTRA_FIELD_01","EXTRA_FIELD_02"
"2","","Marco","Hey this is me!","text into field 01","text into field 02"
"","admin","Luca","The administrator.","extra field!","again!"
"4","","Laura","","","",""

PERSONALIZED EXAMPLE:
The best example is to export your current users' data to a CSV file using this plug-in and modify it following the same scheme.


FAQ:
Q1: I got: "Fatal error: Maximum execution time of 30 seconds exceeded in [..]", why?
Q2: I got: "Connection Interrupted The connection to the server was reset while the page was loading [..]", why?

A: edit your php.ini search and modify these entries:
max_execution_time = 0
max_input_time = 360 ; If the CSV file is very big might be needed to be bigger
file_uploads = On
upload_max_filesize = 20M ; Or whatever is the size of your CSV file to import
post_max_size = 20M ; Or whatever is the size of your CSV file to import


After importing users you can put back original values!


Q: My CSV file doesn't import correctly, outputs bunch of unrelated errors, why?

A: Check if your CSV file has a text delimiter and/or field delimiter and tell the plug-in to use the same, or to not use anything if not present.
Field delimiter is the character(s) that delimits every cell's data:
for example if you have the following row in your CSV file:
"marco","cimmino.marco@gmail.com","Marco Cimmino"
then you must use double quote as text delimiter and comma as fields delimiter, failing to do so will fail the import of such row(s)!


Q: I have more than 1000 users and this plug-in does not export all of them, why?
A: edit your php.ini search and modify this entry:
max_execution_time = 0

After exporting users you can put back the original value!


Q1: I got "Fatal error: Allowed memory size of 8388608 bytes exhausted [..]", why?
Q2: I got blank pages after activating this plug-in, why?

A1: Because your memory limit is too low, to fix it edit your php.ini and search memory_limit key and put at least to 12M
A2: If you do not have access to your php.ini you can try this workaround (might not work)
http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP


Q: I am using WordPress MS and import feature is not available for anyone else that is not a Super Admin, why?

A: This is a feature of WordPress MS and a possible workaround can be found here:
http://thereforei.am/2011/03/15/how-to-allow-administrators-to-edit-users-in-a-wordpress-network/


Q: When feature XYZ will be added?

A: I don't know, remember that this is a 100% free project so answer is "When I have time and/or when someone help me with a donation".


Q: Can I help with a donation?

A: Sure, visit the donation page or contact me via e-mail.


Q: Can I hack this plug-in and hope to see my code in the next release?

A: For sure, this is just happened and can happen again if you write useful new features and good code. Try to see how I maintain the code and try to do the same (or even better of course), I have rules on how I write it, don't want "spaghetti code", I'm Italian and I want spaghetti only on my plate.
There is no guarantee that your patch will reach Cimy User Extra Fields, but feel free to do a fork of this project and distribuite it, this is GPL!


Q1: I have found a bug what can I do?
Q2: Something does not work as expected, why?

A: The first thing is to download the latest version of the plug-in and see if you still have the same issue.
If yes please write me an email or write a comment but give as more details as you can, like:
- Plug-in version
- WordPress version
- MYSQL version
- PHP version
- exact error that is returned (if any)

after describe what you did, what you expected and what instead the plug-in did :)
Then the MOST important thing is: DO NOT DISAPPEAR!
A lot of times I cannot reproduce the problem and I need more details, so if you don't check my answer then 80% of the times bug (if any) will NOT BE FIXED!


CHANGELOG:
v1.3.1 - 17/05/2012
- Fixed website crash when field and/or text delimiters are empty (regressed in v1.3.0) (thanks to Rebel Saffold)
- Fixed another website crash (regressed in v1.3.0) (thanks to Rebel Saffold)

v1.3.0 - 14/05/2012
- Fixed PHP error when trying to import and add users already present (thanks to Rebel Saffold)
- Fixed importing should work much better for users with PHP >= v5.0.4 and delimiters not longer than 1 character
- Fixed importing CSV file produced by the plug-in sometimes failed with non existing ID and username while not true

v1.2.4 - 05/03/2012
- Fixed the exported CSV file to be more compatible with Excel (thanks to Jean-Pierre for the patch)
- Fixed new lines should be stripped out from the exported CSV file (thanks to Jean-Pierre for the patch)

v1.2.3 - 30/01/2012
- Fixed use of an obsolete method's parameter for the localization (thanks to Tom Broad)

v1.2.2 - 22/12/2011
- Fixed password emailing was not working if email address was not specified in the CSV file but present in the DB
- Fixed last field was always dismissed if empty text delimiter was used
- Fixed meaningless error about field '' not existent

v1.2.1 - 14/12/2011
- Fixed password emailing was not working (thanks to Brent)
- Fixed password generator was not producing a random unique password (thanks to Brent)

v1.2.0 - 28/09/2011
- Added WordPress MS support
- Added support for importing custom roles (thanks to Todd Mc Brearty)
- Fixed import should silently disregard the registration date
- Changed capabilities required to use the plug-in (thanks to Chuck Scott)

v1.1.1 - 22/09/2011
- Fixed in some occasions data were not under the right column (introduced in v1.1.0) (thanks to Regan Dawson)

v1.1.0 - 21/09/2011
- Export users is now much faster and uses less memory (thanks to Regan Dawson for profiling all the patches)
- Added possibility to sort exported users by different keys
- Added Display name to import/export
- Added Registered date to export
- Fixed exported cvs file name starts with 'http' when UI button is used
- Fixed plug-in description
- Code cleanup

v1.0.0 - 21/11/2010
- Added possibility to send an email to new users or when existing ones' password is changed
- Fixed export upload path to be inside the blog installation
- Improved security

v0.9.2 - 06/05/2010
- Fixed exported CSV file was not downloadable sometimes (try #1)
- Fixed change of password for existing users was not working (thanks to Wayne Dobson)

v0.9.1 - 20/02/2010
- Added error messages for invalid usernames or e-mail addresses
- Fixed import was not working when JavaScript is disabled (thanks to Jens Wedin)

v0.9.0 - 02/02/2010
- Added text delimiter support
- Added download button when exporting CSV file
- Fixed PHP error when importing from an invalid file
- Changed exporting CSV file name to use local date/time
- Readme file updated

v0.8.3 - 21/10/2009
- Fixed importing csv files produced under Windows were not correctly read
- Fixed in some cases plug-in created users twice

v0.8.2 - 27/08/2009
- Try to set time_limit to infinite to avoid timeout errors with big DBs

v0.8.1 - 11/01/2009
- Changed plug-in link, we have a new home!
- Dropped use of "level_10" role check since is deprecated
- Renamed README file to README_OFFICIAL.txt due to WordPress Plugin Directory rules

v0.8.0 - 06/11/2008
- Added export feature
- Added license file to the package

v0.6.1 - 26/10/2008
- Added WordPress 2.6.x support
- Added fields separator option
- Added Italian translation
- Fixed some non translatable strings

v0.6.0 - 02/09/2008
- First working release