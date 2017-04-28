### API BPM ONLINE

[![Build Status](https://scrutinizer-ci.com/g/agoalofalife/bpm-online/badges/build.png?b=master)](https://scrutinizer-ci.com/g/agoalofalife/bpm-online/build-status/master)
[![Twitter](https://img.shields.io/twitter/url/https/github.com/agoalofalife/geography.svg?style=social&style=plastic)](https://twitter.com/intent/tweet?text=Wow:&url=%5Bobject%20Object%5D) 
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/agoalofalife/bpm-online/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/agoalofalife/bpm-online/?branch=master)

[RU](#RU) | [EN](#EN)

<a name="RU"></a>
**Что это такое?**


Пакет предоставляет удобный интерфейс для работы с [API Terrasoft](https://academy.terrasoft.ru/documents/technic-sdk/7-8/rabota-s-obektami-bpmonline-po-protokolu-odata-s-ispolzovaniem-http-zaprosov) через протокол OData.

- [Установка](#Installation)
- [Настройка конфигураций](#Config)
- [Аутенфикация](#Authentication)
- [Установка коллекции](#SetCollection)
- [Основные запросы](#BasesRequest)
    - [Select](#Select)
    - [Create](#Create)
    - [Update](#Update)
    - [Delete](#Delete)
- [Обработчики  ответов](#Handler)
- [Логирование](#Log)
- [Интеграция с Laravel](#Laravel)



<a name="Installation"></a>
## Установка

Для установки необходимо выполнить команду из composer

    
    composer require agoalofalife/bpm-online
<a name="Config"></a>
## Настройка конфигураций

Для установки ваших конфигурационных данных есть несколько способов :

 - Через class File
 
```
 //Инициализируем ядро
 $kernel = new KernelBpm();
 $file = new File();
 
 // указываем путь до файла с  конфигурациями
 $file->setSource(__DIR__ . '/config/apiBpm.php');
 // Загружаем их
 $kernel->loadConfiguration($file);
```

Файл должен возвращать массив с конфигурационными данными 
```
return [
	// url для аутентификации
    'UrlLogin' => '',
    //ваш url для запросов по api       
    'UrlHome'  => '',
    'Login'    => '',
    'Password' => ''
    ]
```

 - Через метод setConfigManually в KernelBpm

```
$kernel = new KernelBpm();
// первым параметром передается префикс для конфигурации
$$kernel->setConfigManually('apiBpm', [
        'UrlLogin' => '',
        'Login'    => '',
        'Password' => '',
        'UrlHome'  => ''
]);
```
<a name="Authentication"></a>
## Аутенфикация
Для аутенфикация в BPM API необходимо получить cookie по URL `https://ikratkoe.bpmonline.com/ServiceModel/AuthService.svc/Login`
Для этого необходимо вызвать метод `authentication`

```
$kernel->authentication();
```
Можно не вызывать его и пакет автоматически обновит куки сделав дополнительный запрос.

<a name="SetCollection"></a>

## Установка коллекции

В BPM все таблицы из базы данных именуются как коллекции ( Collections )
Для взаимодействия необходимо установить коллекцию.

```
$kernel->setCollection('CaseCollection');
```
Данный подход имеет минус в дополнительном вызове метода `setCollection` , но позволяет переиспользывать установку коллекции.
Имеется в виду что мы можем один раз установить коллекцию и производить операции по ней.


<a name="BasesRequest"></a>

<a name="Select"></a>

## Select

Все методы принимают первым параметром строку типа операции и тип данных, вторым параметром callback внутрь которого передается тип операции, внутри callback происходит выполнение всех пред - настроек в конце вызывается метод get.
Возращается обьект типа  Handler который обрабатывает ответ от BPM.

```
$handler = $kernel->action('read:json', function ($read){
    $read->amount(1)->skip(100);

})->get();

```
Всего два типа данных `xml` и `json`.
Четыре вида операции `read`, `create`, `update`, `delete`.

**Методы Select**

**filterConstructor** 
Позволяет фильтровать выборку с помощью функции $filter  в запросе
```
filterConstructor('Id eq guid\'00000000-0000-0000-0000-000000000000\'');
```
**orderBy** 
Получать данные в отсортированном виде
Первым параметром название поля, вторым параметром тип сортировки :
по возрастанию (asc)
по убыванию (desc)
```
->orderby('Number', 'desc')
```

**skip** 
Если необходимо пропустить заданное кол - во записей

```
->skip(10)
```

**amount**
Задать максимальное кол - во записей

```
->amount(100)
```
`Имейте в виду что вы можете комбинировать методы согласно документации Terrasoft`

<a name="Create"></a>
## Create

Синтаксис для создания записи такой же как и Select.
Внутри callback необходимо вызвать метод setData и передать ему массив параметров для создания записи в таблице BPM.
```
$handler = $kernel->action('create:xml', function ($creator){
    $creator->setData([
        // array key => value
    ]);
})->get();
```

<a name="Update"></a>
## Update
Для обновления данных в записи в BPM необходимо знать guid записи.
Здесь устанавливается guid и новые параметры для обновления.
```
$handler = $kernel->action('update:json', function ($creator){
    $creator->guid('')->setData([
       'Number' => ''
    ]);
})->get();
```
<a name="Delete"></a>
## Delete
Для удаление записи из бд достаточно знать guid
```
$handler = $kernel->action('delete:xml', function ($creator){
    $creator->guid('');
})->get();
```

<a name="Handler"></a>
## Обработчики  ответов

Вне зависимости от операции всегда возращается тип  Handler, который иммеет несколько методов для преобразования данных.

**toArray**
Преобразовывает данные в массив

**toJson**
Преобразовывает данные в json

**getData**
Получить данные как есть

<a name="Log"></a>
## Логирование

На данный момент пакет сохраняет внутри себя детализацию всех запросов с сортировкой по дате.
Их можно найти в `src/resource/logs/...`

<a name="Laravel"></a>
## Интеграция с Laravel

Для интеграции с фреймворком Laravel необходимо скопировать конфигурации и заполнить их

```
 php artisan vendor:publish --tag=bpm --force
```
Вставить сервис провайдер в файл `config/app.php`

```
 \agoalofalife\bpm\ServiceProviders\BpmBaseServiceProvider::class
```
 
 Далее можно пользоваться извлекая обьект из контейнера 
 
 
    $bpm =  app('bpm');
    $bpm->setCollection('CaseCollection');

     $handler = $bpm->action('read:xml', function ($read){
        $read->amount(1);
    })->get();
 
 
 Либо используя фасад , предварительно зарегистрировав его в файле `config/app.php`:
 
```
   'aliases' => [
 Illuminate\Support\Facades\Facade\Bpm
  ... 
  ]
```


  Клиентский код

```

    Bpm::setCollection('CaseCollection');
     $handler = Bpm::action('read:xml', function ($read){
        $read->amount(1);
    })->get();
```


 <a name="EN"></a>


**What is it ?**

The package provides a convenient interface to work with [API Terrasoft](https://academy.terrasoft.ru/documents/technic-sdk/7-8/rabota-s-obektami-bpmonline-po-protokolu-odata-s-ispolzovaniem-http-zaprosov) through the Protocol

- [Install](#Installation_en)
- [Configurations](#Config_en)
- [Authentication](#Authentication_en)
- [Set Collection](#SetCollection_en)
- [Base Request](#BasesRequest_en)
    - [Select](#Select_en)
    - [Create](#Create_en)
    - [Update](#Update_en)
    - [Delete](#Delete_en)
- [Handler Response](#Handler_en)
- [Log](#Log_en)
- [Integration with Laravel](#Laravel_en)

<a name="Installation_en"></a>
## Install

For installation, you must run the command from composer

    
    composer require agoalofalife/bpm-online
<a name="Config_en"></a>

## Configurations

To install your configuration data there are several ways :

 - class File
 
```
 //Init  kernel
 $kernel = new KernelBpm();
 $file = new File();
 
 // specify the path to the file with the configurations
 $file->setSource(__DIR__ . '/config/apiBpm.php');
 // loading...
 $kernel->loadConfiguration($file);
```


The file must return an array with the data
```
return [
	// url for auth
    'UrlLogin' => '',
    //our url for request api       
    'UrlHome'  => '',
    'Login'    => '',
    'Password' => ''
    ]
```


 - through method setConfigManually in KernelBpm

```
$kernel = new KernelBpm();
// the first parameter is passed a prefix for configuration
$kernel->setConfigManually('apiBpm', [
        'UrlLogin' => '',
        'Login'    => '',
        'Password' => '',
        'UrlHome'  => ''
]);
```


<a name="Authentication_en"></a>
## Authentication

For authentication in BPM API , it is necessary to get cookie in URL

`https://ikratkoe.bpmonline.com/ServiceModel/AuthService.svc/Login`
It is necessary to call the method `authentication`


```
$kernel->authentication();
```

You can not call it and the package will automatically update the cookies by making an additional query.

<a name="SetCollection_en"></a>

## Set Collection

In **BPM**  all tables of the database are referred to as collections  ( Collections )
To communicate, you must install the collection.

```
$kernel->setCollection('CaseCollection');
```

This approach has an additional disadvantage in the method call `setCollection`,  but reuse installation of the collection.
Meaning that we can install a collection and perform operations on it.

<a name="BasesRequest_en"></a>

<a name="Select_en"></a>


## Select

All methods take the first parameter of the string type and the data type, 
the second parameter `callback`  inside of which is passed the type of operation, inside `callback`  executed all pre - settings at the end method is called `get`.

Returns the object type Handler which handler response from BPM.

```
$handler = $kernel->action('read:json', function ($read){
    $read->amount(1)->skip(100);

})->get();

```

Only two types of data `xml` and `json`.
Four types of operations `read`, `create`, `update`, `delete`.

**Methods Select**

**filterConstructor** 

Allows you to filter the selection using the function $filter  in request
```
filterConstructor('Id eq guid\'00000000-0000-0000-0000-000000000000\'');
```

**orderBy** 
To retrieve data in sorted form
The first parameter the field name, the second argument to sort :
ascending (asc)
descending (desc)
```
->orderby('Number', 'desc')
```

**skip** 

If you want to skip the specified number of records

```
->skip(10)
```

**amount**
To set the maximum number of records

```
->amount(100)
```
`Keep in mind that you can combine the methods according to the documentation
Terrasoft`

<a name="Create_en"></a>
## Create

The syntax for creating a record is the same as Select.
Inside  `callback` you must call setData and  pass his array parameters for creating record in  table BPM.

```
$handler = $kernel->action('create:xml', function ($creator){
    $creator->setData([
        // array key => value
    ]);
})->get();
```
<a name="Update_en"></a>

## Update
To update the data in the record BPM you need to know guid record.
This set guid and new  parameters for updates.
```
$handler = $kernel->action('update:json', function ($creator){
    $creator->guid('')->setData([
       'Number' => ''
    ]);
})->get();
```

<a name="Delete"></a>
## Delete

For deleting a record from DB enough to know guid and only guid
```
$handler = $kernel->action('delete:xml', function ($creator){
    $creator->guid('');
})->get();
```

<a name="Handler_en"></a>
## Handler Response

Regardless of the operation always returns a typeп  Handler,  which has several methods to convert the data.

**toArray**
Converts the data into an array

**toJson**
Converts the data to json

**getData**
Just Return the data

<a name="Log_en"></a>
## Log

At the moment, the package keeps a detail of all queries sorted by date.
You can find them in `src/resource/logs/...`

<a name="Laravel_en"></a>
## Integration with Laravel

For integration with the framework Laravel you must copy the configuration and fill them

```
 php artisan vendor:publish --tag=bpm --force
```
Insert the service provider to a file `config/app.php`

```
 \agoalofalife\bpm\ServiceProviders\BpmBaseServiceProvider::class,
```
 Then you can use extract the object from the container.
 
```
    $bpm =  app('bpm');
    $bpm->setCollection('CaseCollection');

     $handler = $bpm->action('read:xml', function ($read){
        $read->amount(1);
    })->get();
```

 
Or by using the Facade , registering it in a file`config/app.php`:
 
```
   'aliases' => [
 Illuminate\Support\Facades\Facade\Bpm
  ... 
  ]
```

  Client code
  
```
    Bpm::setCollection('CaseCollection');
     $handler = Bpm::action('read:xml', function ($read){
        $read->amount(1);
    })->get();
```
