"use strict";

// Load plugins
const prefixer = require("gulp-autoprefixer");
const browsersync = require("browser-sync").create();
//const watch = require('gulp-watch');
const uglify = require('gulp-uglify');
const sourcemaps = require('gulp-sourcemaps');
const sass = require('gulp-sass');
const cleanCSS = require('gulp-clean-css');
const pngquant = require('imagemin-pngquant');
const gulp = require("gulp");
const imagemin = require("gulp-imagemin");
const rimraf = require('rimraf');
const newer = require("gulp-newer");
const rev = require("gulp-rev"); //подсчитывает хеш файла и добавляет его в имя: main-abcdfg.css for long term cashing
const revReplace = require("gulp-rev-replace");//прописывает файл стилей или яваскрипта в html
const rigger = require('gulp-rigger');

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


// BrowserSync
function browserSync(done) {
    browsersync.init(config);
    done();
}

// BrowserSync Reload
function browserSyncReload(done) {
    browsersync.reload();
    done();
}

// Clean assets
function clean(cb) {
    return rimraf(path.clean, cb);
}


// Optimize Images
function images() {
    return gulp
        .src(path.app.img) //Выберем наши картинки
        .pipe(newer(path.dist.img))
        .pipe(imagemin({ //Сожмем их
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()],
            interlaced: true
        }))
        .pipe(gulp.dest(path.dist.img)) //И бросим в build
        .pipe(browsersync.stream());
}


// CSS task
function css() {
    return gulp

        .src(path.app.css) //Выберем наш main.css
        .pipe(sourcemaps.init()) //То же самое что и с js
        .pipe(gulp.dest(path.dist.css)) //И в build
        .pipe(browsersync.stream());

}


function scss() {
    return gulp

        .src(path.app.scss) //Выберем наш main.scss
        .pipe(sourcemaps.init()) //То же самое что и с js
        .pipe(sass()) //Скомпилируем
        .pipe(prefixer()) //Добавим вендорные префиксы
        .pipe(cleanCSS()) //Сожмем
        .pipe(sourcemaps.write())
        //.pipe(rev()) //добавим к имени файла хеш
        .pipe(gulp.dest(path.dist.scss)) //И в build
        //.pipe(rev.manifest('css.json'))// укажем это перемеинование в файле css.json
        //.pipe(gulp.dest('dist/manifest'))// который положем в папку manifest
        .pipe(browsersync.stream());

}


function fonts() {
    return gulp

        .src(path.app.fonts)
        .pipe(gulp.dest(path.dist.fonts))
        .pipe(browsersync.stream());

}

function jscript(done){
         gulp
        .src(path.app.js) //Найдем наш main файл
        .pipe(rigger()) //Прогоним через rigger он импортирует указаные там файлы
        .pipe(sourcemaps.init()) //Инициализируем sourcemap
        .pipe(uglify()) //Сожмем наш js
        .pipe(sourcemaps.write('.')) //Пропишем карты
        .pipe(gulp.dest(path.dist.js)) //Выплюнем готовый файл в build
        .pipe(browsersync.stream());
    done();
}


// Watch files
function watchFiles() {
    gulp.watch([path.watch.scss], scss);
    gulp.watch([path.watch.css], css);
    gulp.watch([path.watch.js], jscript);
    gulp.watch([path.watch.img], images);
    gulp.watch([path.watch.fonts], fonts);

}

// define complex tasks
const build = gulp.series(clean, gulp.parallel(css, scss, images, fonts, jscript));
const watch = gulp.parallel(watchFiles, browserSync);

// export tasks
exports.images = images;
exports.css = css;
exports.jscript = jscript;
exports.scss = scss;
exports.clean = clean;
exports.build = build;
exports.watch = watch;
exports.default = build;

