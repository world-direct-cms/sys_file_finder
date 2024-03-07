<!-- omit in toc -->
# TYPO3 extension: "sysfilefinder"

<!-- omit in toc -->
## Content
- [What does it do?](#what-does-it-do)


## What does it do?
When using the extension `fal_securedownloads` the links to files are being changed in the frontend. It is not possible to see in which folder the files reside, as delivering these files now occurs using PHP. This is because `fal_securedownloads` needs to check authorization.

In order to make the editors life simpler, the extension `sysfilefinder` allows him to enter such a frontend link (containing "...index.php?eID=dumpFile...) in a backend module form, which returns the path of the file. So an editor can easily find out where the file is located in the folder tree structure.