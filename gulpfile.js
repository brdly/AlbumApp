var gulp = require('gulp');
var sass = require('gulp-sass');
var prefix = require('gulp-autoprefixer');
var browserSync = require('browser-sync').create();
var sourcemaps = require('gulp-sourcemaps');
var concat = require('gulp-concat');
var php = require('gulp-connect-php');

var paths = {
    styles: {
        src: './assets/sass',
        files: './assets/sass/**/*.scss',
        dest: './public/css'
    },

    fonts: {
        src: './assets/fonts',
        files: [
            './node_modules/@fortawesome/fontawesome-pro/webfonts/*'
        ],
        dest: './public/fonts'
    },

    javascripts: {
        src: './assets/scripts',
        files: [
            './node_modules/jquery/dist/jquery.min.js',
            './node_modules/bootstrap/dist/js/bootstrap.js',
            './assets/scripts/**/*.js'
        ],
        dest: './public/js'
    },

    html: {
        src: './public',
        files: [
            './templates/**/*.html.twig',
            './src/**/*.php',
        ]
    }
};

var displayError = function(error) {
    var errorString = '[' + error.plugin + ']';
    errorString += ' ' + error.message.replace("\n",'');
    if(error.fileName)
        errorString += ' in ' + error.fileName;

    if(error.lineNumber)
        errorString += ' on line ' + error.lineNumber;
    console.error(errorString);
};

gulp.task('sass', (done) => {
    gulp.src(paths.styles.files)
        .pipe(sass({
            outputStyle: 'compressed',
            sourceComments: 'map',
            includePaths : [paths.styles.src]
        }))
        .on('error', function(err){
            displayError(err);
        })
        .pipe(prefix(
            'last 2 version'
        ))
        .pipe(gulp.dest(paths.styles.dest))
        .pipe(browserSync.reload({ stream:true }));
    done();
});

gulp.task('fonts', (done) => {
    gulp.src(paths.fonts.files)
        .pipe(gulp.dest(paths.fonts.dest))
        .pipe(browserSync.reload({ stream:true }));
    done();
});

gulp.task('js', (done) => {
    gulp.src(paths.javascripts.files)
        .pipe(sourcemaps.init())
        .pipe(concat('all.js'))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(paths.javascripts.dest))
        .pipe(browserSync.reload({ stream:true }));
    done();
});

gulp.task('html', (done) => {
    gulp.src(paths.html.files)
        .pipe(browserSync.reload());
    done();
});

gulp.task('php', (done) => {
    php.server({base:'./public/', port:8010, keepalive:true});
    done();
});

gulp.task('browserSync', gulp.parallel('php', (done) => {
    browserSync.init({
        proxy: 'localhost:8010',
        files: ['./public/**/*.js', './public/**/*.css', './**/*.php'],
    });
    done();
}));

gulp.task('default', gulp.series(gulp.parallel('sass', 'fonts', 'js'), 'browserSync', (done) => {
    gulp.watch(paths.styles.files, gulp.series('sass'));
    gulp.watch(paths.javascripts.files, gulp.series('js'));
    gulp.watch(paths.fonts.files, gulp.series('fonts'));
    gulp.watch(paths.html.files, gulp.series('html'));
    done();
}));
