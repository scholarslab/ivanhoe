var gulp = require('gulp'),
    jshint = require('gulp-jshint'),
    sass = require('gulp-sass'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename'),
    lr = require('tiny-lr'),
    util = require('gulp-util'),
    livereload = require('gulp-livereload'),
    imagemin = require('gulp-imagemin'),
    coffee = require('gulp-coffee'),
    server = lr();

var paths = {
  js:     './assets/js/',
  images: './assets/images',
  sass:   './assets/scss/**/*',
  css:    './assets/css',
  fonts:  './assets/fonts/**/*'
}

gulp.task('scripts', function() {
  return gulp.src(paths.js + '/*.{js}')
          .pipe(uglify())
          .pipe(concat('main.js'))
          .pipe(gulp.dest(paths.js + '/build/'))
          .pipe(livereload(server));
});

gulp.task('lint', function() {
  gulp.src(paths.js)
    .pipe(jshint('./.jshintrc'))
    .pipe(jshint.reporter('default'));
});

gulp.task('sass', function() {
  gulp.src(paths.sass)
  .pipe(sass({outputStyle: 'compressed'}))
  .pipe(gulp.dest(paths.css))
  .pipe(livereload(server));
});

gulp.task('php', function() {
  var child = spawn('php', ['-l'], {}),
  stdout = '',
  stderr = '';

  child.stdout.on('data', function(data) {
    stdout += data;
    util.log(data);
  });

  child.stderr.setEncoding('utf-8');
  child.stderr.on('data', function(data) {
    stderr += data;
    util.log(util.colors.red(data));
    util.beep();
  });
});

gulp.task('images', function() {
  return gulp.src(paths.images + '/*.{png,jpg,gif}')
    // Pass in options to the task
    .pipe(imagemin(
      {
        optimizationLevel: 5,
        progressive: true
      }
    ))
    .pipe(gulp.dest(paths.images))
    .pipe(livereload(server));
});

gulp.task('watch', function() {

  server.listen(35729, function(err) {
    if(err) return console.log(err);

    gulp.watch('*.php', ['reload']);
    gulp.watch(paths.js + '/*.js', ['lint', 'scripts']);
    gulp.watch('./gulpfile.js', ['lint']);
    gulp.watch(paths.sass, ['sass']);
    gulp.watch(paths.images + '/*', ['images']);

  });

});

gulp.task('default', ['lint', 'scripts', 'sass', 'images', 'watch']);


