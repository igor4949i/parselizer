var gulp = require('gulp'),
  options = {
    src: './src',
    dest: './dist'
  };

require('gulp-compress')(gulp, options);