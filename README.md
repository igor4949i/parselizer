![Parselizer](https://res.cloudinary.com/user123123/image/upload/v1653928724/parselizer-logo-github_zujb0n.png)

# Parselizer

![GitHub](https://img.shields.io/github/license/hasinhayder/hydra?label=License&style=flat-square)

This is a tool for parsing sites.

## Getting Started

It's super easy to get Parselizer up and running.

1. clone the project

```shell
git clone https://github.com/igor4949i/parselizer.git
```

## Known issue

Warning: count(): Parameter must be an array or an object that implements Countable in 

Simple fix.
https://github.com/guzzle/guzzle/issues/1973#issuecomment-396278571

```shell
if (count($this->handles) >= $this->maxHandles)
if ( $this->handles != null && count($this->handles) >= $this->maxHandles )
```
https://github.com/guzzle/guzzle/issues/1973