"use strict";

module.exports = function(grunt) {

  // load all grunt tasks
  require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

  var paths = {
    js: './assets/js',
    images: './assets/images',
    sass:   './assets/scss/**/*',
    css:    './assets/css'
  };

  grunt.initConfig({

    // watch for changes and trigger compass, jshint, uglify and livereload
    watch: {
      options: {
        livereload: true
      },
      compass: {
        files: [paths.sass],
        tasks: ['compass']
      },
      js: {
        files: '<%= jshint.all %>',
        tasks: ['jshint', 'uglify']
      },
      livereload: {
        files: ['*.html', paths.css + '/*.css', '*.php', paths.images + '/**/*.{png,jpg,jpeg,gif,webp,svg}']
      }
    },

    // compass and scss
    compass: {
      dist: {
        options: {
          config: 'config.rb',
          force: true
        }
      }
    },

    // javascript linting with jshint
    jshint: {
      options: {
        jshintrc: '.jshintrc',
        "force": true
      },
      all: [
        'Gruntfile.js',
        paths.js + '/source/**/*.js'
      ]
    },

    // uglify to concat, minify, and make source maps
     uglify: {
      dist: {
        options: {
          sourceMap: 'assets/js/map/source-map.js'
        },
        files: {
          'assets/js/ivanhoe.min.js': [
            'assets/js/source/plugins.js',
            'assets/js/vendor/**/*.js',
            '!assets/js/vendor/modernizr*.js'
          ],
          'assets/js/main.min.js': [
            'assets/js/source/main.js'
          ]
        }
      }
    },
    // image optimization
    imagemin: {
      dist: {
        options: {
          optimizationLevel: 7,
          progressive: true
        },
        files: [{
          expand: true,
          cwd: paths.images,
          src: '**/*',
          dest: paths.images
        }]
      }
    },

    //phplint
    phplint: {
      good: ['./*.php']
    },

    // phpcs
    phpcs: {
      application: {
        dir: '*.php'
      },
      options: {
        bin: 'vendor/bin/phpcs',
        standard: 'PEAR'
      }
    },

    // deploy via rsync
    deploy: {
      staging: {
        src: "./",
        dest: "~/path/to/theme",
        host: "user@host.com",
        recursive: true,
        syncDest: true,
        exclude: ['.git*', 'node_modules', '.sass-cache', 'Gruntfile.js', 'package.json', '.DS_Store', 'README.md', 'config.rb', '.jshintrc']
      },
      production: {
        src: "./",
        dest: "~/path/to/theme",
        host: "user@host.com",
        recursive: true,
        syncDest: true,
        exclude: '<%= rsync.staging.exclude %>'
      }
    }

  });

  // rename tasks
  grunt.renameTask('rsync', 'deploy');

  // register task
  grunt.registerTask('default', ['watch']);

};
