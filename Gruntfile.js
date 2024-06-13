module.exports = function (grunt) {

    grunt.loadNpmTasks('grunt-wp-i18n');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-jshint');

    const folders = {
        sourceJs:'inc/resources/js',
        sourceSCSS: 'inc/resources/scss',
        buildJs: 'inc/resources/build/js',
        buildCSS: 'inc/resources/build/css',
        distJs: 'inc/resources/dist/js',
        distCSS: 'inc/resources/dist/css'
    };

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        jshint: {
            files: ['Gruntfile.js', `${folders.sourceJs}/*.js`],
            options: {
                esversion: 6,
                globals: {
                    jQuery: true
                }
            }
        },

        concat: {
            options: {
                separator: ';'
            },
            shared: {
                src: `${folders.sourceJs}/shared.js`,
                dest: `${folders.buildJs}/shared.js`
            },
            admin: {
                src: `${folders.sourceJs}/admin.js`,
                dest: `${folders.buildJs}/admin.js`
            },
            vendor: {
                src: `${folders.sourceJs}/vendor/*.js`,
                dest: `${folders.buildJs}/vendor.js`
            }
        },

        uglify: {
            options: {
                banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
            },
            shared: {
                src: `${folders.buildJs}/shared.js`,
                dest: `${folders.distJs}/shared.min.js`
            },
            admin: {
                src: `${folders.buildJs}/admin.js`,
                dest: `${folders.distJs}/admin.min.js`
            },
            vendor: {
                src: `${folders.buildJs}/vendor.js`,
                dest: `${folders.distJs}/vendor.min.js`
            }
        },

        sass: {
            dist: {
                options: {
                    style: 'expanded',
                },
                files: [
                    {
                        src: `${folders.sourceSCSS}/shared.scss`, 
                        dest: `${folders.buildCSS}/shared.css`
                    },
                    {
                        src: `${folders.sourceSCSS}/admin.scss`, 
                        dest: `${folders.buildCSS}/admin.css`
                    },
                ],
            }
        },

        cssmin: {
            dist: {
                options: {
                    banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
                },
                files: [
                    {
                        src: `${folders.buildCSS}/shared.css`, 
                        dest: `${folders.distCSS}/shared.min.css`
                    },
                    {
                        src: `${folders.buildCSS}/admin.css`, 
                        dest: `${folders.distCSS}/admin.min.css`
                    },
                ]
            }
        },
        
        watch: {
            css: {
                files: [ `${folders.sourceSCSS}/*.scss`, `${folders.sourceSCSS}/*/*.scss`,`${folders.sourceSCSS}/*/*/*.scss`],
                tasks: ['compileCss']
            },
            js: {
                files: [`${folders.sourceJs}/*.js`, `${folders.sourceJs}/*/*.js`],
                tasks: ['compileJs']
            }
        },
        
        makepot: {
            target: {
                options: {
                    domainPath: 'inc/languages',
                    potFilename: 'child-theme.pot',
                    type: 'wp-theme'
                }
            }
        },
        
    });

    grunt.registerTask('compilePot', ['makepot']);
    grunt.registerTask('compileJs', [ 'jshint', 'concat', 'uglify' ]);
    grunt.registerTask('compileCss', [ 'sass', 'cssmin']);
    grunt.registerTask('default', [ 'compileJs', 'compileCss', 'watch']);
    
};