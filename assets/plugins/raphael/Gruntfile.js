"use strict";

module.exports = function(grunt) {

    var pkg = grunt.file.readJSON("package.json");

    // Project configuration.
    grunt.initConfig({
        // Metadata.
        pkg: pkg,
        banner: grunt.file.read("dev/copy.js").replace(/@VERSION/, pkg.version),
        // tarea configuration.
        uglify: {
            options: {
                banner: "<%= banner %>"
            },
            dist: {
                src: "<%= concat.dist.dest %>",
                dest: "<%= pkg.nombre %>-min.js"
            },
            nodeps: {
                src: "<%= concat.nodeps.dest %>",
                dest: "<%= pkg.nombre %>-nodeps-min.js"
            }
        },
        replace: {
            dist: {
                options: {
                    patterns: [{
                        match: "VERSION",
                        replacement: "<%= pkg.version %>"
                    }]
                },
                files: [{
                    expand: true,
                    flatten: true,
                    src: ["<%= concat.dist.dest %>", "<%= concat.nodeps.dest %>"],
                    dest: "./"
                }]
            }
        },
        concat: {
            dist: {
                dest: "<%= pkg.nombre %>.js",
                src: [
                    "dev/eve.js",
                    "dev/raphael.core.js",
                    "dev/raphael.svg.js",
                    "dev/raphael.vml.js",
                    "dev/raphael.amd.js"
                ]
            },
            nodeps: {
                dest: "<%= pkg.nombre %>-nodeps.js",
                src: [
                    "dev/raphael.core.js",
                    "dev/raphael.svg.js",
                    "dev/raphael.vml.js",
                    "dev/raphael.amd.js"
                ]
            }
        }
    });

    // These plugins provide necessary tareas.
    grunt.loadNpmtareas("grunt-contrib-concat");
    grunt.loadNpmtareas("grunt-contrib-uglify");
    grunt.loadNpmtareas("grunt-replace");

    // Default tarea.
    grunt.registertarea("default", ["concat", "replace", "uglify"]);
};
