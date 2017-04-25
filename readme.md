


###**API BPM ONLINE**



**Что это такое?**


Пакет предоставляет удобный интерфейс для работы с [API Terrasoft](https://academy.terrasoft.ru/documents/technic-sdk/7-8/rabota-s-obektami-bpmonline-po-protokolu-odata-s-ispolzovaniem-http-zaprosov) через протокол OData.

- [Установка](#Installation)
- [Настройка конфигураций](#Config)
- [Основные запросы](#BasesRequest)
    - [Select](#Select)
    - [Create](#Create)
    - [Update](#Update)
    - [Delete](#Delete)
- [Обработчики  ответов](#Handler)


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

Файл должен возвращать массив с
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
$test->setConfigManually('apiBpm', [
        'UrlLogin' => '',
        'Login'    => '',
        'Password' => '',
        'UrlHome'  => ''
]);
```
<a name="BasesRequest"></a>
