# phpBB Empty Post Subjects extension

This is the repository for the development of the [phpBB 3.2](https://www.phpbb.com/) "Empty Post Subjects" Extension.

[![Build Status](https://travis-ci.org/Mar-tin-G/EmptyPostSubjects.svg?branch=master)](https://travis-ci.org/Mar-tin-G/EmptyPostSubjects)

## Description

Empties the *Post Subject* when (quick) replying to a topic. Two settings are added in the ACP:
* Empties the *Subject* field in post editor when replying to a topic.
* Empties the *Subject* field in the quick reply editor when viewing a topic.

Modifies the text of the *Last Post* link on the board index or when viewing a forum to handle empty post subjects, because in this case phpBB by default displays no link. Possible settings in ACP:
* The *Last Post* link displays the subject of the last post (default, this conforms to the phpBB default),
* it displays the title of the topic containing the last post, or
* it displays the post subject if it is not empty and the topic title otherwise.

Note: the following option must be enabled to display *Last Post* links: ACP > *General* > *Board Configuration* > *Board Features* > *Display subject of last added post on forum list*

Also modifies the *search result titles* (when displaying search results as posts) to handle empty post subjects. Possible settings in ACP:
* The *search result titles* display the subject of the post found by the search (default, this conforms to the phpBB default),
* they display the title of the topic containing the found post, or
* they display the post subject if it is not empty and the topic title otherwise.

This extension merges the previous extensions [EmptySubjectsOnReply](https://www.phpbb.com/community/viewtopic.php?t=2284976) and [CustomLastPost](https://www.phpbb.com/community/viewtopic.php?t=2285101) into one extension.

## Installation

* Download the latest validated release.
* Extract the downloaded release and copy it to the `ext` directory of your phpBB board
  * this should result in a `ext/martin/emptypostsubjects/` directory
* Log into your forum and enter the *Administration Control Panel*.
* Go to *Customise* > *Extension Management* > *Manage Extensions*.
* Find *Empty Post Subjects* in the list on the right side and click on *Enable*.
* Go to *Extensions* > *Empty Post Subjects* > *Settings* to set up the extension.

## Removal

* Log into your forum and enter the *Administration Control Panel*.
* Go to *Customise* > *Extension Management* > *Manage Extensions*.
* Find *Empty Post Subjects* in the list on the right side and click on *Disable*.
* To permanently uninstall, click on *Delete data* and delete the `ext/martin/emptypostsubjects/` directory afterwards.

## Feedback

Please feel free to post any feedback to the [Empty Post Subjects topic](https://www.phpbb.com/community/viewtopic.php?t=2287691) in phpBB's extension community forum.

For bug reports, please open an issue on [the extension's GitHub page](https://github.com/Mar-tin-G/EmptyPostSubjects).

## License

[GPLv2](license.txt)
