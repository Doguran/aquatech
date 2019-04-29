'use strict';

var gulp = require('gulp'),
    mainBowerFiles = require('main-bower-files'),
    watch = require('gulp-watch'),
    prefixer = require('gulp-autoprefixer'),
    uglify = require('gulp-uglify'),
    sourcemaps = require('gulp-sourcemaps'),
    sass = require('gulp-sass'),
    cleanCSS = require('gulp-clean-css'),
    imagemin = require('gulp-imagemin'),
    pngquant = require('imagemin-pngquant'),
    rimraf = require('rimraf'),
    browserSync = require("browser-sync").create(),
    newer = require("gulp-newer"),//сличает даты обновления файлов, если такой же файл уже есть
    rev = require("gulp-rev"),//подсчитывает хеш файла и добавляет его в имя: main-abcdfg.css for long term cashing
    revReplace = require("gulp-rev-replace"),//прописывает файл стилей или яваскрипта в html
    rigger = require('gulp-rigger'),
    reload = browserSync.reload;


var path = {
    vendor: {
        js: 'app/js/',
        css: 'app/css/'
    },
    dist: { //Тут мы укажем куда складывать готовые после сборки файлы
        php: 'dist/application/views/default/',
        html: 'dist/application/views/default/',
        js: 'dist/application/views/default/js/',
        scss: 'dist/application/views/default/css/',
        css: 'dist/application/views/default/css/',
        img: 'dist/application/views/default/img/',
        fonts: 'dist/application/views/default/fonts/'
    },
    app: { //Пути откуда брать исходники
        php: 'app/*.php', //Синтаксис src/*.php говорит gulp что мы хотим взять все файлы с расширением .php
        html: 'app/*.html', //Синтаксис src/*.html говорит gulp что мы хотим взять все файлы с расширением .html
        js: 'app/js/*.js',//В стилях и скриптах нам понадобятся только main файлы
        scss: 'app/css/*.scss',
        css: 'app/css/*.css',
        img: 'app/img/**/*.*', //Синтаксис img/**/*.* означает - взять все файлы всех расширений из папки и из вложенных каталогов
        fonts: 'app/fonts/**/*.*'
    },
    watch: { //Тут мы укажем, за изменением каких файлов мы хотим наблюдать
        php: 'app/**/*.php',
        html: 'app/**/*.html',
        js: 'app/js/**/*.js',
        scss: 'app/css/**/*.scss',
        css: 'app/css/**/*.css',
        img: 'app/img/**/*.*',
        fonts: 'app/fonts/**/*.*'
    },
    clean: './dist/application/views/default'
};

var config = {
    server: {
        baseDir: "./dist/application/views/default"
    },
    tunnel: true,
    host: 'localhost',
    port: 49047,
    logPrefix: "LOG"
};


gulp.task('vendorJs:build', function (done) {
    gulp.src( mainBowerFiles('**/*.js') ) //Выберем файлы по нужному пути
        .pipe(gulp.dest(path.vendor.js)) //Выплюнем готовый файл в app
    done();
});

gulp.task('vendorCss:build', function (done) {
    gulp.src( mainBowerFiles('**/*.css') ) //Выберем файлы по нужному пути
        .pipe(gulp.dest(path.vendor.css)) //И в app
    done();
});

gulp.task('php:build', function (done) {
    gulp.src(path.app.php) //Выберем файлы по нужному пути
        .pipe(gulp.dest(path.dist.php)) //Выплюнем их в папку build
        .pipe(reload({stream: true})); //И перезагрузим наш сервер для обновлений
    done();
});

gulp.task('html:build', function (done) {
    gulp.src(path.app.html) //Выберем файлы по нужному пути
        .pipe(revReplace({
            manifest: gulp.src('dist/manifest/css.json')
        }))
        .pipe(gulp.dest(path.dist.html)) //Выплюнем их в папку build
        .pipe(reload({stream: true})); //И перезагрузим наш сервер для обновлений
    done();
});

function jsc() {
    return gulp.src(path.app.js) //Найдем наш main файл
        .pipe(rigger()) //Прогоним через rigger он импортирует указаные там файлы
        .pipe(sourcemaps.init()) //Инициализируем sourcemap
        .pipe(uglify()) //Сожмем наш js
        .pipe(sourcemaps.write('.')) //Пропишем карты
        .pipe(gulp.dest(path.dist.js)) //Выплюнем готовый файл в build
        .pipe(reload({stream: true})); //И перезагрузим сервер


};


gulp.task('scss:build', function (done) {
    gulp.src(path.app.scss) //Выберем наш main.scss
        .pipe(sourcemaps.init()) //То же самое что и с js
        .pipe(sass()) //Скомпилируем
        .pipe(prefixer()) //Добавим вендорные префиксы
        .pipe(cleanCSS()) //Сожмем
        .pipe(sourcemaps.write())
        //.pipe(rev()) //добавим к имени файла хеш
        .pipe(gulp.dest(path.dist.scss)) //И в build
        //.pipe(rev.manifest('css.json'))// укажем это перемеинование в файле css.json
        //.pipe(gulp.dest('dist/manifest'))// который положем в папку manifest
        .pipe(reload({stream: true}));
    done();

});



gulp.task('css:build', function (done) {
    gulp.src(path.app.css) //Выберем наш main.css
        .pipe(sourcemaps.init()) //То же самое что и с js
        .pipe(gulp.dest(path.dist.css)) //И в build
        .pipe(reload({stream: true}));
    done();
});

gulp.task('image:build', function (done) {
    gulp.src(path.app.img) //Выберем наши картинки
        .pipe(newer(path.dist.img))
        .pipe(imagemin({ //Сожмем их
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()],
            interlaced: true
        }))
        .pipe(gulp.dest(path.dist.img)) //И бросим в build
        .pipe(reload({stream: true}));
    done();
});


gulp.task('fonts:build', function(done) {
    gulp.src(path.app.fonts)
        .pipe(gulp.dest(path.dist.fonts))
    done();
});


gulp.task('build', gulp.parallel(
    // 'vendorCss:build',
    // 'vendorJs:build',
    // 'php:build',
    // 'html:build',
    'jsc',
    'scss:build',
    'css:build',
    'fonts:build',
    'image:build'
));

gulp.task('watch', function(done){
    // watch([path.watch.php], function(event, cb) {
    //     gulp.start('php:build');
    // });
    // watch([path.watch.html], function(event, cb) {
    //     gulp.start('html:build');
    // });
    watch([path.watch.scss], jsc);
    // watch([path.watch.scss], function(event, cb) {
    //     gulp.start('scss:build');
    // });
    // watch([path.watch.css], function(event, cb) {
    //     gulp.start('css:build');
    // });
    // watch([path.watch.js], function(event, cb) {
    //     gulp.start('js:build');
    // });
    // watch([path.watch.img], function(event, cb) {
    //     gulp.start('image:build');
    // });
    // watch([path.watch.fonts], function(event, cb) {
    //     gulp.start('fonts:build');
    // });
    done();
});

gulp.task('webserver', function (done) {
    browserSync.init(config);
    done();
});

gulp.task('clean', function (cb) {
    rimraf(path.clean, cb);
});


// gulp.task('default', ['build', 'webserver', 'watch']);
gulp.task('default', gulp.series('build', 'watch'));

exports.jsc = jsc;