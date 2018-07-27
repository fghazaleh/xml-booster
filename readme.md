## About XmlBooster



### Features:
- Parse Xml file.
- Create a new Google SpreadSheet and store the parsed data.
- Console command interface.

### Tech Features:
- Using PHP 7.x.
- PSR-2 standard code style.
- Configurable.
- Testable.

### System requirements:
- PHP 7.x.
- curl php extension.
- dom php extension.
- xml php extension.
- php composer.

or 

- docker.
- docker compose.

### Installation:

Run the following commands in terminal.

~~~
$ git clone https://github.com/fghazaleh/xml-booster.git
~~~
then..
~~~
$ composer install
~~~

### Manual testing:

Run the console command in terminal. 
~~~
$ ./bin/console
~~~

### Docker testing

Run the docker shell app.
~~~
$ ./app up
~~~

then ..
~~~
$ ./app -e or $ ./app enter
~~~

after that use the above manual testing steps. 


### PHP testing:

To run the **Unit** suites
~~~
$ ./test unit
~~~

To run the **Feature** suites
~~~
$ ./test feature
~~~

To run the **Integration** suites, which use the real APIs of Hacker News,
 this test may take a little bit time to execute.
~~~
$ ./test int
~~~

To run the all suites
~~~
$ ./test all
~~~

#### TODO List:
- Create more unit tests.