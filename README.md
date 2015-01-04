facebook
========

Integrate Facebook SDK into php

* authentification
* permissions management

Installation
============

Add facebook to your dependencies using composer:

    php composer.phar require "christopherfouquier/facebook":"dev-master"

Parameters
==========

* appId : Application ID
* appSecret : Application Secret
* redirectUrl : Url to redirect
* permissions : array of facebook [oAuth permissions](http://developers.facebook.com/docs/reference/api/permissions) needed for the app

Usage
=====

The class call :

    use facebook\Facebook;

We start sessions

    session_start();

The class is instantiated

    $facebook = new Facebook(
      "xxx",
      "xxx"
    );

It retrieves the login URL.

    $url = $facebook->connect();

It checks if the user is identified.

    if (isset($_SESSION['fb_token']) && $_SESSION['fb_token'] != null) {
      // Connected
    }
    else {
      echo "<a href='". $url ."'>Connection from facebook</a>";
    }

TODO
====

* Add featured
