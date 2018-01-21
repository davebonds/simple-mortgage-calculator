module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    sass: {
      options: {
        includePaths: ['node_modules/material-design-lite/src', 'public/css']
      },
      dist: {
        options: {
          outputStyle: 'compressed'
        },
        files: {
          'public/css/simple-mortgage-calculator-public.css': 'public/css/simple-mortgage-calculator-public.scss'
        }
      }
    },

    // wpcss: {
    //     target: {
    //         options: {
    //             commentSpacing: true, // Whether to clean up newlines around comments between CSS rules.
    //             config: 'default',    // Which CSSComb config to use for sorting properties.
    //         },
    //         files: [
    //           {
    //             src: 'style.min.css', dest: 'style.min.css'
    //           },
    //         ],
    //     }
    // },

    makepot: {
        target: {
            options: {
                cwd: '',                          // Directory of files to internationalize.
                domainPath: '/languages',         // Where to save the POT file.
                potComments: '',                  // The copyright at the beginning of the POT file.
                potFilename: '',                  // Name of the POT file.
                potHeaders: {
                    poedit: true,                 // Includes common Poedit headers.
                    'x-poedit-keywordslist': true // Include a list of all possible gettext functions.
                },                                // Headers to add to the generated POT file.
                processPot: null,                 // A callback function for manipulating the POT file.
                type: 'wp-plugin',                // Type of project (wp-plugin or wp-theme).
                updateTimestamp: true             // Whether the POT-Creation-Date should be updated without other changes.
            }
        }
    },

    watch: {
      grunt: { files: ['Gruntfile.js'] },

      sass: {
        files: 'public/css/*.scss',
        tasks: ['sass']
      },
      // wpcss: {
      //   files: 'scss/**/*.scss',
      //   tasks: ['wpcss']
      // }
    }

  });

  grunt.loadNpmTasks('grunt-sass');
  //grunt.loadNpmTasks('grunt-wp-css');
  grunt.loadNpmTasks('grunt-wp-i18n');
  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.registerTask('build', ['sass', 'makepot']);
  grunt.registerTask('default', ['build','watch']);
};