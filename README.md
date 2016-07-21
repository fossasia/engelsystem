
[![Code Climate](https://codeclimate.com/github/fossasia/engelsystem/badges/gpa.svg)](https://codeclimate.com/github/fossasia/engelsystem)
[![Build Status](https://travis-ci.org/fossasia/engelsystem.svg?branch=documentation)](https://travis-ci.org/fossasia/engelsystem)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/d56c5bb224f24946965770230e7253c2)](https://www.codacy.com/app/dishant-khanna1807/engelsystem_2?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=fossasia/engelsystem&amp;utm_campaign=Badge_Grade)
[![CircleCI](https://circleci.com/gh/fossasia/engelsystem/tree/development.svg?style=svg)](https://circleci.com/gh/fossasia/engelsystem/tree/development)
[![Dependency Status](https://www.versioneye.com/user/projects/577c9495b50608003eee0161/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/577c9495b50608003eee0161)
[![Dependency Status](https://gemnasium.com/badges/github.com/fossasia/engelsystem.svg)](https://gemnasium.com/github.com/fossasia/engelsystem)

# What is Engelsystem

Engelsystem is a volunteer management application for events written in PHP. An automatic test deployment from the development branch is here http://volunteersys.herokuapp.com

# Installation of Engelsystem

## How do I install Engelsystem on a Server

Please check out [the documentation here](/docs/INSTALLATION_SERVER.md).

## How do I install Engelsystem on my local machine

Please check out [the documentation here](/docs/INSTALLATION_LOCAL.md).

## How do I install Engelsystem on Docker

To install loklak with Docker please refer to the loklak [Docker installation readme](/docs/INSTALLATION_DOCKER.md).

## How do I deploy Engelsystem with Heroku?

Please read how to deploy to [Heroku here](/docs/HEROKU.md)

## Report Bugs

Please report bugs and feature requests here https://github.com/engelsystem/engelsystem/issues

# Features of Engelsystem

### Admin Features

##### News
-   Under this user can view the news.
-   admin can create new news.

##### Meetings
-   Under this page admin/user can view the meetings and time and information regarding the meetings.

##### AngelTypes
-   Under this page user can view the list of angeltypes and users subscribed.
-   admin can view and edit the existing angeltypes and even can add new angeltypes.

##### Ask an archangel
-   Here user/admin can ask questions to all the angels.

##### Shifts Page
-   Views
    -   *Map view* - In the shifts page user/admin can view the shifts in map view where rooms and  shifts according to their timings.
    -   *Normal view*  - when user/admin undo the new style if possible checkbox.
-   Edit shifts(Admin) 
    -   There is a edit image or pencil image on the shifts when we press it admin will be redirected to Edit shifts page where admin can update the information by selecting update shifts option
-   Create new shifts from exisitng shifts(Admin)
    -   Once admin selects the edit option admin can also create new shifts from existing shift data by selecting create new shift option
-   Delete shifts(Admin)
    -   There is an image of bin . Where admin can delete the shifts . On pressing the button admin will be redirected to delete shifts page where we need ot confirm before deleting

##### Sending message
-    User/admin can send messages to other angels or group members or to only members of angeltypes
by clicking th message icon     

#### Admin Pages
-   Arrived Angels
    -   Under this page admin can view all the angels who have arrived .
    -   we can also search for users in the search box .
    -   Admin can also view the statistics of arrival and departure statistics.
-   Active Angels
    -   Under this page admin can view the active angels
    -   Shirt statistics where admin can be know about the number of shirts needed of each size.
-   All Angels
    -   Under this page admin all the users registered their email, phone number, arrived, DECT etc information
    -   Admin can also search for particular user information in the search box
-   Free Angels
    -   Under this page admin can select angeltype and search for angels under the angeltype
-   Create Shifts
    -   Here admin can create new shifts.
    -   Admin can select number of angeltype needed, rooms for shifts and start date and end date for shifts
-   Answerer question
    -   Here admin can answer all the questions asked by all the angels .
-   Shifttypes
    -   Here admin can create new shift types, edit old shift types and delete existing shifts .
-   Rooms
    -   Here admin can add new rooms, edit and delete already created rooms .
-   Group Rights
    -   Here admin can view the description of the rights and edit the groups
-   Frab import
    -   Here admin can import shifts and shifts. Admin needs to enter the add minutes to start and add minutes to end and import a xcal file
    -   Admin can also export user data by clicking the export button .
-   Log
    -   Under this page, admin can view all the activities and their created and modified time and who has made the changes
    -   Admin can even search for the entries
-   Import and export User data
    -   Under this page, admin can import user database(xls/csv format) and export user database to a `.csv` file
-   Admin Settings
    -   Under this page, admin can change specific settings for the admin .
