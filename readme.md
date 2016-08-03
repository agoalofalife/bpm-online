# Http Terrasoft API


- [Description](#Description)
- [Installation](#Installation)
- [Writing Commands](#writing-commands)
    - [Generating Commands](#generating-commands)
    - [Command Structure](#command-structure)
- [Defining Input Expectations](#defining-input-expectations)
    - [Arguments](#arguments)
    - [Options](#options)
    - [Input Arrays](#input-arrays)
    - [Input Descriptions](#input-descriptions)
- [Command I/O](#command-io)
    - [Retrieving Input](#retrieving-input)
    - [Prompting For Input](#prompting-for-input)
    - [Writing Output](#writing-output)
- [Registering Commands](#registering-commands)
- [Programatically Executing Commands](#programatically-executing-commands)
    - [Calling Commands From Other Commands](#calling-commands-from-other-commands)

<a name="Description"></a>
## Description

The wrapper for use with API Terrasoft
Link to the documentation on http [https://academy.terrasoft.ru/documents/technic-sdk/7-8-0/rabota-s-obektami-bpmonline-po-protokolu-odata-s-ispolzovaniem-http]
The package uses the Laravel framework and its environment

###### If you find a bug or something else (and you always have) I ask you to contact me by mail agoalofalife@gmail.com


<a name="Installation"></a>
## Installation

To work correctly you need to write two providers if file app/config.php : 


	agoalofalife\bpmOnline\bpmOnlineServiceProvider::class
        agoalofalife\bpmOnline\bpmRegisterServiceProvider::class

And one facade : 

	CookieBpm' => agoalofalife\bpmOnline\Facade\Authentication::class

Artisan is the command-line interface included with Laravel. It provides a number of helpful commands that can assist you while you build your application. To view a list of all available Artisan commands, you may use the `list` command:

    php artisan list

Every command also includes a "help" screen which displays and describes the command's available arguments and options. To view a help screen, simply precede the name of the command with `help`:

    php artisan help migrate

<a name="writing-commands"></a>
## Writing Commands

In addition to the commands provided with Artisan, you may also build your own custom commands. Commands are typically stored in the `app/Console/Commands` directory; however, you are free to choose your own storage location as long as your commands can be loaded by Composer.

<a name="generating-commands"></a>
### Generating Commands

To create a new command, use the `make:command` Artisan command. This command will create a new command class in the `app/Console/Commands` directory. The generated command will include the default set of properties and methods that are present on all commands:

    php artisan make:command SendEmails
