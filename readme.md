


### API BPM ONLINE



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
filterConstructor('Id eq guid\'00000000-0000-0000-0000-000000000000\'')->run();
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
 \agoalofalife\bpm\ServiceProviders\BpmBaseServiceProvider::class,
 ```
 
 Далее можно пользоваться извлекая обьект из контейнера 
 
 ```
    $bpm =  app('bpm');
    $bpm->setCollection('CaseCollection');

     $handler = $bpm->action('read:xml', function ($read){
        $read->amount(1);
    })->get();
 ```
 
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
 
