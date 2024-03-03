![Parselizer](https://res.cloudinary.com/user123123/image/upload/v1653928724/parselizer-logo-github_zujb0n.png)

# Parselizer

![GitHub](https://img.shields.io/github/license/hasinhayder/hydra?label=License&style=flat-square)

This is a tool for parsing sites.

## Preview

[Cilck to open the project preview on Github Page](https://igor4949i.github.io/parselizer/)

## Features
- Uploading data to Google Sheets
- Sending data to database (MySQL)
- Downloding file from AWS and uploading via FTP
- Generating HTML file and saving data to local Excel
- Downloading images
- Configuration CRON

## Getting Started

It's super easy to get Parselizer up and running.

1. clone the project

```shell
git clone https://github.com/igor4949i/parselizer.git
```

2. create a copy of the desired project and connect phpQuery library
[phpQuery](https://code.google.com/archive/p/phpquery/)

3. configure the required data for parsing

## Known bugs


**Warning:** count(): Parameter must be an array or an object that implements Countable in 

Simple fix.
[Check this comment](https://github.com/guzzle/guzzle/issues/1973#issuecomment-396278571)

```shell
if (count($this->handles) >= $this->maxHandles)
if ( $this->handles != null && count($this->handles) >= $this->maxHandles )
```