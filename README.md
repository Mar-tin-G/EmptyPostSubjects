# Empty Post Subjects

[phpBB 3.1](https://www.phpbb.com/) Extension Empty Post Subjects

## Beta

This extension is still in beta status. Please do not use this extension on production boards without testing.

## Description

Empties the *Subject* when (quick) replying to a topic. Two settings are added in the ACP:
* Empties the *Subject* field in post editor when replying to a topic.
* Empties the *Subject* field in the quick reply editor when viewing a topic.

Modifies the text of the *Last Post* link on the board index or when viewing a forum to handle empty post subjects, because in this case phpBB by default displays no link. Possible settings in ACP:
* The *Last Post* link displays the subject of the last post (default, this conforms to the phpBB default),
* it displays the title of the topic containing the last post, or
* it displays the post subject if it is not empty and the topic title otherwise.

Also modifies the *search result titles* (when displaying search results as posts) to handle empty post subjects. Possible settings in ACP:
* The *search result titles* display the subject of the post found by the search (default, this conforms to the phpBB default),
* they display the title of the topic containing the found post, or
* they display the post subject if it is not empty and the topic title otherwise.

This extension merges the previous extensions [EmptySubjectsOnReply](https://github.com/Mar-tin-G/EmptySubjectsOnReply) and [CustomLastPost](https://github.com/Mar-tin-G/CustomLastPost) into one extension.

## Changelog

### v1.0.0-beta1

Initial release.

## Installation Instructions

* Download ZIP file from master branch
* Extract the ZIP file locally
* Create the following folders in you phpBB root path (if they do not exist already): `ext/martin/emptypostsubjects/`
* Upload all files from the extracted ZIP file to this folder `ext/martin/emptypostsubjects/` (overwrite any existing files)
* Log into your forum and enter the *Administration Control Panel*
* Go to *Customise* > *Extension Management* > *Manage Extensions*
* Find *Empty Post Subjects* in the list on the right side and click on *Enable*
* Go to *Extensions* > *Empty Post Subjects* > *Settings* to set up the extension

## Feedback

Please feel free to post any feedback to the [Empty Post Subjects](https://www.phpbb.com/community/viewtopic.php?f=456&t=2287691) in phpBB's extension community forum.

## License

[GPLv2](license.txt)
