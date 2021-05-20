module.exports = function (grunt) {
    // Project configuration.
    grunt.initConfig({
        modx: grunt.file.readJSON('_build/config.json'),
        banner: '/*!\n' +
            ' * <%= modx.name %> - <%= modx.description %>\n' +
            ' * Version: <%= modx.version %>\n' +
            ' * Build date: <%= grunt.template.today("yyyy-mm-dd") %>\n' +
            ' */\n',
        usebanner: {
            css: {
                options: {
                    position: 'bottom',
                    banner: '<%= banner %>'
                },
                files: {
                    src: [
                        'assets/components/moddevtools/css/mgr/moddevtools.min.css',
                        'assets/components/moddevtools/css/mgr/breadcrumbs.min.css'
                    ]
                }
            },
            js: {
                options: {
                    position: 'top',
                    banner: '<%= banner %>'
                },
                files: {
                    src: [
                        'assets/components/moddevtools/js/mgr/moddevtools.min.js'
                    ]
                }
            }
        },
        uglify: {
            mgr: {
                src: [
                    'source/js/mgr/moddevtools.js',
                    'source/js/mgr/helper/util.js',
                    'source/js/mgr/widgets/search.form.js',
                    'source/js/mgr/widgets/regenerate.form.js',
                    'source/js/mgr/widgets/home.panel.js',
                    'source/js/mgr/widgets/breadcrumbs.panel.js',
                    'source/js/mgr/widgets/elements.panel.js',
                    'source/js/mgr/widgets/chunks.panel.js',
                    'source/js/mgr/widgets/snippets.panel.js',
                    'source/js/mgr/widgets/resources.grid.js',
                    'source/js/mgr/sections/home.js'
                ],
                dest: 'assets/components/moddevtools/js/mgr/moddevtools.min.js'
            }
        },
        sass: {
            options: {
                implementation: require('node-sass'),
                outputStyle: 'expanded',
                sourcemap: false
            },
            mgr: {
                files: {
                    'source/css/mgr/moddevtools.css': 'source/sass/mgr/moddevtools.scss'
                }
            },
            breadcrumbs: {
                files: {
                    'source/css/mgr/breadcrumbs.css': 'source/sass/mgr/breadcrumbs.scss'
                }
            }
        },
        postcss: {
            options: {
                processors: [
                    require('pixrem')(),
                    require('autoprefixer')()
                ]
            },
            mgr: {
                src: [
                    'source/css/mgr/moddevtools.css'
                ]
            },
            breadcrumbs: {
                src: [
                    'source/css/mgr/breadcrumbs.css'
                ]
            }
        },
        cssmin: {
            mgr: {
                src: [
                    'source/css/mgr/moddevtools.css'
                ],
                dest: 'assets/components/moddevtools/css/mgr/moddevtools.min.css'
            },
            breadcrumbs: {
                src: [
                    'source/css/mgr/breadcrumbs.css'
                ],
                dest: 'assets/components/moddevtools/css/mgr/breadcrumbs.min.css'
            }
        },
        watch: {
            js: {
                files: [
                    'source/**/*.js'
                ],
                tasks: ['uglify', 'usebanner:js']
            },
            css: {
                files: [
                    'source/**/*.scss',
                    'custom_modules/**/*.scss'
                ],
                tasks: ['sass', 'postcss', 'cssmin', 'usebanner:css']
            },
            config: {
                files: [
                    '_build/config.json'
                ],
                tasks: ['default']
            }
        },
        bump: {
            copyright: {
                files: [{
                    src: 'core/components/moddevtools/model/moddevtools/moddevtools.class.php',
                    dest: 'core/components/moddevtools/model/moddevtools/moddevtools.class.php'
                }],
                options: {
                    replacements: [{
                        pattern: /Copyright \d{4}(-\d{4})? by/g,
                        replacement: 'Copyright ' + (new Date().getFullYear() > 2018 ? '2018-' : '') + new Date().getFullYear() + ' by'
                    }]
                }
            },
            version: {
                files: [{
                    src: 'core/components/moddevtools/model/moddevtools/moddevtools.class.php',
                    dest: 'core/components/moddevtools/model/moddevtools/moddevtools.class.php'
                }],
                options: {
                    replacements: [{
                        pattern: /version = '\d+.\d+.\d+[-a-z0-9]*'/ig,
                        replacement: 'version = \'' + '<%= modx.version %>' + '\''
                    }]
                }
            },
            homepanel: {
                files: [{
                    src: 'source/js/mgr/widgets/home.panel.js',
                    dest: 'source/js/mgr/widgets/home.panel.js'
                }],
                options: {
                    replacements: [{
                        pattern: /© \d{4}(-\d{4})? by/g,
                        replacement: '© ' + (new Date().getFullYear() > 2018 ? '2018-' : '') + new Date().getFullYear() + ' by'
                    }]
                }
            }
        }
    });

    //load the packages
    grunt.loadNpmTasks('@lodder/grunt-postcss');
    grunt.loadNpmTasks('grunt-banner');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-string-replace');
    grunt.renameTask('string-replace', 'bump');

    //register the task
    grunt.registerTask('default', ['bump', 'uglify', 'sass', 'postcss', 'cssmin', 'usebanner']);
};
