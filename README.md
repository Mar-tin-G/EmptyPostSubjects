# Empty Post Subjects

[phpBB 3.1](https://www.phpbb.com/) Extension Empty Post Subjects

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

## License

[GPLv2](license.txt)
