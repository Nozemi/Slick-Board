# Slick Board - Open Source (WIP)
This is Slick Board's frontend repository. This is a project I'm running because I want to learn,
and I have a great passion for programming. The plan is to make a stable, nice and easy to use forum
software, that can actually run on live websites.

##### _**As this is a work in progress, installation is not recommended (and not working) yet.**_

##### Requirements
- Apache
- MySQL
- PHP 7.0 (or higher)
- NodeJS (or at least npm)
- Composer

Preferably, you should also have Git installed, just to make your life a whole lot easier.

##### Installation
1. Run `git clone git@github.com:Nozemi/NozumInstaller.git` in the directory you want to clone it from.
Unless specified otherwise, it'll create the folder `NozumInstaller`, which will be the document root for your forum.
2. Run `composer install`.
3. Run `npm install`
4. Run `npm run build`
5. Navigate to your forum from the browser http://_yourdomain.com/forum/path_/Installer/ and follow the steps.