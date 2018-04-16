# Travelr ![PHP7 ready](https://img.shields.io/badge/PHP7-ready-green.svg) [![Build Status](https://travis-ci.org/K-Phoen/travelr.svg?branch=master)](https://travis-ci.org/K-Phoen/travelr) [![Coverage Status](https://coveralls.io/repos/github/K-Phoen/travelr/badge.svg?branch=master)](https://coveralls.io/github/K-Phoen/travelr?branch=master)

Travelr is another static gallery generator.

As your explored the world during your travels, Travelr will explore your
directories and use the photos in them to create a map of your travels.

[See the demo](http://blog.kevingomez.fr/travelr/)

## Features

* Generation of a static map of your travels
* Generation of static galleries, one for each of your travels
* Configuration through simple YAML files
* Automatic generation of thumbnails
* Can be deployed on [GitHub's gh-pages](https://pages.github.com/)

## Usage

To generate your website, there are a few steps to follow:

1. Download Travelr
2. Configure your albums
3. Generate the website
4. Deploy!

### Configuring the albums

Travelr expects your photos to be organized in a certain way.
Let's say that the `my-photos` folder is the root of your website (meaning that
this folder will be the one exposed by your webserver).

Under this root, we expect a `data` folder which will contain the albums.
Each album is represented as a folder containing both the pictures themselves
and a `config.yaml` file describing the album. This file is pretty straightforward.
Here is an example for the `barcelone_2018` album:

```yaml
title: Barcelone – 2018
cover: 0002.jpg
location: Barcelone, Spain
# you can replace the "location" line by the following two, if you want to use exact coordinates
#latitude: 41.3947688
#longitude: 2.0787284
```

**Note**: If the `config.yaml` file is absent, Travelr will ignore the directory.

Here is a sample folder hierarchy: 

```
./my-photos
└── data
    ├── barcelone_2018
    │   ├── 0001.jpg
    │   ├── 0002.jpg
    │   ├── 0003.jpg
    │   └── config.yaml
    ├── danemark_2015
    │   ├── 0001.jpg
    │   ├── 0002.jpg
    │   ├── 0003.jpg
    │   ├── 0004.jpg
    │   ├── 0005.jpg
    │   ├── 0006.jpg
    │   └── config.yaml
    ├── norvege_2017
    │   ├── norway_2017-08-0001.jpg
    │   ├── norway_2017-08-0002.jpg
    │   ├── norway_2017-08-0003.jpg
    │   ├── norway_2017-08-0004.jpg
    │   └── config.yaml
    └── valence_2018
        ├── config.yaml
        ├── P3240014.jpg
        ├── P3240015.jpg
        ├── P3240019-2.jpg
        ├── P3240019.jpg
        └── P3240044.jpg
```

To inspect your directories and see how Travelr sees them, you can use the following command:

```bash
./travelr directories:list
```

### Generating the website

Once your albums are configured, you can use Travelr to generate the website for your:

```
./travelr build
```

### Deploying

If you are deploying on [GitHub's gh-pages](https://pages.github.com/), you can commit the
changes and push them.

## Authors

* **Kévin Gomez** - *Initial work*
* **Bjorn Sandvik** - *For the original version of the [Leaflet.Photo](https://github.com/turban/Leaflet.Photo) plugin*

See also the list of [contributors](https://github.com/K-Phoen/travelr/graphs/contributors) who participated in this project.

## Contributing

See the [CONTRIBUTING.md](CONTRIBUTING.md) file for details.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
